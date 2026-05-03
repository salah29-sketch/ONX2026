<?php

namespace Tests\Unit\Services;

use App\Models\Service\Category;
use App\Models\Service\Service;
use App\Services\TimeCostCalculator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TimeCostCalculatorTest extends TestCase
{
    use RefreshDatabase;

    private TimeCostCalculator $calculator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->calculator = app(TimeCostCalculator::class);
    }

    private function makeService(array $overrides = []): Service
    {
        static $i = 0;
        $i++;

        $category = Category::firstOrCreate(
            ['slug' => 'test-time'],
            ['name' => 'Test', 'is_active' => true]
        );

        return Service::create(array_merge([
            'category_id'      => $category->id,
            'name'             => "Service {$i}",
            'slug'             => "service-time-{$i}",
            'booking_type'     => 'event',
            'pricing_mode'     => 'package',
            'time_mode'        => 'standard',
            'free_hours'       => 0,
            'extra_hour_price' => 2000,
            'is_active'        => true,
        ], $overrides));
    }

    // ── Standard mode ────────────────────────────────────────────────

    /** @test */
    public function standard_mode_charges_for_extra_hours(): void
    {
        $service = $this->makeService([
            'time_mode'        => 'standard',
            'free_hours'       => 2,
            'extra_hour_price' => 2000,
        ]);

        // 4 hours total, 2 free → 2 extra hours → 4000 DA
        $cost = $this->calculator->calculate($service, '10:00', '14:00');

        $this->assertEquals(4000.0, $cost);
    }

    /** @test */
    public function standard_mode_no_charge_within_free_hours(): void
    {
        $service = $this->makeService([
            'time_mode'  => 'standard',
            'free_hours' => 4,
        ]);

        // 3 hours — within 4 free hours
        $cost = $this->calculator->calculate($service, '10:00', '13:00');

        $this->assertEquals(0.0, $cost);
    }

    /** @test */
    public function standard_mode_handles_midnight_crossing(): void
    {
        $service = $this->makeService([
            'time_mode'        => 'standard',
            'free_hours'       => 0,
            'extra_hour_price' => 1000,
        ]);

        // 23:00 → 01:00 = 2 hours
        $cost = $this->calculator->calculate($service, '23:00', '01:00');

        $this->assertEquals(2000.0, $cost);
    }

    /** @test */
    public function standard_mode_rounds_up_partial_hours(): void
    {
        $service = $this->makeService([
            'time_mode'        => 'standard',
            'free_hours'       => 2,
            'extra_hour_price' => 2000,
        ]);

        // 2h30m total, 2 free → 30 min extra → rounds up to 1 hour → 2000 DA
        $cost = $this->calculator->calculate($service, '10:00', '12:30');

        $this->assertEquals(2000.0, $cost);
    }

    // ── Wedding mode ─────────────────────────────────────────────────

    /** @test */
    public function wedding_mode_no_charge_within_default_window(): void
    {
        $service = $this->makeService([
            'time_mode'           => 'wedding',
            'default_start_time'  => '19:00:00',
            'default_end_time'    => '04:00:00',
            'early_start_price'   => 5000,
            'late_end_price'      => 3000,
        ]);

        // Exactly within window
        $cost = $this->calculator->calculate($service, '19:00', '04:00');

        $this->assertEquals(0.0, $cost);
    }

    /** @test */
    public function wedding_mode_charges_for_early_start(): void
    {
        $service = $this->makeService([
            'time_mode'          => 'wedding',
            'default_start_time' => '19:00:00',
            'default_end_time'   => '04:00:00',
            'early_start_price'  => 5000,
            'late_end_price'     => 3000,
        ]);

        // Start 2 hours early (17:00 instead of 19:00) → 2 * 5000 = 10000
        $cost = $this->calculator->calculate($service, '17:00', '04:00');

        $this->assertEquals(10000.0, $cost);
    }

    /** @test */
    public function wedding_mode_charges_for_late_end(): void
    {
        $service = $this->makeService([
            'time_mode'          => 'wedding',
            'default_start_time' => '19:00:00',
            'default_end_time'   => '04:00:00',
            'early_start_price'  => 5000,
            'late_end_price'     => 3000,
        ]);

        // End 1 hour late (05:00 instead of 04:00) → 2 blocks of 30min → 2 * 3000 = 6000
        $cost = $this->calculator->calculate($service, '19:00', '05:00');

        $this->assertEquals(6000.0, $cost);
    }

    /** @test */
    public function wedding_mode_charges_both_early_and_late(): void
    {
        $service = $this->makeService([
            'time_mode'          => 'wedding',
            'default_start_time' => '19:00:00',
            'default_end_time'   => '04:00:00',
            'early_start_price'  => 5000,
            'late_end_price'     => 3000,
        ]);

        // 1 hour early + 1 hour late = 5000 + 6000 = 11000
        $cost = $this->calculator->calculate($service, '18:00', '05:00');

        $this->assertEquals(11000.0, $cost);
    }
}
