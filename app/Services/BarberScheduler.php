<?php

namespace App\Services;

use App\Models\BarberLog;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BarberScheduler
{
    public function __construct(
        private readonly QueueEstimator $estimator,
    ) {}

    public function assign(Booking $booking): BarberLog
    {
        $date = $booking->visit_date->toDateString();
        $schedule = $this->calculateSchedule($date, (int) $booking->service_duration);
        $serviceEnd = $schedule['startAt']->copy()->addMinutes((int) $booking->service_duration);

        $log = BarberLog::updateOrCreate(
            ['booking_id' => $booking->id],
            [
                'barber_slot' => $schedule['slot'],
                'service_start_at' => $schedule['startAt'],
                'service_end_at' => $serviceEnd,
                'status' => 'waiting',
            ]
        );

        return $log;
    }

    /** @return array{barberSlot:int,serviceStartAt:Carbon} */
    public function preview(string $date, int $duration): array
    {
        $schedule = $this->calculateSchedule($date, $duration);

        return [
            'barberSlot' => $schedule['slot'],
            'serviceStartAt' => $schedule['startAt'],
        ];
    }

    public function startServing(Booking $booking): BarberLog
    {
        $date = $booking->visit_date->toDateString();

        if ($date !== now()->toDateString()) {
            throw ValidationException::withMessages([
                'status' => 'Hanya antrean hari ini yang dapat mulai dilayani.',
            ]);
        }

        $settings = $this->estimator->settingsForDate($date);
        $now = now();
        $opening = Carbon::parse("{$date} {$settings['opening_time']}");
        $closing = Carbon::parse("{$date} {$settings['closing_time']}");

        if ($now->lt($opening) || $now->gte($closing)) {
            throw ValidationException::withMessages([
                'status' => "Pelayanan hanya dapat dimulai pukul {$settings['opening_time']} sampai {$settings['closing_time']}.",
            ]);
        }

        $activeBarbers = $this->estimator->activeBarbersAt(
            $date,
            $this->estimator->minuteOfDay($now)
        );

        $serviceEnd = $now->copy()->addMinutes((int) $booking->service_duration);
        if ($serviceEnd->gt($closing)) {
            throw ValidationException::withMessages([
                'status' => 'Durasi layanan akan melewati jam tutup. Antrean tidak dapat mulai dilayani.',
            ]);
        }

        $eligibleSlots = collect(range(1, $activeBarbers))
            ->filter(fn (int $slot) => $this->slotAvailableForInterval(
                $date,
                $slot,
                $now,
                $serviceEnd,
                $settings['active_barbers']
            ));

        $servingBookings = Booking::query()
            ->with('barberLog')
            ->where('id', '!=', $booking->id)
            ->whereDate('visit_date', $date)
            ->where('status', Booking::STATUS_SERVING)
            ->lockForUpdate()
            ->get();

        // Kapasitas dihitung berdasarkan jumlah pelanggan yang sedang dilayani,
        // sehingga data log lama yang tidak lengkap tidak dapat melewati batas.
        if ($servingBookings->count() >= $activeBarbers) {
            throw ValidationException::withMessages([
                'status' => "Semua {$activeBarbers} barber sedang melayani pelanggan. Selesaikan salah satu antrean terlebih dahulu.",
            ]);
        }

        $usedSlots = $servingBookings
            ->pluck('barberLog.barber_slot')
            ->filter(fn ($slot) => $slot !== null)
            ->map(fn ($slot) => (int) $slot)
            ->filter(fn (int $slot) => $slot >= 1 && $slot <= $activeBarbers)
            ->unique()
            ->values();

        // Reservasi slot tambahan untuk log legacy yang hilang/duplikat.
        foreach (range(1, $activeBarbers) as $candidateSlot) {
            if ($usedSlots->count() >= $servingBookings->count()) break;
            if (! $usedSlots->contains($candidateSlot)) $usedSlots->push($candidateSlot);
        }

        $existingSlot = (int) ($booking->barberLog?->barber_slot ?? 0);
        $availableSlots = $eligibleSlots->diff($usedSlots);
        if ($availableSlots->isEmpty()) {
            throw ValidationException::withMessages([
                'status' => 'Tidak ada barber yang tersedia untuk seluruh durasi layanan, termasuk periode istirahat.',
            ]);
        }

        $slot = $availableSlots->contains($existingSlot)
            ? $existingSlot
            : (int) $availableSlots->first();

        $log = BarberLog::updateOrCreate(
            ['booking_id' => $booking->id],
            [
                'barber_slot' => $slot,
                'service_start_at' => $now,
                'service_end_at' => $serviceEnd,
                'status' => 'serving',
            ]
        );

        $booking->update([
            'estimated_waiting_time' => 0,
            'estimated_service_time' => $now->format('H:i'),
        ]);

        return $log;
    }

    public function markDone(Booking $booking): void
    {
        $booking->barberLog?->update([
            'service_end_at' => now(),
            'status' => 'done',
        ]);
    }

    public function removeSchedule(Booking $booking): void
    {
        $booking->barberLog?->delete();
    }

    public function rebuildSchedule(string $date): void
    {
        DB::transaction(function () use ($date) {
            BarberLog::whereHas('booking', fn ($query) => $query
                ->whereDate('visit_date', $date)
                ->whereIn('status', [Booking::STATUS_WAITING, Booking::STATUS_CANCELLED]))
                ->delete();

            $bookings = Booking::query()
                ->whereDate('visit_date', $date)
                ->where('status', Booking::STATUS_WAITING)
                ->orderBy('queue_number')
                ->lockForUpdate()
                ->get();

            foreach ($bookings as $booking) {
                $log = $this->assign($booking);

                $booking->update([
                    'estimated_service_time' => $log->service_start_at->format('H:i'),
                    'estimated_waiting_time' => max(
                        0,
                        (int) now()->diffInMinutes($log->service_start_at, false)
                    ),
                ]);
            }
        });
    }

    /** @return array{slot:int,startAt:Carbon} */
    private function calculateSchedule(string $date, int $duration): array
    {
        $settings = $this->estimator->settingsForDate($date);
        $opening = Carbon::parse("{$date} {$settings['opening_time']}");
        $closing = Carbon::parse("{$date} {$settings['closing_time']}");
        $baseStart = $date === now()->toDateString()
            ? $opening->copy()->max(now())
            : $opening->copy();

        $candidates = collect();

        for ($slot = 1; $slot <= $settings['active_barbers']; $slot++) {
            $lastLog = BarberLog::query()
                ->where('barber_slot', $slot)
                ->whereHas('booking', fn ($query) => $query
                    ->whereDate('visit_date', $date)
                    ->whereIn('status', [
                        Booking::STATUS_WAITING,
                        Booking::STATUS_SERVING,
                        Booking::STATUS_DONE,
                    ]))
                ->orderByDesc('service_end_at')
                ->first();

            $availableAt = $lastLog
                ? Carbon::parse($lastLog->service_end_at)->max($baseStart)
                : $baseStart->copy();
            $availableAt = $this->nextAllowedStart(
                $date,
                $slot,
                $availableAt,
                $closing,
                $duration,
                $settings['active_barbers']
            );

            if ($availableAt && $availableAt->copy()->addMinutes($duration)->lte($closing)) {
                $candidates->push(['slot' => $slot, 'startAt' => $availableAt]);
            }
        }

        $selected = $candidates
            ->sortBy(fn (array $candidate) => sprintf(
                '%s-%03d',
                $candidate['startAt']->format('Y-m-d H:i:s'),
                $candidate['slot']
            ))
            ->first();

        if (! $selected) {
            throw ValidationException::withMessages([
                'visit_date' => 'Jadwal barber pada tanggal tersebut sudah penuh atau berada di luar jam operasional.',
            ]);
        }

        return $selected;
    }

    private function nextAllowedStart(
        string $date,
        int $slot,
        Carbon $candidate,
        Carbon $closing,
        int $duration,
        int $baseBarbers
    ): ?Carbon {
        while ($candidate->lt($closing)) {
            $serviceEnd = $candidate->copy()->addMinutes($duration);
            if ($serviceEnd->gt($closing)) return null;

            $blockedUntil = null;
            foreach ([['12:00', '14:00'], ['18:00', '20:00']] as [$from, $until]) {
                $breakStart = Carbon::parse("{$date} {$from}");
                $breakEnd = Carbon::parse("{$date} {$until}");
                $breakCapacity = $this->estimator->effectiveBarbersForBase(
                    $baseBarbers,
                    $this->estimator->toMinutes($from)
                );

                // Slot barber harus tersedia untuk seluruh durasi layanan,
                // bukan hanya pada menit pertama.
                if (
                    $slot > $breakCapacity
                    && $candidate->lt($breakEnd)
                    && $serviceEnd->gt($breakStart)
                ) {
                    $blockedUntil = $breakEnd;
                    break;
                }
            }

            if (! $blockedUntil) return $candidate;

            $candidate = $blockedUntil;
        }

        return null;
    }

    private function slotAvailableForInterval(
        string $date,
        int $slot,
        Carbon $start,
        Carbon $end,
        int $baseBarbers
    ): bool {
        foreach ([['12:00', '14:00'], ['18:00', '20:00']] as [$from, $until]) {
            $breakStart = Carbon::parse("{$date} {$from}");
            $breakEnd = Carbon::parse("{$date} {$until}");
            $breakCapacity = $this->estimator->effectiveBarbersForBase(
                $baseBarbers,
                $this->estimator->toMinutes($from)
            );

            if (
                $slot > $breakCapacity
                && $start->lt($breakEnd)
                && $end->gt($breakStart)
            ) {
                return false;
            }
        }

        return true;
    }
}
