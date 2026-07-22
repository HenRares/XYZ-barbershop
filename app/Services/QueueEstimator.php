<?php

namespace App\Services;

use App\Models\BarberCapacity;
use App\Models\Booking;
use Illuminate\Support\Collection;

class QueueEstimator
{
    public const DEFAULT_BARBERS = 4;
    public const DEFAULT_OPEN = '10:00';
    public const DEFAULT_CLOSE = '21:00';

    /** @return array{active_barbers:int,opening_time:string,closing_time:string} */
    public function settingsForDate(string $date): array
    {
        $capacity = BarberCapacity::whereDate('date', $date)->first();

        return [
            'active_barbers' => (int) ($capacity?->active_barbers ?? self::DEFAULT_BARBERS),
            'opening_time' => substr((string) ($capacity?->opening_time ?? self::DEFAULT_OPEN), 0, 5),
            'closing_time' => substr((string) ($capacity?->closing_time ?? self::DEFAULT_CLOSE), 0, 5),
        ];
    }

    public function activeBarbersAt(string $date, int $minuteOfDay): int
    {
        $settings = $this->settingsForDate($date);

        return $this->effectiveBarbersForBase(
            $settings['active_barbers'],
            $minuteOfDay
        );
    }

    public function effectiveBarbersForBase(int $base, int $minuteOfDay): int
    {
        if (
            ($minuteOfDay >= $this->toMinutes('12:00') && $minuteOfDay < $this->toMinutes('14:00'))
            || ($minuteOfDay >= $this->toMinutes('18:00') && $minuteOfDay < $this->toMinutes('20:00'))
        ) {
            return max(1, $base - 1);
        }

        return max(1, $base);
    }

    /** @return Collection<int, Booking> */
    public function dayBookings(string $date): Collection
    {
        return Booking::forDate($date)->orderBy('queue_number')->get();
    }

    public function currentServing(string $date): ?Booking
    {
        return Booking::forDate($date)
            ->where('status', Booking::STATUS_SERVING)
            ->orderBy('queue_number')
            ->first();
    }

    public function lastCompletedNumber(string $date): int
    {
        return (int) (Booking::forDate($date)
            ->where('status', Booking::STATUS_DONE)
            ->max('queue_number') ?? 0);
    }

    /**
     * @param Collection<int, Booking>|null $bookings
     * @return array{waitingMinutes:int,serviceTime:string,currentServingNumber:int,waitingBefore:int,activeBarbers:int}
     */
    public function estimate(
        string $date,
        int $targetQueueNumber,
        int $targetDuration,
        ?Collection $bookings = null
    ): array {
        $list = ($bookings ?? $this->dayBookings($date))
            ->filter(fn (Booking $booking) =>
                $booking->queue_number < $targetQueueNumber
                && $booking->isActive()
            )
            ->sortBy('queue_number')
            ->values();

        $settings = $this->settingsForDate($date);
        $now = now();
        $isToday = $date === $now->toDateString();
        $startMinute = $isToday
            ? max($this->toMinutes($settings['opening_time']), $this->minuteOfDay($now))
            : $this->toMinutes($settings['opening_time']);

        $barberCount = $this->activeBarbersAt($date, $startMinute);
        $slots = array_fill(0, $barberCount, $startMinute);

        foreach ($list as $booking) {
            sort($slots, SORT_NUMERIC);

            $duration = (int) $booking->service_duration;
            if ($booking->status === Booking::STATUS_SERVING && $booking->barberLog) {
                $duration = max(
                    0,
                    (int) now()->diffInMinutes($booking->barberLog->service_end_at, false)
                );
            }

            $slots[0] += $duration;
        }

        sort($slots, SORT_NUMERIC);
        $targetStart = $slots[0];
        $closingMinute = $this->toMinutes($settings['closing_time']);

        // Gunakan durasi target untuk mendeteksi slot yang melewati jam tutup.
        if (($targetStart + $targetDuration) > $closingMinute) {
            $targetStart = $closingMinute;
        }

        $current = $list->firstWhere('status', Booking::STATUS_SERVING);

        return [
            'waitingMinutes' => max(0, $targetStart - $startMinute),
            'serviceTime' => $this->toHHMM($targetStart),
            'currentServingNumber' => (int) ($current?->queue_number ?? $this->lastCompletedNumber($date)),
            'waitingBefore' => $list->where('status', Booking::STATUS_WAITING)->count(),
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

            $estimate = $this->estimate(
                $date,
                $booking->queue_number,
                $booking->service_duration,
                $bookings
            );

            $booking->update([
                'estimated_waiting_time' => $estimate['waitingMinutes'],
                'estimated_service_time' => $estimate['serviceTime'],
            ]);
        }
    }

    public function minuteOfDay(?\DateTimeInterface $time = null): int
    {
        $time ??= now();

        return ((int) $time->format('H') * 60) + (int) $time->format('i');
    }

    public function toMinutes(string $hhmm): int
    {
        [$hour, $minute] = array_map('intval', explode(':', substr($hhmm, 0, 5)));

        return ($hour * 60) + $minute;
    }

    private function toHHMM(int $minutes): string
    {
        return sprintf('%02d:%02d', intdiv($minutes, 60), $minutes % 60);
    }
}
