<?php

namespace App\Services;

use App\Models\BarberLog;
use App\Models\Booking;
use App\Models\QueueCounter;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BookingStatusReconciler
{
    public function __construct(
        private readonly BarberScheduler $scheduler,
    ) {}

    public function reconcileDate(string $date): int
    {
        try {
            $date = Carbon::parse($date)->toDateString();
        } catch (\Throwable) {
            return 0;
        }

        if ($date > now()->toDateString()) return 0;

        return DB::transaction(function () use ($date) {
            DB::table('queue_counters')->insertOrIgnore([
                'date' => $date,
                'last_number' => Booking::forDate($date)->max('queue_number') ?? 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            QueueCounter::where('date', $date)->lockForUpdate()->firstOrFail();

            $logs = BarberLog::query()
                ->with('booking')
                ->where('service_end_at', '<=', now())
                ->whereHas('booking', fn ($query) => $query
                    ->whereDate('visit_date', $date)
                    ->whereIn('status', [
                        Booking::STATUS_WAITING,
                        Booking::STATUS_SERVING,
                    ]))
                ->lockForUpdate()
                ->get();

            $changed = 0;

            foreach ($logs as $log) {
                $booking = Booking::whereKey($log->booking_id)->lockForUpdate()->first();
                if (! $booking || ! $booking->isActive()) continue;

                if ($booking->status === Booking::STATUS_WAITING) {
                    $booking->update(['status' => Booking::STATUS_CANCELLED]);
                    $this->scheduler->removeSchedule($booking);
                } else {
                    $booking->update(['status' => Booking::STATUS_DONE]);
                    $this->scheduler->markDone($booking);
                }

                $changed++;
            }

            if ($changed > 0) {
                $this->scheduler->rebuildSchedule($date);
            }

            return $changed;
        }, attempts: 5);
    }

    public function reconcileAllExpired(): int
    {
        $dates = Booking::query()
            ->whereIn('status', [Booking::STATUS_WAITING, Booking::STATUS_SERVING])
            ->whereDate('visit_date', '<=', now()->toDateString())
            ->distinct()
            ->orderBy('visit_date')
            ->pluck('visit_date');

        return $dates->sum(
            fn ($date) => $this->reconcileDate(Carbon::parse($date)->toDateString())
        );
    }
}
