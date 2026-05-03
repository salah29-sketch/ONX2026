<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Booking\Booking;
use App\Services\BookingService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function __construct(protected BookingService $bookingService)
    {
    }

    /**
     * Verify booking token for stateless access
     */
    private function verifyBookingToken(Booking $booking, ?string $token): bool
    {
        if (!$token) {
            return false;
        }
        $expected = hash('sha256', $booking->id . '|' . $booking->created_at);
        return hash_equals($expected, $token);
    }

    /**
     * صفحة تأكيد الحجز
     */
    public function confirmation(Request $request, Booking $booking)
    {
        if (!session("booking_confirmed_{$booking->id}") && !$this->verifyBookingToken($booking, $request->query('token'))) {
            abort(403);
        }

        $meta  = $this->bookingService->getBookingMeta($booking);
        $bid   = $booking->id;
        $creds = session('booking_creds_' . $bid);

        $clientLogin    = $creds['login']    ?? ($booking->client?->email ?: $booking->client?->phone);
        $clientPassword = $creds['password'] ?? null;

        $packagePrice = $meta['packagePrice'] ?? null;
        $totalPrice   = $booking->total_price ? (float) $booking->total_price : null;

        $extraPrice = null;
        if ($totalPrice && $packagePrice && $totalPrice > (float) $packagePrice) {
            $extraPrice = $totalPrice - (float) $packagePrice;
        }

        return view('front.booking.confirmation', [
            'booking'         => $booking,
            'packageName'     => $meta['packageName'],
            'packagePrice'    => $packagePrice,
            'totalPrice'      => $totalPrice,
            'extraPrice'      => $extraPrice,
            'locationName'    => $meta['locationName'],
            'clientLogin'     => $clientLogin,
            'clientPassword'  => $clientPassword,
        ]);
    }

    /**
     * تحميل PDF الحجز
     */
    public function pdf(Request $request, Booking $booking)
    {
        if (!session("booking_confirmed_{$booking->id}") && !$this->verifyBookingToken($booking, $request->query('token'))) {
            abort(403);
        }

        $meta  = $this->bookingService->getBookingMeta($booking);
        $bid   = $booking->id;
        $creds = session('booking_creds_' . $bid);

        $client      = $booking->client;
        $clientLogin = $creds['login']
            ?? ($client ? ($client->email ?: $client->phone) : null);
        $clientPassword = $creds['password'] ?? null;

        $pdf = Pdf::loadView('front.booking.pdf', [
            'booking'         => $booking,
            'packageName'     => $meta['packageName'],
            'packagePrice'    => $meta['packagePrice'],
            'locationName'    => $meta['locationName'],
            'clientLogin'     => $clientLogin,
            'clientPassword'  => $clientPassword,
        ]);

        session()->forget('booking_creds_' . $bid);

        return $pdf->download('booking-' . $booking->id . '.pdf');
    }
}
