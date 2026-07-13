<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\QueueEstimator;
use Illuminate\Http\Request;
use App\Services\BarberScheduler;
use Carbon\Carbon;

class QueueTrackingController extends Controller
{
    // public function index(Request $request, QueueEstimator $estimator)
    // {
    //     $phone = trim((string) $request->query('phone', ''));
    //     $query = Booking::query();
    //     if ($request->user()) {
    //         $query->where(function ($q) use ($request) {
    //             $q->where('user_id', $request->user()->id)->orWhere('phone', $request->user()->phone);
    //         });
    //         $phone = $request->user()->phone;
    //     } elseif ($phone !== '') {
    //         $query->where('phone', $phone);
    //     } else {
    //         $query->whereRaw('1 = 0');
    //     }
    //     $order = [Booking::STATUS_SERVING => 0, Booking::STATUS_WAITING => 1, Booking::STATUS_DONE => 2, Booking::STATUS_CANCELLED => 3];
    //     $bookings = $query->get()->sort(function (Booking $a, Booking $b) use ($order) {
    //         $statusOrder = ($order[$a->status] ?? 9) <=> ($order[$b->status] ?? 9);
    //         return $statusOrder !== 0 ? $statusOrder : $b->created_at <=> $a->created_at;
    //     })->values();
    //     $primary = $bookings->first(fn (Booking $b) => $b->isActive()) ?? $bookings->first();
    //     // $estimate = $primary ? $estimator->estimate($primary->visit_date->toDateString(), $primary->queue_number, $primary->service_duration) : null;

    //     $estimate = $primary
    //         ? [
    //             'currentServingNumber' => Booking::forDate(
    //                 $primary->visit_date->toDateString()
    //             )
    //             ->where('status', Booking::STATUS_SERVING)
    //             ->value('queue_number') ?? 0,

    //             'waitingBefore' => Booking::forDate(
    //                 $primary->visit_date->toDateString()
    //             )
    //             ->where('status', Booking::STATUS_WAITING)
    //             ->where('queue_number', '<', $primary->queue_number)
    //             ->count(),

    //             'waitingMinutes' => $primary->estimated_waiting_time,

    //             'serviceTime' => $primary->estimated_service_time,
    //         ]
    //         : null;

    //     return view('booking.my-queue', compact('bookings', 'primary', 'estimate', 'phone'));
    // }

    public function index(Request $request, QueueEstimator $estimator)
    {
        $phone = trim((string) $request->query('phone', ''));

        // Query dasar berdasarkan user
        $baseQuery = Booking::query();
        if ($request->user()) {
            $baseQuery->where(function ($q) use ($request) {
                $q->where('user_id', $request->user()->id)
                ->orWhere('phone', $request->user()->phone);
            });
            $phone = $request->user()->phone;
        } elseif ($phone !== '') {
            $baseQuery->where('phone', $phone);
        } else {
            $baseQuery->whereRaw('1 = 0');
        }

        /*
        |--------------------------------------------------------------------------
        | RIWAYAT BOOKING
        |--------------------------------------------------------------------------
        */
        $order = [
            Booking::STATUS_SERVING => 0,
            Booking::STATUS_WAITING => 1,
            Booking::STATUS_DONE => 2,
            Booking::STATUS_CANCELLED => 3,
        ];

        $bookings = (clone $baseQuery)
            ->orderByDesc('visit_date')
            ->orderByDesc('created_at')
            ->get()
            ->sort(function (Booking $a, Booking $b) use ($order) {
                $statusOrder = ($order[$a->status] ?? 9)
                            <=> ($order[$b->status] ?? 9);
                return $statusOrder !== 0
                    ? $statusOrder
                    : $b->created_at <=> $a->created_at;
            })
            ->values();

        /*
        |--------------------------------------------------------------------------
        | ANTREAN AKTIF HARI INI
        |--------------------------------------------------------------------------
        */
        $primary = (clone $baseQuery)
            ->whereDate('visit_date', today())
            ->whereIn('status', [
                Booking::STATUS_WAITING,
                Booking::STATUS_SERVING,
            ])
            ->orderBy('queue_number')
            ->first();

        /*
        |--------------------------------------------------------------------------
        | ESTIMASI
        |--------------------------------------------------------------------------
        */
        $estimate = $primary
            ? [
                'currentServingNumber' => Booking::forDate(
                    $primary->visit_date->toDateString()
                )
                ->where('status', Booking::STATUS_SERVING)
                ->value('queue_number') ?? 0,

                'waitingBefore' => Booking::forDate(
                    $primary->visit_date->toDateString()
                )
                ->where('status', Booking::STATUS_WAITING)
                ->where('queue_number', '<', $primary->queue_number)
                ->count(),
                'waitingMinutes' => $primary->estimated_waiting_time,
                'serviceTime' => $primary->estimated_service_time,
            ]
            : null;

        return view(
            'booking.my-queue',
            compact('bookings', 'primary', 'estimate', 'phone')
        );
    }


    public function summary(Request $request, Booking $booking)
        {
            $booking->refresh();

            return response()->json([
                'status' => $booking->status,

                'statusClass' => $this->statusClass(
                    $booking->status
                ),

                'queueNumber' => $booking->queue_number,

                'currentServingNumber' =>
                    Booking::whereDate(
                        'visit_date',
                        $booking->visit_date
                    )
                    ->where(
                        'status',
                        Booking::STATUS_SERVING
                    )
                    ->value('queue_number') ?? 0,

                'waitingBefore' =>
                    Booking::whereDate(
                        'visit_date',
                        $booking->visit_date
                    )
                    ->where(
                        'status',
                        Booking::STATUS_WAITING
                    )
                    ->where(
                        'queue_number',
                        '<',
                        $booking->queue_number
                    )
                    ->count(),

                'waitingText' =>
                    app(\App\Services\QueueEstimator::class)
                        ->formatWait(
                            (int) $booking->estimated_waiting_time
                        ),

                'serviceTime' =>
                    $booking->estimated_service_time,
            ]);
        }


    public function cancel(Request $request,Booking $booking,BarberScheduler $scheduler)
    {
        abort_unless($booking->isActive(), 422, 'Booking sudah tidak aktif.');
        $allowed = $request->user()
            ? ($request->user()->isAdmin() || $booking->user_id === $request->user()->id || $booking->phone === $request->user()->phone)
            : hash_equals($booking->phone, (string) $request->input('phone'));
        abort_unless($allowed, 403, 'Nomor HP tidak cocok.');


        $booking->update([
            'status' => Booking::STATUS_CANCELLED
        ]);

        $scheduler->rebuildSchedule(
            $booking->visit_date->toDateString()
        );

        return back()->with('success', 'Booking dibatalkan.');
    }

    private function statusClass(string $status): string
    {
        return match ($status) {
            Booking::STATUS_WAITING => 'status-warning', Booking::STATUS_SERVING => 'status-primary',
            Booking::STATUS_DONE => 'status-success', default => 'status-danger',
        };
    }
}
