<?php

namespace Tests\Unit\Services;

use App\DTOs\PricingResult;
use App\Models\Service\Category;
use App\Models\Service\Package;
use App\Models\Service\Service;
use App\Services\PricingCalculator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PricingCalculatorTest extends TestCase
{
    use RefreshDatabase;

    private PricingCalculator $calculator;
    private Service $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->calculator = app(PricingCalculator::class);

        $category = Category::create([
            'name'      => 'Test',
            'slug'      => 'test',
            'is_active' => true,
        ]);

        $this->service = Service::create([
            'category_id'    => $category->id,
            'name'           => 'Photography',
            'slug'           => 'photography',
            'booking_type'   => 'event',
            'pricing_mode'   => 'package',
            'base_price'     => 30000,
            'deposit_amount' => 5000,
            'free_hours'     => 0,
            'extra_hour_price' => 2000,
            'time_mode'      => 'standard',
            'is_active'      => true,
        ]);
    }

    // ── Event pricing ────────────────────────────────────────────────

    /** @test */
    public function it_uses_base_price_when_no_package(): void
    {
        $result = $this->calculator->calculateEvent(
            $this->service, null, [], null, null, null, null
        );

        $this->assertInstanceOf(PricingResult::class, $result);
        $this->assertEquals(30000.0, $result->base);
        $this->assertEquals(30000.0, $result->total);
    }

    /** @test */
    public function it_uses_package_price_over_base_price(): void
    {
        $package = Package::create([
            'service_id' => $this->service->id,
            'name'       => 'Premium',
            'price'      => 80000,
            'is_active'  => true,
        ]);

        $result = $this->calculator->calculateEvent(
            $this->service, $package, [], null, null, null, null
        );

        $this->assertEquals(80000.0, $result->base);
        $this->assertEquals(80000.0, $result->total);
    }

    /** @test */
    public function it_returns_zero_options_cost_when_no_options(): void
    {
        $result = $this->calculator->calculateEvent(
            $this->service, null, [], null, null, null, null
        );

        $this->assertEquals(0.0, $result->optionsCost);
    }

    /** @test */
    public function it_calculates_correct_subtotal(): void
    {
        $package = Package::create([
            'service_id' => $this->service->id,
            'name'       => 'Basic',
            'price'      => 50000,
            'is_active'  => true,
        ]);

        $result = $this->calculator->calculateEvent(
            $this->service, $package, [], null, null, null, null
        );

        $this->assertEquals($result->base + $result->optionsCost, $result->subtotal);
    }

    /** @test */
    public function it_includes_deposit_from_service(): void
    {
        $result = $this->calculator->calculateEvent(
            $this->service, null, [], null, null, null, null
        );

        $this->assertEquals(5000.0, $result->deposit);
    }

    /** @test */
    public function total_equals_subtotal_plus_time_and_travel_costs(): void
    {
        $result = $this->calculator->calculateEvent(
            $this->service, null, [], null, null, null, null
        );

        $expected = $result->subtotal + $result->timeCost + $result->travelCost;
        $this->assertEquals($expected, $result->total);
    }

    // ── Appointment pricing ──────────────────────────────────────────

    /** @test */
    public function appointment_uses_package_price(): void
    {
        $package = Package::create([
            'service_id' => $this->service->id,
            'name'       => 'Session',
            'price'      => 15000,
            'is_active'  => true,
        ]);

        $result = $this->calculator->calculateAppointment($this->service, $package);

        $this->assertEquals(15000.0, $result->total);
        $this->assertEquals(0.0, $result->timeCost);
        $this->assertEquals(0.0, $result->travelCost);
    }

    /** @test */
    public function appointment_uses_service_base_price_when_no_package(): void
    {
        $result = $this->calculator->calculateAppointment($this->service, null);

        $this->assertEquals(30000.0, $result->total);
    }

    // ── Subscription pricing ─────────────────────────────────────────

    /** @test */
    public function subscription_uses_package_price(): void
    {
        $package = Package::create([
            'service_id' => $this->service->id,
            'name'       => 'Monthly',
            'price'      => 12000,
            'is_active'  => true,
        ]);

        $result = $this->calculator->calculateSubscription($package);

        $this->assertEquals(12000.0, $result->total);
        $this->assertEquals(0.0, $result->deposit);
    }

    /** @test */
    public function subscription_returns_zero_when_no_package(): void
    {
        $result = $this->calculator->calculateSubscription(null);

        $this->assertEquals(0.0, $result->total);
    }

    // ── PricingResult DTO ────────────────────────────────────────────

    /** @test */
    public function pricing_result_to_array_has_all_keys(): void
    {
        $result = $this->calculator->calculateEvent(
            $this->service, null, [], null, null, null, null
        );

        $array = $result->toArray();

        $this->assertArrayHasKey('base', $array);
        $this->assertArrayHasKey('options_cost', $array);
        $this->assertArrayHasKey('time_cost', $array);
        $this->assertArrayHasKey('travel_cost', $array);
        $this->assertArrayHasKey('subtotal', $array);
        $this->assertArrayHasKey('total', $array);
        $this->assertArrayHasKey('deposit', $array);
    }
}
