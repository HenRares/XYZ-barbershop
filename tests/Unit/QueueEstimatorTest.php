<?php

namespace Tests\Unit;

use App\Models\BarberCapacity;
use App\Services\QueueEstimator;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QueueEstimatorTest extends TestCase
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

    public function test_break_period_reduces_active_barbers_by_one(): void
    {
        $date = now()->toDateString();
        BarberCapacity::create([
            'date' => $date,
            'active_barbers' => 4,
            'opening_time' => '10:00',
            'closing_time' => '21:00',
        ]);

        $estimator = app(QueueEstimator::class);

        $this->assertSame(4, $estimator->activeBarbersAt($date, 11 * 60));
        $this->assertSame(3, $estimator->activeBarbersAt($date, 12 * 60 + 30));
        $this->assertSame(3, $estimator->activeBarbersAt($date, 19 * 60));
        $this->assertSame(4, $estimator->activeBarbersAt($date, 20 * 60));
    }

    public function test_default_capacity_is_consistently_four_barbers(): void
    {
        $this->assertSame(
            QueueEstimator::DEFAULT_BARBERS,
            app(QueueEstimator::class)->activeBarbersAt(now()->addDay()->toDateString(), 10 * 60)
        );
    }
}
