<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\QueueCounter;
use App\Services\BarberScheduler;
use App\Services\BookingStatusReconciler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QueueTrackingController extends Controller
{
    public function index(Request $request, BookingStatusReconciler $reconciler)
    {
        $reconciler->reconcileDate(now()->toDateString());

        // Booking pelanggan harus ditentukan dari user_id, bukan nomor HP.
        $baseQuery = Booking::query()
            ->where('user_id', $request->user()->id);

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
                $statusOrder = ($order[$a->status] ?? 9) <=> ($order[$b->status] ?? 9);

                return $statusOrder !== 0
                    ? $statusOrder
                    : $b->created_at <=> $a->created_at;
            })
            ->values();

        $activeQuery = (clone $baseQuery)
            ->whereDate('visit_date', '>=', today())
            ->whereIn('status', [Booking::STATUS_WAITING, Booking::STATUS_SERVING]);

        $primary = null;
        $requestedPublicId = trim((string) $request->query('booking', ''));

        if ($requestedPublicId !== '') {
            $primary = (clone $activeQuery)
                ->where('public_id', $requestedPublicId)
                ->first();
        }

        $primary ??= (clone $activeQuery)
            ->orderByRaw(
                'CASE WHEN status = ? THEN 0 ELSE 1 END',
                [Booking::STATUS_SERVING]
            )
            ->orderBy('visit_date')
            ->orderByDesc('created_at')
            ->first();

        $estimate = null;

        if ($primary) {
            $servingNumbers = $this->currentServingNumbers(
                $primary->visit_date->toDateString()
            );

            $estimate = [
                'currentServingNumbers' => $servingNumbers,
                'currentServingLabel' => $this->formatServingNumbers($servingNumbers),
                'waitingBefore' => Booking::forDate($primary->visit_date->toDateString())
                    ->where('status', Booking::STATUS_WAITING)
                    ->where('queue_number', '<', $primary->queue_number)
                    ->count(),
                'waitingMinutes' => $primary->getCurrentWaitingMinutes(),
                'serviceTime' => $primary->estimated_service_time,
            ];
        }

        return view('booking.my-queue', compact('bookings', 'primary', 'estimate'));
    }

    public function summary(
        Request $request,
        Booking $booking,
        BookingStatusReconciler $reconciler
    ) {
        $this->authorizeBooking($request, $booking);
        $reconciler->reconcileDate($booking->visit_date->toDateString());
        $booking->refresh();

        $servingNumbers = $this->currentServingNumbers(
            $booking->visit_date->toDateString()
        );

        return response()->json([
            'status' => $booking->status,
            'statusClass' => $this->statusClass($booking->status),
            'queueNumber' => $booking->queue_number,
            'currentServingNumbers' => $servingNumbers,
            'currentServingLabel' => $this->formatServingNumbers($servingNumbers),
            'waitingBefore' => Booking::forDate($booking->visit_date->toDateString())
                ->where('status', Booking::STATUS_WAITING)
                ->where('queue_number', '<', $booking->queue_number)
                ->count(),
            'waitingText' => app(\App\Services\QueueEstimator::class)
                ->formatWait($booking->getCurrentWaitingMinutes()),
            'serviceTime' => $booking->estimated_service_time,
            'isActive' => $booking->isActive(),
        ]);
    }

    public function cancel(Request $request, Booking $booking, BarberScheduler $scheduler)
    {
        $this->authorizeBooking($request, $booking);

        DB::transaction(function () use ($booking, $scheduler) {
            $date = $booking->visit_date->toDateString();
            DB::table('queue_counters')->insertOrIgnore([
                'date' => $date,
                'last_number' => Booking::forDate($date)->max('queue_number') ?? 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            QueueCounter::where('date', $date)->lockForUpdate()->firstOrFail();
            $booking = Booking::whereKey($booking->id)->lockForUpdate()->firstOrFail();
            abort_unless($booking->isActive(), 422, 'Booking sudah tidak aktif.');

            $booking->update(['status' => Booking::STATUS_CANCELLED]);
            $scheduler->removeSchedule($booking);
            $scheduler->rebuildSchedule($date);
        }, attempts: 5);

        return back()->with('success', 'Booking dibatalkan.');
    }

    private function authorizeBooking(Request $request, Booking $booking): void
    {
        abort_unless(
            $request->user()->isAdmin() || $booking->user_id === $request->user()->id,
            403,
            'Anda tidak memiliki akses ke booking ini.'
        );
    }

    /** @return array<int, int> */
    private function currentServingNumbers(string $date): array
    {
        return Booking::forDate($date)
            ->where('status', Booking::STATUS_SERVING)
            ->orderBy('queue_number')
            ->pluck('queue_number')
            ->map(fn ($number) => (int) $number)
            ->all();
    }

    /** @param array<int, int> $numbers */
    private function formatServingNumbers(array $numbers): string
    {
        if ($numbers === []) return 'Belum ada';

        return collect($numbers)
            ->map(fn (int $number) => "No. {$number}")
            ->implode(', ');
    }

    private function statusClass(string $status): string
    {
        return match ($status) {
            Booking::STATUS_WAITING => 'status-warning',
            Booking::STATUS_SERVING => 'status-primary',
            Booking::STATUS_DONE => 'status-success',
            default => 'status-danger',
        };
    }
}
