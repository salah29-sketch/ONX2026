<?php

namespace Tests\Feature\Booking;

use App\DTOs\PricingResult;
use App\Models\Service\Category;
use App\Models\Service\Package;
use App\Models\Service\Service;
use App\Services\BookingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppointmentBookingTest extends TestCase
{
    use RefreshDatabase;

    private Service $service;
    private Package $package;

    protected function setUp(): void
    {
        parent::setUp();

        $category = Category::create([
            'name'      => 'استوديو',
            'slug'      => 'studio',
            'is_active' => true,
        ]);

        $this->service = Service::create([
            'category_id'  => $category->id,
            'name'         => 'جلسة تصوير',
            'slug'         => 'photo-session',
            'booking_type' => 'appointment',
            'pricing_mode' => 'package',
            'is_active'    => true,
        ]);

        $this->package = Package::create([
            'service_id' => $this->service->id,
            'name'       => 'جلسة عادية',
            'price'      => 15000,
            'is_active'  => true,
        ]);
    }

    private function makePricing(float $total = 15000): PricingResult
    {
        return new PricingResult(
            base: $total, optionsCost: 0, timeCost: 0,
            travelCost: 0, subtotal: $total, total: $total, deposit: 0,
        );
    }

    /** @test */
    public function it_creates_appointment_booking(): void
    {
        $result = app(BookingService::class)->createAppointmentBooking([
            'name'             => 'سارة أحمد',
            'phone'            => '0661234567',
            'email'            => 'sara@test.com',
            'service_id'       => $this->service->id,
            'package_id'       => $this->package->id,
            'appointment_date' => now()->addDays(3)->format('Y-m-d'),
            'slot_start'       => '10:00',
            'slot_end'         => '11:00',
            'duration_minutes' => 60,
        ], $this->makePricing());

        $this->assertDatabaseHas('bookings', [
            'booking_type' => 'appointment',
            'status'       => 'pending',
            'total_price'  => 15000,
        ]);

        $this->assertDatabaseHas('appointment_bookings', [
            'booking_id'       => $result['booking']->id,
            'slot_start'       => '10:00',
            'duration_minutes' => 60,
        ]);
    }

    /** @test */
    public function it_generates_password_for_new_appointment_client(): void
    {
        $result = app(BookingService::class)->createAppointmentBooking([
            'name'             => 'عميل جديد',
            'phone'            => '0771234567',
            'service_id'       => $this->service->id,
            'appointment_date' => now()->addDays(5)->format('Y-m-d'),
            'slot_start'       => '14:00',
            'slot_end'         => '15:00',
            'duration_minutes' => 60,
        ], $this->makePricing());

        $this->assertNotNull($result['generated_password']);
    }
}
