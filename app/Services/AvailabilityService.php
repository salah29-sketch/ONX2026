<?php

namespace App\Services;

use App\Enums\BookingStatus;
use App\Models\Booking\Booking;

class AvailabilityService
{
    /**
     * Check if a date is available for a specific service (or globally).
     */
    public function isDateAvailable(string $date, ?int $serviceId = null): bool
    {
        return $this->getDateStatus($date, $serviceId) === 'available';
    }

    /**
     * Returns 'available', 'pending', or 'booked'.
     */
    public function getDateStatus(string $date, ?int $serviceId = null): string
    {
        $bookings = Booking::whereDate('event_date', $date)
            ->whereNotIn('status', ['cancelled', \App\Enums\BookingStatus::CANCELLED])
            ->when($serviceId, fn ($q) => $q->where('service_id', $serviceId))
            ->pluck('status')
            ->map(fn ($status) => $status instanceof \BackedEnum ? $status->value : $status);

        if ($bookings->isEmpty()) {
            return 'available';
        }

        $confirmed = [
            BookingStatus::CONFIRMED->value,
            BookingStatus::ASSIGNED->value,
            BookingStatus::IN_PROGRESS->value,
            BookingStatus::COMPLETED->value,
        ];

        if ($bookings->intersect($confirmed)->isNotEmpty()) {
            return 'booked';
        }

        return 'pending';
    }

    /**
     * Check if a time slot is available for appointments on a given date/service.
     */
    public function isSlotAvailable(string $date, string $startTime, string $endTime, int $serviceId): bool
    {
        return !Booking::whereDate('event_date', $date)
            ->where('service_id', $serviceId)
            ->whereNotIn('status', ['cancelled'])
            ->whereHas('appointmentBooking', function ($q) use ($startTime, $endTime) {
                $q->where('slot_start', '<', $endTime)
                  ->where('slot_end', '>', $startTime);
            })
            ->exists();
    }

    /**
     * Get all booked dates for a service (for calendar display).
     */
    public function getBookedDates(?int $serviceId = null): array
    {
        return Booking::whereNotNull('event_date')
            ->whereNotIn('status', ['cancelled'])
            ->when($serviceId, fn ($q) => $q->where('service_id', $serviceId))
            ->pluck('event_date')
            ->map(fn ($d) => \Carbon\Carbon::parse($d)->format('Y-m-d'))
            ->unique()
            ->values()
            ->toArray();
    }
}
