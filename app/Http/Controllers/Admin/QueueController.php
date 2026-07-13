<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Service;
use App\Services\BookingCreator;
use App\Services\QueueEstimator;
use Illuminate\Http\Request;
use App\Services\BarberScheduler;

class QueueController extends Controller
{
    public function index(Request $request)
    {
        $filters = [
            'date' => $request->query('date', now()->toDateString()),
            'status' => $request->query('status', ''),
            'service' => $request->query('service', ''),
            'search' => trim((string) $request->query('search', '')),
        ];
        $query = Booking::query()->with('service')->orderBy('queue_number');
        if ($filters['date']) $query->whereDate('visit_date', $filters['date']);
        if ($filters['status']) $query->where('status', $filters['status']);
        if ($filters['service']) $query->where('service_id', $filters['service']);
        if ($filters['search']) {
            $term = '%'.$filters['search'].'%';
            $query->where(fn ($q) => $q->where('customer_name', 'like', $term)->orWhere('phone', 'like', $term)->orWhere('booking_code', 'like', $term));
        }
        return view('admin.queues', [
            'bookings' => $query->get(), 'services' => Service::orderBy('name')->get(), 'filters' => $filters,
        ]);
    }

    public function storeWalkIn(Request $request, BookingCreator $creator)
    {
        $data = $request->validate([
            'customer_name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:30'],
            'service_id' => ['required', 'integer', 'exists:services,id'],
        ]);
        $data['visit_date'] = now()->toDateString();
        $service = Service::active()->findOrFail($data['service_id']);
        $booking = $creator->create($data, $service, Booking::TYPE_WALK_IN);
        return back()->with('success', "Walk-in ditambahkan: No. {$booking->queue_number}");
    }

    public function updateStatus(Request $request,Booking $booking,QueueEstimator $estimator,BarberScheduler $scheduler)
    {
        $data = $request->validate(['status' => ['required', 'in:Menunggu,Sedang Dilayani,Selesai,Dibatalkan']]);
    

        $allowed = match ($booking->status) {
            Booking::STATUS_WAITING => [Booking::STATUS_SERVING, Booking::STATUS_CANCELLED],
            Booking::STATUS_SERVING => [Booking::STATUS_DONE, Booking::STATUS_CANCELLED],
            default => [],
        };
        abort_unless(in_array($data['status'], $allowed, true), 422, 'Perubahan status tidak valid.');

        $booking->update([
            'status' => $data['status']
        ]);

        $scheduler->rebuildSchedule(
            $booking->visit_date->toDateString()
        );

        return back()->with('success', 'Status diperbarui.');
    }
}
