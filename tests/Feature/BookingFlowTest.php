<?php

namespace Tests\Feature;

use App\Models\BarberCapacity;
use App\Models\Booking;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_two_bookings_receive_sequential_queue_numbers(): void
    {
        $service = Service::create(['name' => 'Basic Cut', 'description' => 'Test', 'duration' => 45, 'price' => 35000, 'status' => 'aktif']);
        BarberCapacity::create(['date' => now()->toDateString(), 'active_barbers' => 4, 'opening_time' => '10:00', 'closing_time' => '21:00']);
        foreach (['Andi', 'Budi'] as $i => $name) {
            $this->post(route('booking.store'), ['customer_name' => $name, 'phone' => '081'.$i, 'service_id' => $service->id, 'visit_date' => now()->toDateString()])->assertRedirect();
        }
        $this->assertSame([1, 2], Booking::orderBy('queue_number')->pluck('queue_number')->all());
    }

    public function test_cancelled_booking_is_not_counted_as_pending(): void
    {
        $this->seed();
        $booking = Booking::where('status', Booking::STATUS_WAITING)->firstOrFail();
        $this->post(route('queue.cancel', $booking->public_id), ['phone' => $booking->phone])->assertRedirect();
        $this->assertSame(Booking::STATUS_CANCELLED, $booking->fresh()->status);
    }
}
