<?php

namespace Tests\Feature\Booking;

use App\DTOs\PricingResult;
use App\Models\Service\Category;
use App\Models\Service\Package;
use App\Models\Service\Service;
use App\Services\BookingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscriptionBookingTest extends TestCase
{
    use RefreshDatabase;

    private Service $service;
    private Package $package;

    protected function setUp(): void
    {
        parent::setUp();

        $category = Category::create([
            'name'      => 'تسويق',
            'slug'      => 'marketing',
            'is_active' => true,
        ]);

        $this->service = Service::create([
            'category_id'  => $category->id,
            'name'         => 'إدارة حسابات',
            'slug'         => 'social-media',
            'booking_type' => 'subscription',
            'pricing_mode' => 'package',
            'is_active'    => true,
        ]);

        $this->package = Package::create([
            'service_id' => $this->service->id,
            'name'       => 'باقة شهرية',
            'price'      => 25000,
            'is_active'  => true,
        ]);
    }

    private function makePricing(float $total = 25000): PricingResult
    {
        return new PricingResult(
            base: $total, optionsCost: 0, timeCost: 0,
            travelCost: 0, subtotal: $total, total: $total, deposit: 0,
        );
    }

    /** @test */
    public function it_creates_subscription_booking_with_subscription_record(): void
    {
        $result = app(BookingService::class)->createSubscriptionBooking([
            'name'          => 'شركة ABC',
            'phone'         => '0551112222',
            'email'         => 'abc@company.com',
            'service_id'    => $this->service->id,
            'package_id'    => $this->package->id,
            'billing_cycle' => 'monthly',
            'is_company'    => true,
            'business_name' => 'شركة ABC',
        ], $this->makePricing());

        $this->assertDatabaseHas('bookings', [
            'booking_type' => 'subscription',
            'status'       => 'pending',
            'total_price'  => 25000,
        ]);

        $this->assertDatabaseHas('subscriptions', [
            'client_id'    => $result['booking']->client_id,
            'booking_id'   => $result['booking']->id,
            'status'       => 'active',
            'renewal_type' => 'manual',
        ]);

        $this->assertDatabaseHas('subscription_bookings', [
            'booking_id'    => $result['booking']->id,
            'billing_cycle' => 'monthly',
            'plan_price'    => 25000,
        ]);
    }

    /** @test */
    public function it_creates_client_with_company_info(): void
    {
        app(BookingService::class)->createSubscriptionBooking([
            'name'          => 'أحمد',
            'phone'         => '0553334444',
            'service_id'    => $this->service->id,
            'package_id'    => $this->package->id,
            'billing_cycle' => 'monthly',
            'is_company'    => true,
            'business_name' => 'مؤسسة النور',
        ], $this->makePricing());

        $this->assertDatabaseHas('clients', [
            'phone'         => '0553334444',
            'is_company'    => true,
            'business_name' => 'مؤسسة النور',
        ]);
    }
}
