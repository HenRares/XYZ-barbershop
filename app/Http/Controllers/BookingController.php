<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\QueueCounter;
use App\Models\Service;
use App\Services\BookingCreator;
use App\Services\QueueEstimator;
use Illuminate\Http\Request;
use App\Services\BarberScheduler;
use Illuminate\Validation\Rule;

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
        BarberScheduler $scheduler,
        QueueEstimator $estimator
    )
    {
        $data = $request->validate([
            'service_id' => [
                'required',
                'integer',
                Rule::exists('services', 'id')->where('status', 'aktif'),
            ],
            'visit_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
        ]);

        $service = Service::active()->findOrFail(
            $data['service_id']
        );

        $lastCounter = (int) (QueueCounter::whereDate('date', $data['visit_date'])
            ->value('last_number') ?? 0);
        $lastBooking = (int) (Booking::forDate($data['visit_date'])
            ->max('queue_number') ?? 0);
        $nextNumber = max($lastCounter, $lastBooking) + 1;

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

        $servingNumbers = Booking::forDate($data['visit_date'])
            ->where('status', Booking::STATUS_SERVING)
            ->orderBy('queue_number')
            ->pluck('queue_number');

        return response()->json([
            'nextNumber' => $nextNumber,

            'currentServingLabel' => $servingNumbers->isEmpty()
                ? 'Belum ada'
                : $servingNumbers->map(fn ($number) => 'No. '.$number)->implode(', '),

            'waitingBefore' =>
                Booking::forDate(
                    $data['visit_date']
                )
                ->activeQueue()
                ->count(),

            'activeBarbers' => $estimator->activeBarbersAt(
                $data['visit_date'],
                $estimator->toMinutes(
                    $preview['serviceStartAt']->format('H:i')
                )
            ),

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
            'phone' => ['required', 'string', 'min:8', 'max:30', 'regex:/^[0-9+()\-\s]+$/'],
            'service_id' => [
                'required',
                'integer',
                Rule::exists('services', 'id')->where('status', 'aktif'),
            ],
            'visit_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
        ]);
        $data['customer_name'] = trim($data['customer_name']);
        $data['phone'] = trim($data['phone']);
        $service = Service::active()->findOrFail($data['service_id']);
        $booking = $creator->create($data, $service, Booking::TYPE_ONLINE, $request->user());
        return redirect()->route('booking.success', $booking->public_id)
            ->with('success', "Booking berhasil! Nomor antrean Anda {$booking->queue_number}");
    }

    public function success(Request $request, Booking $booking)
    {
        abort_unless(
            $request->user()->isAdmin() || $booking->user_id === $request->user()->id,
            403,
            'Anda tidak memiliki akses ke booking ini.'
        );

        return view('booking.success', compact('booking'));
    }
}
