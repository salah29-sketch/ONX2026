<?php

namespace Tests\Feature\Booking;

use App\DTOs\PricingResult;
use App\Models\Client\Client;
use App\Models\Service\Category;
use App\Models\Service\Package;
use App\Models\Service\Service;
use App\Services\BookingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventBookingTest extends TestCase
{
    use RefreshDatabase;

    private Service $service;
    private Package $package;

    protected function setUp(): void
    {
        parent::setUp();

        $category = Category::create([
            'name'      => 'أحداث',
            'slug'      => 'events',
            'type'      => 'events',
            'is_active' => true,
        ]);

        $this->service = Service::create([
            'category_id'  => $category->id,
            'name'         => 'تصوير أحداث',
            'slug'         => 'event-photography',
            'booking_type' => 'event',
            'pricing_mode' => 'package',
            'is_active'    => true,
        ]);

        $this->package = Package::create([
            'service_id' => $this->service->id,
            'name'       => 'باقة أساسية',
            'price'      => 50000,
            'is_active'  => true,
        ]);
    }

    private function makePricing(float $total = 50000): PricingResult
    {
        return new PricingResult(
            base: $total,
            optionsCost: 0,
            timeCost: 0,
            travelCost: 0,
            subtotal: $total,
            total: $total,
            deposit: 0,
        );
    }

    /** @test */
    public function it_creates_a_client_on_first_booking(): void
    {
        $this->assertDatabaseCount('clients', 0);

        app(BookingService::class)->createEventBooking([
            'name'       => 'أحمد محمد',
            'phone'      => '0551234567',
            'email'      => 'ahmed@test.com',
            'service_id' => $this->service->id,
            'event_date' => now()->addDays(10)->format('Y-m-d'),
        ], $this->makePricing());

        $this->assertDatabaseCount('clients', 1);
        $this->assertDatabaseHas('clients', ['phone' => '0551234567']);
    }

    /** @test */
    public function it_creates_booking_with_correct_type(): void
    {
        $result = app(BookingService::class)->createEventBooking([
            'name'       => 'أحمد محمد',
            'phone'      => '0551234567',
            'service_id' => $this->service->id,
            'event_date' => now()->addDays(10)->format('Y-m-d'),
        ], $this->makePricing());

        $this->assertDatabaseHas('bookings', [
            'booking_type' => 'event',
            'status'       => 'pending',
            'total_price'  => 50000,
        ]);

        $this->assertDatabaseHas('event_bookings', [
            'booking_id' => $result['booking']->id,
        ]);
    }

    /** @test */
    public function it_reuses_existing_client_by_phone(): void
    {
        Client::create([
            'name'  => 'أحمد محمد',
            'phone' => '0551234567',
        ]);

        app(BookingService::class)->createEventBooking([
            'name'       => 'أحمد محمد',
            'phone'      => '0551234567',
            'service_id' => $this->service->id,
            'event_date' => now()->addDays(10)->format('Y-m-d'),
        ], $this->makePricing());

        $this->assertDatabaseCount('clients', 1);
        $this->assertDatabaseCount('bookings', 1);
    }

    /** @test */
    public function it_creates_booking_items_for_package(): void
    {
        $result = app(BookingService::class)->createEventBooking([
            'name'         => 'أحمد محمد',
            'phone'        => '0551234567',
            'service_id'   => $this->service->id,
            'package_id'   => $this->package->id,
            'package_name' => $this->package->name,
            'event_date'   => now()->addDays(10)->format('Y-m-d'),
        ], $this->makePricing());

        $this->assertDatabaseHas('booking_items', [
            'booking_id' => $result['booking']->id,
            'item_type'  => 'package',
            'item_name'  => 'باقة أساسية',
        ]);
    }

    /** @test */
    public function it_generates_password_for_new_client(): void
    {
        $result = app(BookingService::class)->createEventBooking([
            'name'       => 'أحمد محمد',
            'phone'      => '0551234567',
            'service_id' => $this->service->id,
            'event_date' => now()->addDays(10)->format('Y-m-d'),
        ], $this->makePricing());

        $this->assertNotNull($result['generated_password']);
        $this->assertEquals(10, strlen($result['generated_password']));
    }
}