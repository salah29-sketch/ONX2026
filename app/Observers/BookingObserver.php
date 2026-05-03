<?php

namespace App\Observers;

use App\Models\Booking\Booking;
use App\Models\Booking\BookingCalendarSlot;

class BookingObserver
{
    public function updated(Booking $booking): void
    {
        if ($booking->isDirty('status') && $booking->status === 'cancelled') {
            BookingCalendarSlot::where('booking_id', $booking->id)->delete();
        }
    }

    public function deleted(Booking $booking): void
    {
        BookingCalendarSlot::where('booking_id', $booking->id)->delete();
    }
}
