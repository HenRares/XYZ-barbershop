<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\QueueCounter;
use App\Models\Service;
use App\Services\BookingCreator;
use App\Services\BookingStatusReconciler;
use App\Services\BarberScheduler;
use App\Services\QueueEstimator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class QueueController extends Controller
{
    public function index(
        Request $request,
        BookingStatusReconciler $reconciler,
        QueueEstimator $estimator
    )
    {
        $requestedDate = (string) $request->query('date', now()->toDateString());
        if (! validator(
            ['date' => $requestedDate],
            ['date' => ['required', 'date_format:Y-m-d']]
        )->passes()) {
            $requestedDate = now()->toDateString();
        }

        $filters = [
            'date' => $requestedDate,
            'status' => $request->query('status', ''),
            'service' => $request->query('service', ''),
            'search' => trim((string) $request->query('search', '')),
        ];
        $reconciler->reconcileDate($filters['date']);

        $query = Booking::query()->with('service')->orderBy('queue_number');
        if ($filters['date']) $query->whereDate('visit_date', $filters['date']);
        if ($filters['status']) $query->where('status', $filters['status']);
        if ($filters['service']) $query->where('service_id', $filters['service']);
        if ($filters['search']) {
            $term = '%'.$filters['search'].'%';
            $query->where(fn ($q) => $q->where('customer_name', 'like', $term)->orWhere('phone', 'like', $term)->orWhere('booking_code', 'like', $term));
        }
        $settings = $estimator->settingsForDate($filters['date']);
        $minute = $filters['date'] === now()->toDateString()
            ? $estimator->minuteOfDay()
            : $estimator->toMinutes($settings['opening_time']);
        $effectiveCapacity = $estimator->activeBarbersAt($filters['date'], $minute);
        $servingCount = Booking::forDate($filters['date'])
            ->where('status', Booking::STATUS_SERVING)
            ->count();
        $withinHours = now()->format('H:i') >= $settings['opening_time']
            && now()->format('H:i') < $settings['closing_time'];

        return view('admin.queues', [
            'bookings' => $query->get(),
            'services' => Service::orderBy('name')->get(),
            'filters' => $filters,
            'canStartServing' => $filters['date'] === now()->toDateString()
                && $withinHours
                && $servingCount < $effectiveCapacity,
            'effectiveCapacity' => $effectiveCapacity,
            'servingCount' => $servingCount,
        ]);
    }

    public function storeWalkIn(Request $request, BookingCreator $creator)
    {
        $data = $request->validate([
            'customer_name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'min:8', 'max:30', 'regex:/^[0-9+()\-\s]+$/'],
            'service_id' => ['required', 'integer', 'exists:services,id'],
        ]);
        $data['visit_date'] = now()->toDateString();
        $data['customer_name'] = trim($data['customer_name']);
        $data['phone'] = trim($data['phone']);
        $service = Service::active()->findOrFail($data['service_id']);
        $booking = $creator->create($data, $service, Booking::TYPE_WALK_IN);
        return back()->with('success', "Walk-in ditambahkan: No. {$booking->queue_number}");
    }

    public function updateStatus(
        Request $request,
        Booking $booking,
        BarberScheduler $scheduler
    ) {
        $data = $request->validate([
            'status' => [
                'required',
                'in:Menunggu,Sedang Dilayani,Selesai,Dibatalkan',
            ],
        ]);

        $newStatus = $data['status'];
        $date = $booking->visit_date->toDateString();

        /*
        * Transaksi utama hanya menangani perubahan status.
        * rebuildSchedule tidak dimasukkan ke transaksi utama
        * agar kegagalan perhitungan ulang jadwal tidak membatalkan status.
        */
        DB::transaction(function () use (
            $booking,
            $newStatus,
            $date,
            $scheduler
        ) {
            // Mengunci perubahan antrean pada tanggal yang sama.
            DB::table('queue_counters')->insertOrIgnore([
                'date' => $date,
                'last_number' => Booking::forDate($date)
                    ->max('queue_number') ?? 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            QueueCounter::where('date', $date)
                ->lockForUpdate()
                ->firstOrFail();

            $booking = Booking::whereKey($booking->id)
                ->lockForUpdate()
                ->firstOrFail();

            $allowed = match ($booking->status) {
                Booking::STATUS_WAITING => [
                    Booking::STATUS_SERVING,
                    Booking::STATUS_CANCELLED,
                ],

                Booking::STATUS_SERVING => [
                    Booking::STATUS_DONE,
                    Booking::STATUS_CANCELLED,
                ],

                default => [],
            };

            abort_unless(
                in_array($newStatus, $allowed, true),
                422,
                'Perubahan status tidak valid.'
            );

            /*
            * Proteksi kapasitas hanya dijalankan ketika
            * pelanggan mulai dilayani.
            */
            if ($newStatus === Booking::STATUS_SERVING) {
                $scheduler->startServing($booking);

                $booking->update([
                    'status' => Booking::STATUS_SERVING,
                ]);

                return;
            }

            /*
            * Menyelesaikan pelayanan tidak perlu
            * mengecek kapasitas atau jam operasional.
            */
            if ($newStatus === Booking::STATUS_DONE) {
                $booking->update([
                    'status' => Booking::STATUS_DONE,
                ]);

                $scheduler->markDone($booking);

                return;
            }

            /*
            * Membatalkan antrean dan menghapus
            * jadwal barber yang berkaitan.
            */
            if ($newStatus === Booking::STATUS_CANCELLED) {
                $booking->update([
                    'status' => Booking::STATUS_CANCELLED,
                ]);

                $scheduler->removeSchedule($booking);
            }
        }, attempts: 5);

        /*
        * Perhitungan ulang jadwal dilakukan setelah transaksi utama selesai.
        * Kegagalan rebuild tidak membatalkan status yang sudah berhasil diubah.
        */
        if (in_array($newStatus, [
            Booking::STATUS_DONE,
            Booking::STATUS_CANCELLED,
        ], true)) {
            try {
                DB::transaction(function () use ($scheduler, $date) {
                    $scheduler->rebuildSchedule($date);
                }, attempts: 3);
            } catch (\Throwable $exception) {
                report($exception);

                return back()
                    ->with('success', 'Status berhasil diperbarui.')
                    ->with(
                        'warning',
                        'Jadwal antrean belum berhasil dihitung ulang, tetapi perubahan status sudah tersimpan.'
                    );
            }
        }

        return back()->with('success', 'Status diperbarui.');
    }
}
