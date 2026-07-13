<?php

namespace App\Services;

use App\Models\BarberCapacity;
use App\Models\Booking;
use Illuminate\Support\Collection;

class QueueEstimator
{
    public const DEFAULT_OPEN = '10:00';
    public const DEFAULT_CLOSE = '21:00';

    public function activeBarbersAt(string $date, int $minuteOfDay): int
    {
        $base = (int) (BarberCapacity::whereDate('date', $date)->value('active_barbers') ?? 4);
        if ($minuteOfDay >= $this->toMinutes('12:00') && $minuteOfDay < $this->toMinutes('14:00')) return max(1, $base - 1);
        if ($minuteOfDay >= $this->toMinutes('18:00') && $minuteOfDay < $this->toMinutes('20:00')) return max(1, $base - 1);
        return $base;
    }

    /** @return Collection<int, Booking> */
    public function dayBookings(string $date): Collection
    {
        return Booking::forDate($date)->orderBy('queue_number')->get();
    }

    public function currentServing(string $date): ?Booking
    {
        return Booking::forDate($date)->where('status', Booking::STATUS_SERVING)->orderBy('queue_number')->first();
    }

    public function lastCompletedNumber(string $date): int
    {
        return (int) (Booking::forDate($date)->where('status', Booking::STATUS_DONE)->max('queue_number') ?? 0);
    }

    /**
     * Port 1:1 dari src/lib/queue.ts.
     * Setiap barber direpresentasikan sebagai slot berisi menit saat ia kembali kosong.
     * Antrean sebelum target dimasukkan ke slot yang paling cepat kosong.
     *
     * @param Collection<int, Booking>|null $bookings Optional in-transaction snapshot.
     * @return array{waitingMinutes:int,serviceTime:string,currentServingNumber:int,waitingBefore:int,activeBarbers:int}
     */


    // public function estimate(string $date, int $targetQueueNumber, int $targetDuration, ?Collection $bookings = null): array
    // {
    //     $list = ($bookings ?? $this->dayBookings($date))->sortBy('queue_number')->values();
    //     $now = now();
    //     $isToday = $date === $now->toDateString();
    //     $startMinute = $isToday
    //         ? max($this->toMinutes(self::DEFAULT_OPEN), ((int) $now->format('H') * 60) + (int) $now->format('i'))
    //         : $this->toMinutes(self::DEFAULT_OPEN);

    //     $pending = $list->filter(fn (Booking $booking) =>
    //         $booking->queue_number < $targetQueueNumber
    //         && in_array($booking->status, [Booking::STATUS_WAITING, Booking::STATUS_SERVING], true)
    //     )->values();

    //     $barberCount = $this->activeBarbersAt($date, $startMinute);
    //     $slots = array_fill(0, $barberCount, $startMinute);
    //     $assign = function (int $duration) use (&$slots): int {
    //         sort($slots, SORT_NUMERIC);
    //         $start = $slots[0];
    //         if ($start >= $this->toMinutes(self::DEFAULT_CLOSE)) $start = $this->toMinutes(self::DEFAULT_CLOSE);
    //         $slots[0] = $start + $duration;
    //         return $start;
    //     };

    //     foreach ($pending as $booking) $assign((int) $booking->service_duration);
    //     $serviceStart = $assign($targetDuration);
    //     $waitingMinutes = max(0, $serviceStart - $startMinute);

    //     $current = $list->firstWhere('status', Booking::STATUS_SERVING);
    //     $currentNumber = $current?->queue_number
    //         ?? (int) ($list->where('status', Booking::STATUS_DONE)->max('queue_number') ?? 0);

    //     return [
    //         'waitingMinutes' => $waitingMinutes,
    //         'serviceTime' => $this->toHHMM(min($serviceStart, $this->toMinutes(self::DEFAULT_CLOSE))),
    //         'currentServingNumber' => (int) $currentNumber,
    //         'waitingBefore' => $pending->where('status', Booking::STATUS_WAITING)->count(),
    //         'activeBarbers' => $barberCount,
    //     ];
    // }
    public function estimate(
        string $date,
        int $targetQueueNumber,
        int $targetDuration,
        ?Collection $bookings = null
    ): array {
        $list = ($bookings ?? $this->dayBookings($date))
            ->whereIn('status', [
                Booking::STATUS_WAITING,
                Booking::STATUS_SERVING,
            ])
            ->sortBy('queue_number')
            ->values();
        $now = now();
        $isToday = $date === $now->toDateString();
        $startMinute = $isToday
            ? max(
                $this->toMinutes(self::DEFAULT_OPEN),
                ((int)$now->format('H') * 60) + (int)$now->format('i')
            )
            : $this->toMinutes(self::DEFAULT_OPEN);
        $barberCount = max(
            1,
            $this->activeBarbersAt($date, $startMinute)
        );

        /*
     * Virtual barber slots
     */
        $slots = array_fill(
            0,
            $barberCount,
            $startMinute
        );

        /*
     * Simulasikan semua antrean aktif
     */
        foreach ($list as $booking) {
            sort($slots, SORT_NUMERIC);
            $serviceStart = $slots[0];
            $duration = (int) $booking->service_duration;
            $slots[0] = $serviceStart + $duration;
        }

        /*
     * Booking BARU (targetQueueNumber)
     * masuk ke barber yang paling cepat tersedia
     */
        sort($slots, SORT_NUMERIC);
        $targetStart = $slots[0];
        $waitingMinutes = max(
            0,
            $targetStart - $startMinute
        );
        $current = $list->firstWhere(
            'status',
            Booking::STATUS_SERVING
        );
        $currentNumber = $current?->queue_number
            ?? (int) (
                $this->lastCompletedNumber($date)
            );
        $waitingBefore = $list->count();

        return [
            'waitingMinutes' => $waitingMinutes,
            'serviceTime' => $this->toHHMM(
                min(
                    $targetStart,
                    $this->toMinutes(self::DEFAULT_CLOSE)
                )
            ),
            'currentServingNumber' => (int) $currentNumber,
            'waitingBefore' => $waitingBefore,
            'activeBarbers' => $barberCount,
        ];
    }


    public function formatWait(int $minutes): string
    {
        if ($minutes <= 0) return 'Segera dilayani';
        $hours = intdiv($minutes, 60);
        $remainder = $minutes % 60;
        if ($hours === 0) return "± {$remainder} menit";
        if ($remainder === 0) return "± {$hours} jam";
        return "± {$hours} jam {$remainder} menit";
    }

    public function recalculateDay(string $date): void
    {
        $bookings = $this->dayBookings($date);
        foreach ($bookings as $booking) {
            if (! $booking->isActive()) continue;
            $estimate = $this->estimate($date, $booking->queue_number, $booking->service_duration, $bookings);
            $booking->update([
                'estimated_waiting_time' => $estimate['waitingMinutes'],
                'estimated_service_time' => $estimate['serviceTime'],
            ]);
        }
    }

    private function toMinutes(string $hhmm): int
    {
        [$hour, $minute] = array_map('intval', explode(':', substr($hhmm, 0, 5)));
        return ($hour * 60) + $minute;
    }

    private function toHHMM(int $minutes): string
    {
        return sprintf('%02d:%02d', intdiv($minutes, 60), $minutes % 60);
    }
}
