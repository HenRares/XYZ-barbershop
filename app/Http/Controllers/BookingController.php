<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Service;
use App\Services\BookingCreator;
use App\Services\QueueEstimator;
use Illuminate\Http\Request;
use App\Services\BarberScheduler;
use App\Models\BarberCapacity;

class BookingController extends Controller
{
    public function create(Request $request)
    {
        return view('booking.create', [
            'services' => Service::active()->orderBy('name')->get(),
            'selectedService' => $request->string('service')->toString(),
            'today' => now()->toDateString(),
        ]);
    }

    public function estimate(
        Request $request,
        BarberScheduler $scheduler
    )
    {
        $data = $request->validate([
            'service_id' => ['required', 'integer', 'exists:services,id'],
            'visit_date' => ['required', 'date'],
        ]);

        $service = Service::findOrFail(
            $data['service_id']
        );

        $nextNumber =
            ((int) Booking::forDate(
                $data['visit_date']
            )->max('queue_number')) + 1;

        $preview = $scheduler->preview(
            $data['visit_date'],
            $service->duration
        );

        $waitingMinutes = (int) round(
            max(
                0,
                now()->diffInMinutes(
                    $preview['serviceStartAt'],
                    false
                )
            )
        );

        return response()->json([
            'nextNumber' => $nextNumber,

            'currentServingNumber' =>
                Booking::forDate(
                    $data['visit_date']
                )
                ->where('status', Booking::STATUS_SERVING)
                ->value('queue_number') ?? 0,

            'waitingBefore' =>
                Booking::forDate(
                    $data['visit_date']
                )
                ->activeQueue()
                ->count(),

            'activeBarbers' =>
                BarberCapacity::whereDate(
                    'date',
                    $data['visit_date']
                )->value('active_barbers') ?? 1,

            'waitingMinutes' =>
                $waitingMinutes,

            'serviceTime' =>
                $preview['serviceStartAt']
                    ->format('H:i'),
        ]);
    }

    public function store(Request $request, BookingCreator $creator)
    {
        $data = $request->validate([
            'customer_name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:30'],
            'service_id' => ['required', 'integer', 'exists:services,id'],
            'visit_date' => ['required', 'date', 'after_or_equal:today'],
        ]);
        $service = Service::active()->findOrFail($data['service_id']);
        $booking = $creator->create($data, $service, Booking::TYPE_ONLINE, $request->user());
        return redirect()->route('booking.success', $booking->public_id)
            ->with('success', "Booking berhasil! Nomor antrean Anda {$booking->queue_number}");
    }

    public function success(Booking $booking)
    {
        return view('booking.success', compact('booking'));
    }
}
