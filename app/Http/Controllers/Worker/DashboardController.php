<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use App\Models\Booking\Booking;
use App\Models\Booking\EventBooking;
use App\Models\Client\Client;
use App\Models\Service\Package;
use App\Models\Service\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // جلب خدمة حفلات الزفاف
        $weddingService = Service::where('slug', 'wedding')->first();

        // جلب الحجوزات (فقط حفلات الزفاف)
        $query = Booking::with(['client', 'service', 'package', 'eventBooking'])
            ->where('service_id', optional($weddingService)->id);

        $dateFilter   = $request->get('date', 'all');
        $statusFilter = $request->get('status');
        $monthFilter  = $request->get('month', now()->format('Y-m'));

        if ($dateFilter === 'today') {
            $query->whereDate('event_date', Carbon::today());
        } elseif ($dateFilter === 'upcoming') {
            $query->where('event_date', '>=', Carbon::today());
        }

        if ($statusFilter && in_array($statusFilter, ['pending', 'confirmed', 'in_progress', 'completed', 'cancelled'], true)) {
            $query->where('status', $statusFilter);
        }

        $bookings = $query->orderBy('event_date')->paginate(20)->withQueryString();

        // حجوزات الشهر المحدد للتقويم
        [$calYear, $calMonth] = explode('-', $monthFilter);
        $calBookings = Booking::where('service_id', optional($weddingService)->id)
            ->whereYear('event_date', $calYear)
            ->whereMonth('event_date', $calMonth)
            ->orderBy('event_date')
            ->get(['id', 'name', 'event_date', 'status']);

        // الباقات المتاحة لخدمة الزفاف
        $packages = $weddingService
            ? Package::where('service_id', $weddingService->id)->where('is_active', true)->orderBy('sort_order')->get()
            : collect();

        // العملاء للـ select في نموذج الحجز
        $clients = Client::orderBy('name')->get(['id', 'name', 'phone', 'email']);

        $calBookingsJson = $calBookings->map(function ($b) {
            return [
                'date'   => $b->event_date?->format('Y-m-d'),
                'name'   => $b->name,
                'status' => $b->status instanceof \App\Enums\BookingStatus
                            ? $b->status->value
                            : $b->status,
            ];
        })->values();

        // ── إذا كان طلب AJAX → أرجع JSON فقط (بدون إعادة تحميل الصفحة) ──
        if ($request->ajax()) {
            $statusLabels = [
                'pending'     => 'قيد المراجعة',
                'confirmed'   => 'مؤكد',
                'in_progress' => 'قيد التنفيذ',
                'completed'   => 'مكتمل',
                'cancelled'   => 'ملغى',
            ];

            $bookingsHtml = view('worker.partials.bookings', compact('bookings', 'statusLabels'))->render();

            return response()->json([
                'calBookings'  => $calBookingsJson,
                'bookingsHtml' => $bookingsHtml,
            ]);
        }

        return view('worker.dashboard', compact(
            'bookings', 'dateFilter', 'statusFilter',
            'calBookings', 'calBookingsJson', 'monthFilter', 'calYear', 'calMonth',
            'packages', 'clients', 'weddingService'
        ));
    }

    public function store(Request $request)
    {
        $weddingService = Service::where('slug', 'wedding')->firstOrFail();

        $data = $request->validate([
            'name'         => 'required|string|max:255',
            'phone'        => 'required|string|max:50',
            'email'        => 'nullable|email|max:255',
            'event_date'   => 'required|date|after_or_equal:today',
            'package_id'   => 'nullable|exists:packages,id',
            'notes'        => 'nullable|string|max:1000',
            'start_time'   => 'nullable|date_format:H:i',
            'end_time'     => 'nullable|date_format:H:i',
            'venue_custom' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($data, $weddingService) {
            $booking = Booking::create([
                'service_id'   => $weddingService->id,
                'package_id'   => $data['package_id'] ?? null,
                'booking_type' => 'event',
                'name'         => $data['name'],
                'phone'        => $data['phone'],
                'email'        => $data['email'] ?? null,
                'event_date'   => $data['event_date'],
                'notes'        => $data['notes'] ?? null,
                'status'       => 'pending',
                'total_price'  => 0,
                'final_price'  => 0,
            ]);

            EventBooking::create([
                'booking_id'   => $booking->id,
                'start_time'   => $data['start_time'] ?? null,
                'end_time'     => $data['end_time'] ?? null,
                'venue_custom' => $data['venue_custom'] ?? null,
                'time_cost'    => 0,
                'travel_cost'  => 0,
                'late_fee'     => 0,
            ]);
        });

        return redirect()->route('worker.dashboard')->with('success', 'تم إضافة الحجز بنجاح.');
    }
}