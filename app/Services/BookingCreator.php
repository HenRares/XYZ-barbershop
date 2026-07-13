<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\QueueCounter;
use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Services\BarberScheduler;


class BookingCreator
{
 public function __construct(
        private readonly QueueEstimator $estimator,
        private readonly BarberScheduler $scheduler,
    ) {}

    public function create(array $data, Service $service, string $queueType, ?User $user = null): Booking
    {
        return DB::transaction(function () use ($data, $service, $queueType, $user) {
            $date = $data['visit_date'];

            DB::table('queue_counters')->insertOrIgnore([
                'date' => $date,
                'last_number' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            /** @var QueueCounter $counter */
            $counter = QueueCounter::whereDate('date', $date)->lockForUpdate()->firstOrFail();
            $queueNumber = $counter->last_number + 1;
            $counter->update(['last_number' => $queueNumber]);

            $dayBookings = Booking::forDate($date)->orderBy('queue_number')->lockForUpdate()->get();
            $estimate = $this->estimator->estimate($date, $queueNumber, (int) $service->duration, $dayBookings);

            $booking = Booking::create([
                'public_id' => (string) Str::ulid(),
                'booking_code' => null,
                'queue_number' => $queueNumber,
                'customer_name' => $data['customer_name'],
                'phone' => $data['phone'],
                'service_id' => $service->id,
                'service_name' => $service->name,
                'service_duration' => $service->duration,
                'visit_date' => $date,
                'estimated_waiting_time' => $estimate['waitingMinutes'],
                'estimated_service_time' => $estimate['serviceTime'],
                'queue_type' => $queueType,
                'status' => Booking::STATUS_WAITING,
                'user_id' => $user?->id,
            ]);
            $log = $this->scheduler->assign($booking);


            /*
            |--------------------------------------------------------------------------
            | Perbarui informasi booking berdasarkan jadwal FINAL barber.
            |
            | Nilai ini akan digunakan pada:
            | - Halaman Booking Berhasil
            | - Halaman Antrean Saya
            | - Dashboard Admin
            |
            | Dengan demikian seluruh aplikasi menggunakan satu
            | sumber estimasi yang sama.
            |--------------------------------------------------------------------------
            */
            $booking->update([
                'estimated_waiting_time' => max(
                    0,
                    now()->diffInMinutes(
                        $log->service_start_at,
                        false
                    )
                ),

                'estimated_service_time' =>
                    $log->service_start_at->format('H:i'),

                'booking_code' => 'XYZ-' . str_pad(
                    (string) $booking->id,
                    4,
                    '0',
                    STR_PAD_LEFT
                ),
            ]);


            return $booking->fresh();
        }, attempts: 5);
    }
}


