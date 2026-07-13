<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\QueueEstimator;

class DashboardController extends Controller
{
    public function __invoke(QueueEstimator $estimator)
    {
        $today = now()->toDateString();
        $bookings = Booking::forDate($today)->orderBy('queue_number')->get();
        $stats = [
            'total' => $bookings->count(),
            'serving' => $bookings->where('status', Booking::STATUS_SERVING)->count(),
            'waiting' => $bookings->where('status', Booking::STATUS_WAITING)->count(),
            'done' => $bookings->where('status', Booking::STATUS_DONE)->count(),
            'cancelled' => $bookings->where('status', Booking::STATUS_CANCELLED)->count(),
        ];
        $current = $bookings->firstWhere('status', Booking::STATUS_SERVING);
        $next = $bookings->firstWhere('status', Booking::STATUS_WAITING);
        $minute = ((int) now()->format('H') * 60) + (int) now()->format('i');
        $barbers = $estimator->activeBarbersAt($today, $minute);
        $waiting = $bookings->where('status', Booking::STATUS_WAITING);
        $avgWait = $waiting->count() ? (int) round($waiting->avg('estimated_waiting_time')) : null;
        $recent = $bookings->sortByDesc('queue_number')->take(8);
        return view('admin.dashboard', compact('today', 'stats', 'current', 'next', 'barbers', 'avgWait', 'recent'));
    }
}
