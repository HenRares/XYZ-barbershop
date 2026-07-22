<?php

namespace Tests\Feature;

use App\Models\BarberCapacity;
use App\Models\Booking;
use App\Models\QueueCounter;
use App\Models\Service;
use App\Models\User;
use App\Services\BookingCreator;
use App\Services\BookingStatusReconciler;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class BookingFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Carbon::setTestNow('2026-07-20 10:00:00');
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    public function test_two_authenticated_bookings_receive_sequential_queue_numbers(): void
    {
        $user = $this->makeUser();
        $service = $this->makeService();
        $this->makeCapacity(4);

        foreach (['Andi', 'Budi'] as $name) {
            $this->actingAs($user)
                ->post(route('booking.store'), [
                    'customer_name' => $name,
                    'phone' => $user->phone,
                    'service_id' => $service->id,
                    'visit_date' => now()->toDateString(),
                ])
                ->assertRedirect();
        }

        $this->assertSame(
            [1, 2],
            Booking::orderBy('queue_number')->pluck('queue_number')->all()
        );
    }

    public function test_admin_cannot_start_a_fifth_customer_when_four_barbers_are_busy(): void
    {
        $customer = $this->makeUser();
        $admin = $this->makeUser('admin@example.test', '089999999999', 'admin');
        $service = $this->makeService();
        $this->makeCapacity(4);

        $bookings = collect(range(1, 5))->map(
            fn (int $number) => $this->makeBooking($customer, $service, "Customer {$number}")
        );

        foreach ($bookings->take(4) as $booking) {
            $this->actingAs($admin)
                ->patch(route('admin.queues.status', $booking), [
                    'status' => Booking::STATUS_SERVING,
                ])
                ->assertSessionHasNoErrors();
        }

        $this->actingAs($admin)
            ->from(route('admin.queues'))
            ->patch(route('admin.queues.status', $bookings->last()), [
                'status' => Booking::STATUS_SERVING,
            ])
            ->assertRedirect(route('admin.queues'))
            ->assertSessionHasErrors('status');

        $this->assertSame(
            4,
            Booking::where('status', Booking::STATUS_SERVING)->count()
        );
        $this->assertSame(
            Booking::STATUS_WAITING,
            $bookings->last()->fresh()->status
        );

        $this->actingAs($customer)
            ->getJson(route('queue.summary', $bookings->first()->public_id))
            ->assertOk()
            ->assertJsonPath('currentServingNumbers', [1, 2, 3, 4]);
    }

    public function test_queue_page_uses_the_booking_requested_after_success(): void
    {
        $user = $this->makeUser();
        $service = $this->makeService();
        $this->makeCapacity(4);
        $first = $this->makeBooking($user, $service, 'Booking Lama');
        $second = $this->makeBooking($user, $service, 'Booking Baru');

        $response = $this->actingAs($user)->get(route('queue.mine', [
            'booking' => $second->public_id,
        ]));

        $response->assertOk();
        $this->assertSame($second->id, $response->viewData('primary')->id);
        $this->assertNotSame($first->id, $response->viewData('primary')->id);
    }

    public function test_new_queue_number_recovers_from_a_stale_daily_counter(): void
    {
        $user = $this->makeUser();
        $service = $this->makeService();
        $this->makeCapacity(4);

        QueueCounter::create([
            'date' => now()->toDateString(),
            'last_number' => 2,
        ]);

        Booking::create([
            'public_id' => (string) \Illuminate\Support\Str::ulid(),
            'booking_code' => 'XYZ-LEGACY',
            'queue_number' => 5,
            'customer_name' => 'Legacy Customer',
            'phone' => $user->phone,
            'service_id' => $service->id,
            'service_name' => $service->name,
            'service_duration' => $service->duration,
            'visit_date' => now()->toDateString(),
            'estimated_waiting_time' => 0,
            'estimated_service_time' => '10:00',
            'queue_type' => Booking::TYPE_ONLINE,
            'status' => Booking::STATUS_DONE,
            'user_id' => $user->id,
        ]);

        $booking = $this->makeBooking($user, $service, 'Booking Baru');

        $this->assertSame(6, $booking->queue_number);
        $this->assertSame(6, QueueCounter::first()->last_number);
    }

    public function test_schedule_does_not_use_a_barber_slot_across_a_reduced_capacity_break(): void
    {
        Carbon::setTestNow('2026-07-20 11:30:00');
        $user = $this->makeUser();
        $service = $this->makeService(60);
        $this->makeCapacity(4);

        $this->makeBooking($user, $service, 'Customer 1');
        $this->makeBooking($user, $service, 'Customer 2');
        $this->makeBooking($user, $service, 'Customer 3');
        $fourth = $this->makeBooking($user, $service, 'Customer 4');

        $this->assertSame(
            '2026-07-20 12:30',
            $fourth->barberLog->service_start_at->format('Y-m-d H:i')
        );
        $this->assertLessThanOrEqual(3, $fourth->barberLog->barber_slot);
    }

    public function test_customer_cannot_view_another_customers_booking_summary_or_success_page(): void
    {
        $owner = $this->makeUser();
        $other = $this->makeUser('other@example.test', '087777777777');
        $service = $this->makeService();
        $this->makeCapacity(4);
        $booking = $this->makeBooking($owner, $service, 'Pemilik');

        $this->actingAs($other)
            ->get(route('booking.success', $booking->public_id))
            ->assertForbidden();

        $this->actingAs($other)
            ->getJson(route('queue.summary', $booking->public_id))
            ->assertForbidden();
    }

    public function test_future_booking_is_scheduled_on_the_visit_date_at_opening_time(): void
    {
        $user = $this->makeUser();
        $service = $this->makeService();
        $tomorrow = now()->addDay()->toDateString();

        $booking = app(BookingCreator::class)->create([
            'customer_name' => 'Future Customer',
            'phone' => $user->phone,
            'service_id' => $service->id,
            'visit_date' => $tomorrow,
        ], $service, Booking::TYPE_ONLINE, $user);

        $this->assertSame(
            "{$tomorrow} 10:00",
            $booking->barberLog->service_start_at->format('Y-m-d H:i')
        );
    }

    public function test_reconciler_finishes_a_serving_booking_after_its_service_end(): void
    {
        $user = $this->makeUser();
        $service = $this->makeService();
        $this->makeCapacity(4);
        $booking = $this->makeBooking($user, $service, 'Customer');
        $admin = $this->makeUser('admin@example.test', '089999999999', 'admin');

        $this->actingAs($admin)->patch(route('admin.queues.status', $booking), [
            'status' => Booking::STATUS_SERVING,
        ]);

        Carbon::setTestNow('2026-07-20 10:46:00');
        app(BookingStatusReconciler::class)->reconcileDate(now()->toDateString());

        $this->assertSame(Booking::STATUS_DONE, $booking->fresh()->status);
        $this->assertSame('done', $booking->barberLog->fresh()->status);
    }

    public function test_daily_report_does_not_include_future_bookings(): void
    {
        $user = $this->makeUser();
        $admin = $this->makeUser('admin@example.test', '089999999999', 'admin');
        $service = $this->makeService();
        $this->makeCapacity(4);
        $this->makeBooking($user, $service, 'Hari Ini');

        app(BookingCreator::class)->create([
            'customer_name' => 'Besok',
            'phone' => $user->phone,
            'service_id' => $service->id,
            'visit_date' => now()->addDay()->toDateString(),
        ], $service, Booking::TYPE_ONLINE, $user);

        $response = $this->actingAs($admin)->get(route('admin.reports', [
            'period' => 'Harian',
        ]));

        $response->assertOk();
        $this->assertSame(1, $response->viewData('stats')['total']);
    }

    public function test_authenticated_customer_can_cancel_only_their_own_booking(): void
    {
        $owner = $this->makeUser();
        $other = $this->makeUser('other@example.test', '087777777777');
        $service = $this->makeService();
        $this->makeCapacity(4);
        $booking = $this->makeBooking($owner, $service, 'Pemilik');

        $this->actingAs($other)
            ->post(route('queue.cancel', $booking->public_id))
            ->assertForbidden();

        $this->actingAs($owner)
            ->post(route('queue.cancel', $booking->public_id))
            ->assertRedirect();

        $this->assertSame(Booking::STATUS_CANCELLED, $booking->fresh()->status);
    }

    public function test_registration_rejects_a_phone_number_used_by_another_account(): void
    {
        $this->makeUser();

        $this->from(route('register'))
            ->post(route('register.store'), [
                'name' => 'Customer Lain',
                'phone' => '081234567890',
                'email' => 'new@example.test',
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ])
            ->assertRedirect(route('register'))
            ->assertSessionHasErrors('phone');
    }

    private function makeBooking(User $user, Service $service, string $name): Booking
    {
        return app(BookingCreator::class)->create([
            'customer_name' => $name,
            'phone' => $user->phone,
            'service_id' => $service->id,
            'visit_date' => now()->toDateString(),
        ], $service, Booking::TYPE_ONLINE, $user);
    }

    private function makeUser(
        string $email = 'customer@example.test',
        string $phone = '081234567890',
        string $role = 'pelanggan'
    ): User {
        return User::create([
            'name' => ucfirst($role),
            'email' => $email,
            'phone' => $phone,
            'password' => Hash::make('password123'),
            'role' => $role,
        ]);
    }

    private function makeService(int $duration = 45): Service
    {
        return Service::create([
            'name' => 'Basic Cut',
            'description' => 'Test',
            'duration' => $duration,
            'price' => 35000,
            'status' => 'aktif',
        ]);
    }

    private function makeCapacity(int $barbers): BarberCapacity
    {
        return BarberCapacity::create([
            'date' => now()->toDateString(),
            'active_barbers' => $barbers,
            'opening_time' => '10:00',
            'closing_time' => '21:00',
        ]);
    }
}
