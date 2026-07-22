<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\BookingStatusReconciler;
use App\Services\QueueEstimator;

class DashboardController extends Controller
{
    public function __invoke(
        QueueEstimator $estimator,
        BookingStatusReconciler $reconciler
    )
    {
        $today = now()->toDateString();
        $reconciler->reconcileDate($today);
        $bookings = Booking::forDate($today)->orderBy('queue_number')->get();
        $stats = [
            'total' => $bookings->count(),
            'serving' => $bookings->where('status', Booking::STATUS_SERVING)->count(),
            'waiting' => $bookings->where('status', Booking::STATUS_WAITING)->count(),
            'done' => $bookings->where('status', Booking::STATUS_DONE)->count(),
            'cancelled' => $bookings->where('status', Booking::STATUS_CANCELLED)->count(),
        ];
        $currentNumbers = $bookings
            ->where('status', Booking::STATUS_SERVING)
            ->pluck('queue_number')
            ->values();
        $currentLabel = $currentNumbers->isEmpty()
            ? '—'
            : $currentNumbers->map(fn ($number) => '#'.$number)->implode(', ');
        $next = $bookings->firstWhere('status', Booking::STATUS_WAITING);
        $minute = ((int) now()->format('H') * 60) + (int) now()->format('i');
        $barbers = $estimator->activeBarbersAt($today, $minute);
        $waiting = $bookings->where('status', Booking::STATUS_WAITING);
        $avgWait = $waiting->count()
            ? (int) round($waiting->avg(fn (Booking $booking) => $booking->getCurrentWaitingMinutes()))
            : null;
        $recent = $bookings->sortByDesc('queue_number')->take(8);
        return view('admin.dashboard', compact('today', 'stats', 'currentLabel', 'next', 'barbers', 'avgWait', 'recent'));
    }
}
