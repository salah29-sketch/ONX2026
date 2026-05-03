<?php

namespace Tests\Unit\Services;

use App\Enums\BookingStatus;
use App\Models\Booking\AppointmentBooking;
use App\Models\Booking\Booking;
use App\Models\Client\Client;
use App\Models\Service\Category;
use App\Models\Service\Service;
use App\Services\AvailabilityService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AvailabilityServiceTest extends TestCase
{
    use RefreshDatabase;

    private AvailabilityService $availability;
    private Service $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->availability = app(AvailabilityService::class);

        $category = Category::create([
            'name'      => 'Test',
            'slug'      => 'test-avail',
            'is_active' => true,
        ]);

        $this->service = Service::create([
            'category_id'  => $category->id,
            'name'         => 'Photography',
            'slug'         => 'photography-avail',
            'booking_type' => 'event',
            'pricing_mode' => 'package',
            'is_active'    => true,
        ]);
    }

    private function makeClient(): Client
    {
        return Client::create(['name' => 'Test Client', 'phone' => '055' . rand(1000000, 9999999)]);
    }

    private function makeBooking(string $date, string $status, ?int $serviceId = null): Booking
    {
        return Booking::create([
            'client_id'    => $this->makeClient()->id,
            'service_id'   => $serviceId ?? $this->service->id,
            'name'         => 'Test',
            'phone'        => '0551234567',
            'event_date'   => $date,
            'booking_type' => 'event',
            'status'       => $status,
            'total_price'  => 50000,
            'final_price'  => 50000,
        ]);
    }

    // ── isDateAvailable ──────────────────────────────────────────────

    /** @test */
    public function date_is_available_when_no_bookings_exist(): void
    {
        $this->assertTrue($this->availability->isDateAvailable('2030-06-15'));
    }

    /** @test */
    public function date_is_not_available_when_confirmed_booking_exists(): void
    {
        $this->makeBooking('2030-06-20', BookingStatus::CONFIRMED->value);

        $this->assertFalse($this->availability->isDateAvailable('2030-06-20'));
    }

    /** @test */
    public function date_is_not_available_when_in_progress_booking_exists(): void
    {
        $this->makeBooking('2030-07-10', BookingStatus::IN_PROGRESS->value);

        $this->assertFalse($this->availability->isDateAvailable('2030-07-10'));
    }

    /** @test */
    public function date_is_available_after_cancelled_booking(): void
    {
        $this->makeBooking('2030-08-01', BookingStatus::CANCELLED->value);

        $this->assertTrue($this->availability->isDateAvailable('2030-08-01'));
    }

    // ── getDateStatus ────────────────────────────────────────────────

    /** @test */
    public function status_is_available_with_no_bookings(): void
    {
        $this->assertEquals('available', $this->availability->getDateStatus('2030-09-01'));
    }

    /** @test */
    public function status_is_pending_with_only_pending_booking(): void
    {
        $this->makeBooking('2030-09-05', BookingStatus::PENDING->value);

        $this->assertEquals('pending', $this->availability->getDateStatus('2030-09-05'));
    }

    /** @test */
    public function status_is_booked_with_confirmed_booking(): void
    {
        $this->makeBooking('2030-09-10', BookingStatus::CONFIRMED->value);

        $this->assertEquals('booked', $this->availability->getDateStatus('2030-09-10'));
    }

    /** @test */
    public function status_is_booked_with_assigned_booking(): void
    {
        $this->makeBooking('2030-09-15', BookingStatus::ASSIGNED->value);

        $this->assertEquals('booked', $this->availability->getDateStatus('2030-09-15'));
    }

    /** @test */
    public function status_is_booked_even_when_pending_also_exists(): void
    {
        $this->makeBooking('2030-09-20', BookingStatus::PENDING->value);
        $this->makeBooking('2030-09-20', BookingStatus::CONFIRMED->value);

        $this->assertEquals('booked', $this->availability->getDateStatus('2030-09-20'));
    }

    /** @test */
    public function cancelled_booking_does_not_block_date(): void
    {
        $this->makeBooking('2030-10-01', BookingStatus::CANCELLED->value);

        $this->assertEquals('available', $this->availability->getDateStatus('2030-10-01'));
    }

    // ── service-scoped availability ──────────────────────────────────

    /** @test */
    public function booking_on_other_service_does_not_block_date_for_this_service(): void
    {
        $otherCategory = Category::create(['name' => 'Other', 'slug' => 'other-avail', 'is_active' => true]);
        $otherService  = Service::create([
            'category_id'  => $otherCategory->id,
            'name'         => 'Other Service',
            'slug'         => 'other-svc-avail',
            'booking_type' => 'event',
            'pricing_mode' => 'package',
            'is_active'    => true,
        ]);

        $this->makeBooking('2030-10-15', BookingStatus::CONFIRMED->value, $otherService->id);

        $this->assertEquals(
            'available',
            $this->availability->getDateStatus('2030-10-15', $this->service->id)
        );
    }

    // ── isSlotAvailable ──────────────────────────────────────────────

    /** @test */
    public function slot_is_available_when_no_appointments_exist(): void
    {
        $this->assertTrue(
            $this->availability->isSlotAvailable('2030-11-01', '10:00', '11:00', $this->service->id)
        );
    }

    /** @test */
    public function overlapping_slot_is_not_available(): void
    {
        $booking = $this->makeBooking('2030-11-05', BookingStatus::CONFIRMED->value);
        $booking->update(['booking_type' => 'appointment']);

        AppointmentBooking::create([
            'booking_id'       => $booking->id,
            'slot_start'       => '10:00',
            'slot_end'         => '11:00',
            'duration_minutes' => 60,
        ]);

        $this->assertFalse(
            $this->availability->isSlotAvailable('2030-11-05', '10:30', '11:30', $this->service->id)
        );
    }

    /** @test */
    public function adjacent_slot_is_available(): void
    {
        $booking = $this->makeBooking('2030-11-10', BookingStatus::CONFIRMED->value);
        $booking->update(['booking_type' => 'appointment']);

        AppointmentBooking::create([
            'booking_id'       => $booking->id,
            'slot_start'       => '10:00',
            'slot_end'         => '11:00',
            'duration_minutes' => 60,
        ]);

        $this->assertTrue(
            $this->availability->isSlotAvailable('2030-11-10', '11:00', '12:00', $this->service->id)
        );
    }

    // ── getBookedDates ───────────────────────────────────────────────

    /** @test */
    public function returns_empty_array_when_no_bookings(): void
    {
        $this->assertEmpty($this->availability->getBookedDates());
    }

    /** @test */
    public function returns_dates_of_active_bookings(): void
    {
        $this->makeBooking('2030-12-01', BookingStatus::CONFIRMED->value);
        $this->makeBooking('2030-12-05', BookingStatus::PENDING->value);

        $dates = $this->availability->getBookedDates();

        $this->assertContains('2030-12-01', $dates);
        $this->assertContains('2030-12-05', $dates);
    }

    /** @test */
    public function cancelled_bookings_are_excluded_from_booked_dates(): void
    {
        $this->makeBooking('2030-12-20', BookingStatus::CANCELLED->value);

        $dates = $this->availability->getBookedDates();

        $this->assertNotContains('2030-12-20', $dates);
    }
}
