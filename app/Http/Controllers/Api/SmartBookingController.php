<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Content\PortfolioItem;
use App\Models\Content\Testimonial;
use App\Models\Event\Venue;
use App\Models\Event\Wilaya;
use App\Models\Service\Category;
use App\Models\Service\Package;
use App\Models\Service\Service;
use App\Services\AvailabilityService;
use App\Services\BookingService;
use App\Services\PricingCalculator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;

class SmartBookingController extends Controller
{
    public function __construct(
        protected BookingService     $bookingService,
        protected PricingCalculator  $pricing,
        protected AvailabilityService $availability,
    ) {}

    // ── GET /api/smart-booking/init ──────────────────────────────
    // يجلب كل البيانات الأولية دفعة واحدة
    public function init(): JsonResponse
    {
        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->get(['id', 'name', 'icon', 'description']);

        $wilayas = Wilaya::orderBy('code')->get(['id', 'name', 'code', 'is_local']);

        $testimonials = Testimonial::where('is_active', true)
            ->where('status', 'approved')
            ->inRandomOrder()
            ->limit(3)
            ->get(['id', 'client_name', 'client_role', 'content', 'rating', 'initial']);

        // youtube_thumbnail هو accessor — نجلب الكائن كاملاً ونضيف الـ thumbnail يدوياً
        $recentWorks = PortfolioItem::where('is_active', true)
            ->where('is_featured', true)
            ->latest()
            ->limit(3)
            ->get(['id', 'title', 'youtube_video_id', 'image_path'])
            ->map(fn ($item) => [
                'id'                => $item->id,
                'title'             => $item->title,
                'youtube_video_id'  => $item->youtube_video_id,
                'image_path'        => $item->image_path ? \Illuminate\Support\Facades\Storage::url($item->image_path) : null,
                'youtube_thumbnail' => $item->youtube_thumbnail,
            ]);

        return response()->json(compact('categories', 'wilayas', 'testimonials', 'recentWorks'));
    }

    // ── GET /api/smart-booking/services?category_id= ────────────
    public function services(Request $request): JsonResponse
    {
        $categoryId = (int) $request->query('category_id');
        if (!$categoryId) return response()->json([]);

        $services = Service::where('is_active', true)
            ->where('category_id', $categoryId)
            ->orderBy('sort_order')
            ->get(['id', 'name', 'description', 'icon', 'base_price',
                   'booking_type', 'time_mode', 'show_venue_selector',
                   'show_wilaya_selector', 'deposit_amount']);

        return response()->json($services);
    }

    // ── GET /api/smart-booking/packages?service_id= ─────────────
    public function packages(Request $request): JsonResponse
    {
        $serviceId = (int) $request->query('service_id');
        if (!$serviceId) return response()->json([]);

        $packages = Package::with('activeOptions')
            ->where('is_active', true)
            ->where('service_id', $serviceId)
            ->orderBy('sort_order')
            ->get();

        return response()->json($packages);
    }

    // ── GET /api/smart-booking/venues?wilaya_id= ────────────────
    public function venues(Request $request): JsonResponse
    {
        $query = Venue::where('is_active', true)->orderBy('sort_order')->orderBy('name');

        if ($wilayaId = $request->query('wilaya_id')) {
            $query->where('wilaya_id', (int) $wilayaId);
        }

        return response()->json($query->get(['id', 'name', 'address', 'wilaya_id']));
    }

    // ── GET /api/smart-booking/availability?date=&service_id= ───
    public function availability(Request $request): JsonResponse
    {
        $date      = $request->query('date');
        $serviceId = $request->query('service_id') ? (int) $request->query('service_id') : null;

        if (!$date) return response()->json(['status' => 'unknown']);

        $status = $this->availability->getDateStatus($date, $serviceId);

        return response()->json(['status' => $status]);
    }

    // ── POST /api/smart-booking/price ───────────────────────────
    public function price(Request $request): JsonResponse
    {
        $serviceId  = (int) $request->input('service_id');
        $packageId  = $request->input('package_id') ? (int) $request->input('package_id') : null;
        $options    = $request->input('options', []);
        $startTime  = $request->input('start_time');
        $endTime    = $request->input('end_time');
        $venueId    = $request->input('venue_id') ? (int) $request->input('venue_id') : null;
        $wilayaId   = $request->input('wilaya_id') ? (int) $request->input('wilaya_id') : null;

        $svc = Service::find($serviceId);
        if (!$svc) return response()->json(['error' => 'خدمة غير موجودة'], 404);

        $pkg = $packageId ? Package::find($packageId) : null;
        $type = $svc->booking_type instanceof \App\Enums\BookingType
            ? $svc->booking_type->value
            : (string) $svc->booking_type;

        $result = match ($type) {
            'appointment'  => $this->pricing->calculateAppointment($svc, $pkg),
            'subscription' => $this->pricing->calculateSubscription($pkg),
            default        => $this->pricing->calculateEvent(
                $svc, $pkg, $options,
                $startTime ?: null, $endTime ?: null,
                $venueId, $wilayaId,
            ),
        };

        return response()->json($result->toArray());
    }

    // ── POST /api/smart-booking/submit ──────────────────────────
    public function submit(Request $request): JsonResponse
    {
        // Rate limiting
        $key = 'smart-booking:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 3)) {
            return response()->json(['error' => 'طلبات كثيرة، حاول بعد دقائق'], 429);
        }
        RateLimiter::hit($key, 300);

        $validator = Validator::make($request->all(), [
            'name'       => 'required|string|max:255',
            'email'      => 'required|email',
            'phone'      => 'required|string|max:30',
            'service_id' => 'required|integer|exists:services,id',
            'type'       => 'required|in:event,appointment,subscription',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $svc  = Service::find($request->input('service_id'));
        $pkg  = $request->input('package_id') ? Package::find($request->input('package_id')) : null;
        $type = $request->input('type');

        // Recalculate price server-side (never trust client)
        $options  = $request->input('selected_options', []);
        $venueId  = $request->input('venue_id') ? (int) $request->input('venue_id') : null;
        $wilayaId = $request->input('wilaya_id') ? (int) $request->input('wilaya_id') : null;

        $pricing = match ($type) {
            'appointment'  => $this->pricing->calculateAppointment($svc, $pkg),
            'subscription' => $this->pricing->calculateSubscription($pkg),
            default        => $this->pricing->calculateEvent(
                $svc, $pkg, $options,
                $request->input('start_time') ?: null,
                $request->input('end_time') ?: null,
                $venueId, $wilayaId,
            ),
        };

        $data = $request->only([
            'name', 'email', 'phone', 'service_id', 'notes',
            'event_date', 'start_time', 'end_time',
            'appointment_date', 'slot_start', 'slot_end', 'duration_minutes',
            'venue_id', 'venue_custom', 'wilaya_id',
            'package_id', 'package_name', 'package_snapshot',
            'selected_options', 'promo_code',
        ]);

        try {
            $result = match ($type) {
                'appointment'  => $this->bookingService->createAppointmentBooking($data, $pricing),
                'subscription' => $this->bookingService->createSubscriptionBooking($data, $pricing),
                default        => $this->bookingService->createEventBooking($data, $pricing),
            };

            $booking = $result['booking'];

            // Store creds in session for confirmation page
            if ($result['generated_password'] ?? null) {
                session()->put('booking_creds_' . $booking->id, [
                    'login'    => $booking->email ?? $booking->phone,
                    'password' => $result['generated_password'],
                ]);
            }
            session()->put("booking_confirmed_{$booking->id}", true);

            $token = hash('sha256', $booking->id . '|' . $booking->created_at);

            return response()->json([
                'success'            => true,
                'booking_id'         => $booking->id,
                'booking_ref'        => str_pad($booking->id, 4, '0', STR_PAD_LEFT),
                'generated_password' => $result['generated_password'] ?? null,
                'confirmation_url'   => route('booking.confirmation', $booking) . '?token=' . $token,
                'pricing'            => $pricing->toArray(),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}