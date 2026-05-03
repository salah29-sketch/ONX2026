<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Booking\Booking;
use Illuminate\Http\Request;

class BookingStatusController extends Controller
{
    public function index()
    {
        return view('front.booking.status');
    }

    public function search(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:50',
        ], [
            'phone.required' => 'رقم الهاتف مطلوب.',
        ]);

        $phone = preg_replace('/\s+/', '', $request->phone);

        $bookings = Booking::with(['client:id,name,phone', 'service:id,name'])
            ->select('id', 'client_id', 'service_id', 'phone', 'status', 'event_date', 'booking_type', 'created_at')
            ->where(function ($q) use ($phone) {
                $q->where('phone', $phone)
                  ->orWhereHas('client', fn ($c) => $c->where('phone', $phone));
            })
            ->orderByDesc('created_at')
            ->get();

        if ($bookings->isEmpty()) {
            return back()
                ->withInput()
                ->withErrors(['phone' => 'لم يتم العثور على أي حجوزات بهذا الرقم.']);
        }

        return view('front.booking.status', compact('bookings', 'phone'));
    }
}