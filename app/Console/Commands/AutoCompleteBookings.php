<?php

namespace App\Console\Commands;

use App\Services\BookingStatusReconciler;
use Illuminate\Console\Command;

class AutoCompleteBookings extends Command
{
    protected $signature = 'booking:auto-complete';

    protected $description = 'Menyelesaikan atau membatalkan booking yang slot waktunya sudah lewat';

    public function handle(BookingStatusReconciler $reconciler): int
    {
        $changed = $reconciler->reconcileAllExpired();

        $this->info("Rekonsiliasi selesai. {$changed} booking diperbarui.");

        return self::SUCCESS;
    }
}
