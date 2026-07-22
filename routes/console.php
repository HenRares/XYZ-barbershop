<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('queue:recalculate {date?}', function (?string $date = null) {
    $date ??= now()->toDateString();

    app(\App\Services\BarberScheduler::class)
        ->rebuildSchedule($date);

    $this->info("Estimasi antrean {$date} berhasil dihitung ulang.");
});

/*
|--------------------------------------------------------------------------
| Scheduler
|--------------------------------------------------------------------------
*/

Schedule::command(
    'booking:auto-complete'
)->everyMinute();
