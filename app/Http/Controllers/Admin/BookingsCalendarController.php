<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking\Booking;
use Carbon\Carbon;

class BookingsCalendarController extends Controller
{
    public function index()
    {
        $bookingsWithDates = Booking::with(['client', 'service', 'eventBooking.venue'])
            ->whereNotNull('event_date')
            ->whereIn('status', ['pending', 'unconfirmed', 'confirmed', 'assigned', 'in_progress', 'completed', 'cancelled'])
            ->orderBy('event_date')
            ->get();

        $calendarItems = $bookingsWithDates->map(function ($booking) {
            $locationName = $booking->eventBooking?->venue?->name
                ?? $booking->eventBooking?->venue_custom
                ?? '—';

            $clientName = $booking->client?->name ?? $booking->name;

            return [
                'title'        => $clientName,
                'start'        => Carbon::parse($booking->event_date)->format('Y-m-d'),
                'url'          => route('admin.bookings.show', $booking->id),
                'status'       => $booking->status?->value ?? 'pending',
                'service_name' => $booking->service?->name ?? '—',
                'location'     => $locationName,
            ];
        });

        $stats = [
            'total'       => Booking::count(),
            'unconfirmed' => Booking::whereIn('status', ['unconfirmed', 'pending'])->count(),
            'confirmed'   => Booking::where('status', 'confirmed')->count(),
            'cancelled'   => Booking::where('status', 'cancelled')->count(),
        ];

        return view('admin.bookings.calendar', [
            'calendarItems' => $calendarItems,
            'stats'         => $stats,
        ]);
    }
}
