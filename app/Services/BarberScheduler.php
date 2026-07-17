<?php

namespace App\Services;

use App\Models\BarberCapacity;
use App\Models\BarberLog;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BarberScheduler
{
    /**
     * Menentukan barber slot untuk booking yang baru dibuat.
     *
     * Logic:
     * - Ambil jumlah barber aktif pada tanggal booking.
     * - Cari barber yang paling cepat selesai melayani pelanggan sebelumnya.
     * - Jika ada barber yang belum memiliki antrean, gunakan barber tersebut.
     * - Simpan jadwal pelayanan ke tabel barber_logs.
     */
    public function assign(Booking $booking): BarberLog
    {
        $date = $booking->visit_date;

        /*
        |--------------------------------------------------------------------------
        | Hitung jadwal barber menggunakan algoritma yang sama
        | dengan halaman preview.
        |--------------------------------------------------------------------------
        */
        $schedule = $this->calculateSchedule(
            $booking->visit_date
        );
        $selectedSlot = $schedule['slot'];
        $selectedStart = $schedule['startAt'];

                /*
        |--------------------------------------------------------------------------
        | Hitung waktu selesai pelayanan
        |--------------------------------------------------------------------------
        */
        $serviceEnd = (clone $selectedStart)
            ->addMinutes(
                (int) $booking->service_duration
            );

        /*
        |--------------------------------------------------------------------------
        | Simpan jadwal barber
        |--------------------------------------------------------------------------
        */
        return BarberLog::create([
            'booking_id' => $booking->id,
            'barber_slot' => $selectedSlot,
            'service_start_at' => $selectedStart,
            'service_end_at' => $serviceEnd,
        ]);
    }

    /**
     * ============================================================
     * Menghitung jadwal barber yang paling cepat tersedia.
     *
     * Method ini digunakan oleh:
     * - preview()  -> hanya menampilkan estimasi.
     * - assign()   -> menyimpan hasil penjadwalan.
     *
     * Dengan menggunakan satu algoritma yang sama,
     * estimasi pada halaman Booking dan Booking Berhasil
     * akan selalu konsisten.
     * ============================================================
     */

    private function calculateSchedule(string $date): array
    {
        /*
        |--------------------------------------------------------------------------
        | Ambil jumlah barber aktif
        |--------------------------------------------------------------------------
        */
        $activeBarbers = (int) (
            BarberCapacity::whereDate('date', $date)
                ->value('active_barbers')
            ?? 1
        );
        /*
        |--------------------------------------------------------------------------
        | Default barber pertama
        |--------------------------------------------------------------------------
        */
        $selectedSlot = 1;
        $selectedStart = now();
        $earliestTime = null;

        /*
        |--------------------------------------------------------------------------
        | Cari barber yang paling cepat tersedia
        |--------------------------------------------------------------------------
        */
        for ($slot = 1; $slot <= $activeBarbers; $slot++) {
            $lastLog = BarberLog::where('barber_slot', $slot)
                ->whereHas('booking', function ($q) use ($date) {
                    $q->whereDate('visit_date', $date);
                })
                ->orderByDesc('service_end_at')
                ->first();
            /*
            |--------------------------------------------------------------------------
            | Barber belum memiliki antrean
            |--------------------------------------------------------------------------
            */
            if (!$lastLog) {
                $selectedSlot = $slot;
                $selectedStart = now();
                break;
            }

            /*
            |--------------------------------------------------------------------------
            | Barber tersedia setelah selesai melayani
            |--------------------------------------------------------------------------
            */
            $availableAt = Carbon::parse(
                $lastLog->service_end_at
            )->max(now());

            /*
            |--------------------------------------------------------------------------
            | Pilih barber dengan waktu selesai tercepat
            |--------------------------------------------------------------------------
            */
            if (
                $earliestTime === null ||
                $availableAt->lt($earliestTime)
            ) {
                $earliestTime = $availableAt;
                $selectedSlot = $slot;
                $selectedStart = $availableAt;
            }
        }

        return [
            'slot' => $selectedSlot,
            'startAt' => $selectedStart,
        ];
    }


    /**
     * ============================================================
     * Preview estimasi antrean.
     *
     * Digunakan pada halaman Booking sebelum pelanggan
     * melakukan konfirmasi.
     * ============================================================
     */
    public function preview(string $date, int $duration): array
    {
        $schedule = $this->calculateSchedule($date);

        return [
            'barberSlot'    => $schedule['slot'],
            'serviceStartAt'=> $schedule['startAt'],
        ];
    }



    public function rebuildSchedule(string $date): void
    {
        DB::transaction(function () use ($date) {

            $activeBarbers = (int) (
                BarberCapacity::whereDate('date', $date)
                ->value('active_barbers')
                ?? 1
            );

            /*
            * Hapus hanya barber log untuk booking MENUNGGU dan CANCEL
            */
            BarberLog::whereHas('booking', function ($q) use ($date) {
                $q->whereDate('visit_date', $date)
                    // ->where('status', Booking::STATUS_WAITING);
                    ->whereIn('status', [
                        Booking::STATUS_WAITING,
                        Booking::STATUS_CANCELLED,
                    ]);
            })->delete();

            /*
            * Ambil booking yang masih MENUNGGU
            */
            $bookings = Booking::query()
                ->whereDate('visit_date', $date)
                ->where('status', Booking::STATUS_WAITING)
                ->orderBy('queue_number')
                ->get();

            /*
            * Bangun slot barber dari data yang MASIH ADA
            * (Sedang Dilayani / Selesai)
            */
            $slots = [];

            for ($i = 1; $i <= $activeBarbers; $i++) {

                $lastLog = BarberLog::query()
                    ->where('barber_slot', $i)
                    // ->whereHas('booking', function ($q) use ($date) {
                    //     $q->whereDate('visit_date', $date);
                    // })

                    ->whereHas('booking', function ($q) use ($date) {
                        $q->whereDate('visit_date', $date)
                        ->whereIn('status', [
                            Booking::STATUS_SERVING,
                            Booking::STATUS_DONE,
                        ]);
                    })
                    ->orderByDesc('service_end_at')
                    ->first();

                $slots[$i] = $lastLog
                    ? Carbon::parse($lastLog->service_end_at)->max(now())
                    : now();
            }

            /*
            * Jadwalkan ulang booking yang menunggu
            */
            foreach ($bookings as $booking) {

                asort($slots);

                $slotNumber = array_key_first($slots);

                $startAt = clone $slots[$slotNumber];

                $endAt = (clone $startAt)
                    ->addMinutes(
                        (int) $booking->service_duration
                    );

                BarberLog::create([
                    'booking_id' => $booking->id,
                    'barber_slot' => $slotNumber,
                    'service_start_at' => $startAt,
                    'service_end_at' => $endAt,
                    // 'status' => 'waiting',
                ]);

                $booking->update([
                    'estimated_service_time' => $startAt->format('H:i'),

                    'estimated_waiting_time' => max(
                        0,
                        now()->diffInMinutes(
                            $startAt,
                            false
                        )
                    ),
                ]);

                $slots[$slotNumber] = $endAt;
            }
        });
    }
}
