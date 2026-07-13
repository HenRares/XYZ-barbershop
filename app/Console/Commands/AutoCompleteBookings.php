<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\BarberLog;
use Illuminate\Console\Command;

class AutoCompleteBookings extends Command
{
    protected $signature = 'booking:auto-complete';

    protected $description =
    'Menyelesaikan atau membatalkan booking yang slot waktunya sudah lewat';

    public function handle(): int
    {
        $this->info(
            now()->format('Y-m-d H:i:s')
                . ' Scheduler berjalan'
        );

        $scheduler = app(
            \App\Services\BarberScheduler::class
        );

        $logs = BarberLog::query()
            ->where('service_end_at', '<=', now())
            ->whereHas('booking', function ($q) {
                $q->whereIn('status', [
                    Booking::STATUS_WAITING,
                    Booking::STATUS_SERVING,
                ]);
            })
            ->get();

        foreach ($logs as $log) {

            $booking = $log->booking;

            /*
         * Customer tidak datang sampai slot habis
         */
            if ($booking->status === Booking::STATUS_WAITING) {

                $booking->update([
                    'status' => Booking::STATUS_CANCELLED,
                ]);

                $scheduler->rebuildSchedule(
                    $booking->visit_date->toDateString()
                );

                $this->info(
                    "Booking #{$booking->id} dibatalkan (tidak hadir)."
                );
            }

            /*
         * Pelayanan sudah selesai
         */
            if ($booking->status === Booking::STATUS_SERVING) {

                $booking->update([
                    'status' => Booking::STATUS_DONE,
                ]);

                $scheduler->rebuildSchedule(
                    $booking->visit_date->toDateString()
                );

                $this->info(
                    "Booking #{$booking->id} selesai."
                );
            }
        }

        return self::SUCCESS;
    }
}
