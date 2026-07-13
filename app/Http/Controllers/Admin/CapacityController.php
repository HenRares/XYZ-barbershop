<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BarberCapacity;
use App\Services\QueueEstimator;
use Illuminate\Http\Request;

class CapacityController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->query('date', now()->toDateString());
        $selected = BarberCapacity::whereDate('date', $date)->first();
        $base = $selected?->active_barbers ?? 4;
        $schedule = [
            ['range' => '10:00 - 12:00', 'barbers' => $base, 'note' => 'Normal'],
            ['range' => '12:00 - 14:00', 'barbers' => max(1, $base - 1), 'note' => 'Istirahat bergantian'],
            ['range' => '14:00 - 18:00', 'barbers' => $base, 'note' => 'Normal'],
            ['range' => '18:00 - 20:00', 'barbers' => max(1, $base - 1), 'note' => 'Istirahat bergantian'],
            ['range' => '20:00 - 21:00', 'barbers' => $base, 'note' => 'Normal'],
        ];
        return view('admin.capacities', ['date' => $date, 'selected' => $selected, 'base' => $base, 'schedule' => $schedule, 'capacities' => BarberCapacity::latest('date')->get()]);
    }

    public function store(Request $request, QueueEstimator $estimator)
    {
        $data = $request->validate(['date' => ['required', 'date'], 'active_barbers' => ['required', 'integer', 'min:1', 'max:20']]);
        BarberCapacity::updateOrCreate(['date' => $data['date']], ['active_barbers' => $data['active_barbers'], 'opening_time' => '10:00', 'closing_time' => '21:00']);
        $estimator->recalculateDay($data['date']);
        return redirect()->route('admin.capacities', ['date' => $data['date']])->with('success', 'Kapasitas tersimpan.');
    }
}
