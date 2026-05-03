<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking\Booking;
use App\Models\Booking\BookingPayment;
use Illuminate\Http\Request;

class BookingPaymentsController extends Controller
{
    /**
     * إضافة دفعة جديدة
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'amount'     => 'required|numeric|min:0.01',
            'type'       => 'required|in:deposit,partial,final,full',
            'method'     => 'required|in:cash,bank_transfer,other',
            'reference'  => 'nullable|string|max:255',
            'paid_at'    => 'required|date',
            'notes'      => 'nullable|string|max:1000',
        ]);

        BookingPayment::create($data);

        return back()->with('message', 'تم تسجيل الدفعة بنجاح.');
    }

    /**
     * حذف دفعة
     */
    public function destroy(BookingPayment $payment)
    {
        $payment->delete();
        return back()->with('message', 'تم حذف الدفعة.');
    }

    /**
     * تحديث السعر الإجمالي للحجز
     */
    public function updateTotal(Request $request, Booking $booking)
    {
        $request->validate([
            'total_price' => 'required|numeric|min:0',
        ]);

        $booking->update(['total_price' => $request->total_price]);

        return back()->with('message', 'تم تحديث السعر الإجمالي.');
    }
}
