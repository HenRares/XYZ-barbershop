<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __invoke(Request $request)
    {
        $period = in_array($request->query('period'), ['Harian', 'Mingguan', 'Bulanan'], true) ? $request->query('period') : 'Harian';
        $cutoff = match ($period) {
            'Mingguan' => now()->subDays(7)->startOfDay(), 'Bulanan' => now()->subMonth()->startOfDay(), default => now()->startOfDay(),
        };
        $bookings = Booking::whereDate('visit_date', '>=', $cutoff->toDateString())->orderByDesc('visit_date')->get();
        $stats = [
            'total' => $bookings->count(), 'online' => $bookings->where('queue_type', Booking::TYPE_ONLINE)->count(),
            'walkin' => $bookings->where('queue_type', Booking::TYPE_WALK_IN)->count(), 'done' => $bookings->where('status', Booking::STATUS_DONE)->count(),
            'cancelled' => $bookings->where('status', Booking::STATUS_CANCELLED)->count(),
        ];
        $rows = $bookings->groupBy(fn (Booking $b) => $b->visit_date->toDateString())->map(function ($items, $date) {
            $counts = $items->countBy('service_name');
            return ['date' => $date, 'total' => $items->count(), 'done' => $items->where('status', Booking::STATUS_DONE)->count(), 'cancelled' => $items->where('status', Booking::STATUS_CANCELLED)->count(), 'top_service' => $counts->sortDesc()->keys()->first() ?? '—'];
        })->values();
        return view('admin.reports', compact('period', 'stats', 'rows'));
    }
}
