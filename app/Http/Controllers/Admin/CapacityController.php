<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BarberCapacity;
use App\Models\Booking;
use App\Models\QueueCounter;
use App\Services\BarberScheduler;
use App\Services\QueueEstimator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CapacityController extends Controller
{
    public function index(Request $request)
    {
        $date = (string) $request->query('date', now()->toDateString());
        if (! validator(
            ['date' => $date],
            ['date' => ['required', 'date_format:Y-m-d']]
        )->passes()) {
            $date = now()->toDateString();
        }

        $selected = BarberCapacity::whereDate('date', $date)->first();
        $base = $selected?->active_barbers ?? QueueEstimator::DEFAULT_BARBERS;
        $schedule = [
            ['range' => '10:00 - 12:00', 'barbers' => $base, 'note' => 'Normal'],
            ['range' => '12:00 - 14:00', 'barbers' => max(1, $base - 1), 'note' => 'Istirahat bergantian'],
            ['range' => '14:00 - 18:00', 'barbers' => $base, 'note' => 'Normal'],
            ['range' => '18:00 - 20:00', 'barbers' => max(1, $base - 1), 'note' => 'Istirahat bergantian'],
            ['range' => '20:00 - 21:00', 'barbers' => $base, 'note' => 'Normal'],
        ];
        return view('admin.capacities', ['date' => $date, 'selected' => $selected, 'base' => $base, 'schedule' => $schedule, 'capacities' => BarberCapacity::latest('date')->get()]);
    }

    public function store(
        Request $request,
        QueueEstimator $estimator,
        BarberScheduler $scheduler
    )
    {
        $data = $request->validate([
            'date' => ['required', 'date_format:Y-m-d'],
            'active_barbers' => ['required', 'integer', 'min:1', 'max:20'],
        ]);

        DB::transaction(function () use ($data, $estimator, $scheduler) {
            DB::table('queue_counters')->insertOrIgnore([
                'date' => $data['date'],
                'last_number' => Booking::forDate($data['date'])->max('queue_number') ?? 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            QueueCounter::where('date', $data['date'])->lockForUpdate()->firstOrFail();

            $minute = $data['date'] === now()->toDateString()
                ? $estimator->minuteOfDay()
                : $estimator->toMinutes(QueueEstimator::DEFAULT_OPEN);
            $effectiveCapacity = $estimator->effectiveBarbersForBase(
                (int) $data['active_barbers'],
                $minute
            );

            $servingCount = Booking::forDate($data['date'])
                ->where('status', Booking::STATUS_SERVING)
                ->lockForUpdate()
                ->get()
                ->count();

            if ($servingCount > $effectiveCapacity) {
                throw ValidationException::withMessages([
                    'active_barbers' => "Masih ada {$servingCount} pelanggan yang sedang dilayani. Kapasitas efektif tidak boleh kurang dari jumlah tersebut.",
                ]);
            }

            BarberCapacity::updateOrCreate(
                ['date' => $data['date']],
                [
                    'active_barbers' => $data['active_barbers'],
                    'opening_time' => QueueEstimator::DEFAULT_OPEN,
                    'closing_time' => QueueEstimator::DEFAULT_CLOSE,
                ]
            );

            // Barber logs dan kolom estimasi booking harus diperbarui bersama-sama.
            $scheduler->rebuildSchedule($data['date']);
        }, attempts: 5);

        return redirect()->route('admin.capacities', ['date' => $data['date']])->with('success', 'Kapasitas tersimpan.');
    }
}
