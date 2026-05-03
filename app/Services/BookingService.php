<?php

namespace App\Services;

use App\DTOs\PricingResult;
use App\Enums\BookingStatus;
use App\Enums\BookingType;
use App\Models\Booking\AppointmentBooking;
use App\Models\Booking\Booking;
use App\Models\Booking\BookingItem;
use App\Models\Booking\EventBooking;
use App\Models\Booking\SubscriptionBooking;
use App\Models\Client\Client;
use App\Models\Subscription\Subscription;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class BookingService
{
    public function __construct(
        protected PromoCodeService $promoCodeService
    ) {
    }

    /**
     * Validate a promo code against a total (used for AJAX checks).
     */
    public function checkPromoCode(string $code, float $total): array
    {
        $promoCodeClass = '\\App\\Models\\Promo\\PromoCode';
        $promo = $promoCodeClass::where('code', $code)->first();

        if (!$promo) {
            return ['valid' => false, 'message' => 'كود التخفيض غير صحيح.'];
        }
        if (!$promo->is_active) {
            return ['valid' => false, 'message' => 'هذا الكود غير مفعّل.'];
        }
        if ($promo->max_uses !== null && $promo->used_count >= $promo->max_uses) {
            return ['valid' => false, 'message' => 'تم استنفاد استخدام هذا الكود.'];
        }
        if (!$promo->meetsMinOrder($total)) {
            $min = number_format((float) $promo->min_order_value, 0);
            return ['valid' => false, 'message' => "الحد الأدنى للطلب: {$min} دج."];
        }

        $discount  = $total > 0 ? $promo->calculateDiscount($total) : 0;
        $typeLabel = $promo->discount_type === 'percent'
            ? number_format((float) $promo->value, 0) . '%'
            : number_format((float) $promo->value, 0) . ' دج';

        return [
            'valid'           => true,
            'message'         => "✓ خصم {$typeLabel} مطبّق",
            'discount_type'   => $promo->discount_type,
            'discount_value'  => (float) $promo->value,
            'discount_amount' => $discount,
            'final_price'     => $total > 0 ? max(0, $total - $discount) : null,
        ];
    }

    /**
     * Return the availability status string for a given date.
     * Returns 'available', 'pending', or 'booked'.
     */
    public function getDateStatus(string $date, ?\App\Models\Service\Service $service = null): array
    {
        $query = Booking::whereDate('event_date', $date);

        if ($service) {
            $query->where('service_id', $service->id);
        }

        $bookings = $query->get();

        foreach ($bookings as $booking) {
            if (in_array($booking->status, [
                BookingStatus::CONFIRMED->value,
                BookingStatus::ASSIGNED->value,
                BookingStatus::IN_PROGRESS->value,
                BookingStatus::COMPLETED->value,
            ], true)) {
                return ['status' => 'booked', 'message' => 'هذا اليوم محجوز ومؤكد'];
            }
            if ($booking->status === BookingStatus::PENDING->value) {
                return ['status' => 'pending', 'message' => 'هذا اليوم محجوز وغير مؤكد'];
            }
        }

        return ['status' => 'available', 'message' => 'هذا اليوم متاح'];
    }

    /**
     * Find an existing client by phone or email, or create a new one.
     */
    public function findOrCreateClient(array $data): Client
    {
        $phone = trim((string) ($data['phone'] ?? ''));
        $email = isset($data['email']) ? trim((string) $data['email']) : null;
        $name  = trim((string) ($data['name'] ?? ''));

        if ($name === '') {
            $name = $phone ?: $email ?: 'عميل';
        }

        $client = $phone ? Client::where('phone', $phone)->first() : null;

        if (!$client && $email) {
            $client = Client::where('email', $email)->first();
        }

        $isCompany    = !empty($data['is_company']);
        $businessName = trim((string) ($data['business_name'] ?? '')) ?: null;

        if (!$client) {
            $client = Client::create([
                'name'          => $name,
                'phone'         => $phone ?: null,
                'email'         => $email ?: null,
                'is_company'    => $isCompany,
                'business_name' => $businessName,
            ]);
        } else {
            $update = [];
            if (empty($client->name) && $name !== '')        $update['name']          = $name;
            if (empty($client->phone) && $phone)             $update['phone']         = $phone;
            if (empty($client->email) && $email)             $update['email']         = $email;
            if (!$client->is_company && $isCompany)          $update['is_company']    = true;
            if (empty($client->business_name) && $businessName) $update['business_name'] = $businessName;
            if (!empty($update)) {
                $client->update($update);
            }
        }

        return $client;
    }

    /**
     * Fetch package/venue metadata for confirmation pages and PDFs.
     */
    public function getBookingMeta(Booking $booking): array
    {
        $packageName  = null;
        $packagePrice = null;
        $pkg          = $booking->package;

        if ($pkg) {
            $packageName  = $pkg->name;
            $packagePrice = $pkg->price ?? $pkg->price_note;
        }

        $locationName = null;
        if ($booking->eventBooking) {
            $locationName = $booking->eventBooking->venueName();
        }

        return [
            'packageName'  => $packageName,
            'packagePrice' => $packagePrice,
            'package'      => $pkg,
            'locationName' => $locationName,
        ];
    }

    /**
     * Check if a date is taken by an active booking, excluding one booking ID (for edits).
     */
    public function isDateTakenForUpdate(string $date, int $excludeBookingId): bool
    {
        return Booking::where('id', '!=', $excludeBookingId)
            ->whereDate('event_date', $date)
            ->whereIn('status', BookingStatus::activeValues())
            ->exists();
    }

    /**
     * Check if a date is taken by any active booking.
     */
    public function isDateTaken(string $date): bool
    {
        return Booking::whereDate('event_date', $date)
            ->whereIn('status', BookingStatus::activeValues())
            ->exists();
    }

    // ═══════════════════════════════════════════════════════════════
    //  Unified booking creation
    // ═══════════════════════════════════════════════════════════════

    /**
     * Create an event booking with all related records in a single transaction.
     */
    public function createEventBooking(array $data, PricingResult $pricing): array
    {
        return DB::transaction(function () use ($data, $pricing) {
            $client   = $this->findOrCreateClient($data);
            $password = $this->ensureClientPassword($client);

            $booking = Booking::create([
                'client_id'       => $client->id,
                'service_id'      => $data['service_id'],
                'name'            => $data['name'],
                'phone'           => $data['phone'],
                'email'           => $data['email'] ?? null,
                'event_date'      => $data['event_date'],
                'booking_type'    => BookingType::EVENT->value,
                'status'          => BookingStatus::PENDING->value,
                'total_price'     => $pricing->total,
                'final_price'     => $pricing->total,
                'discount_amount' => 0,
                'notes'           => $data['notes'] ?? null,
            ]);

            EventBooking::create([
                'booking_id'   => $booking->id,
                'start_time'   => $data['start_time'] ?? null,
                'end_time'     => $data['end_time'] ?? null,
                'venue_id'     => $data['venue_id'] ?? null,
                'venue_custom' => $data['venue_custom'] ?? null,
                'wilaya_id'    => $data['wilaya_id'] ?? null,
                'time_cost'    => $pricing->timeCost,
                'travel_cost'  => $pricing->travelCost,
            ]);

            $this->createBookingItems($booking, $data, $pricing);
            $this->applyPromoIfPresent($booking, $data);

            return [
                'booking'            => $booking->fresh(),
                'generated_password' => $password,
            ];
        });
    }

    /**
     * Create an appointment booking in a single transaction.
     */
    public function createAppointmentBooking(array $data, PricingResult $pricing): array
    {
        return DB::transaction(function () use ($data, $pricing) {
            $client   = $this->findOrCreateClient($data);
            $password = $this->ensureClientPassword($client);

            $booking = Booking::create([
                'client_id'       => $client->id,
                'service_id'      => $data['service_id'],
                'package_id'      => $data['package_id'] ?? null,
                'name'            => $data['name'],
                'phone'           => $data['phone'],
                'email'           => $data['email'] ?? null,
                'event_date'      => $data['appointment_date'],
                'booking_type'    => BookingType::APPOINTMENT->value,
                'status'          => BookingStatus::PENDING->value,
                'total_price'     => $pricing->total,
                'final_price'     => $pricing->total,
                'discount_amount' => 0,
                'notes'           => $data['notes'] ?? null,
            ]);

            AppointmentBooking::create([
                'booking_id'       => $booking->id,
                'slot_start'       => $data['slot_start'],
                'slot_end'         => $data['slot_end'],
                'duration_minutes' => $data['duration_minutes'],
            ]);

            $this->createBookingItems($booking, $data, $pricing);
            $this->applyPromoIfPresent($booking, $data);

            return [
                'booking'            => $booking->fresh(),
                'generated_password' => $password,
            ];
        });
    }

    /**
     * Create a subscription booking in a single transaction.
     */
    public function createSubscriptionBooking(array $data, PricingResult $pricing): array
    {
        return DB::transaction(function () use ($data, $pricing) {
            $client   = $this->findOrCreateClient($data);
            $password = $this->ensureClientPassword($client);

            $booking = Booking::create([
                'client_id'       => $client->id,
                'service_id'      => $data['service_id'],
                'package_id'      => $data['package_id'] ?? null,
                'name'            => $data['name'],
                'phone'           => $data['phone'],
                'email'           => $data['email'] ?? null,
                'booking_type'    => BookingType::SUBSCRIPTION->value,
                'status'          => BookingStatus::PENDING->value,
                'total_price'     => $pricing->total,
                'final_price'     => $pricing->total,
                'discount_amount' => 0,
                'notes'           => $data['notes'] ?? null,
            ]);

            $subscription = Subscription::create([
                'client_id'         => $client->id,
                'booking_id'        => $booking->id,
                'package_id'        => $data['package_id'] ?? null,
                'start_date'        => now(),
                'next_billing_date' => now()->addMonth(),
                'renewal_type'      => 'manual',
                'status'            => 'active',
            ]);

            SubscriptionBooking::create([
                'booking_id'      => $booking->id,
                'subscription_id' => $subscription->id,
                'billing_cycle'   => $data['billing_cycle'] ?? 'monthly',
                'plan_price'      => $pricing->total,
            ]);

            $this->createBookingItems($booking, $data, $pricing);
            $this->applyPromoIfPresent($booking, $data);

            return [
                'booking'            => $booking->fresh(),
                'generated_password' => $password,
            ];
        });
    }

    // ── Private helpers ──────────────────────────────────────────────

    /**
     * Assign a random plain-text password to the client if they don't have one yet.
     * Returns the plain-text password so it can be shown once, or null if already set.
     */
    private function ensureClientPassword(Client $client): ?string
    {
        if ($client->password) {
            return null;
        }

        $plain            = Str::random(10);
        $client->password = Hash::make($plain);
        $client->save();

        return $plain;
    }

    /**
     * Persist BookingItem rows for the package and any selected options.
     */
    private function createBookingItems(Booking $booking, array $data, PricingResult $pricing): void
    {
        if (!empty($data['package_name'])) {
            BookingItem::create([
                'booking_id'  => $booking->id,
                'item_type'   => 'package',
                'item_name'   => $data['package_name'],
                'item_id'     => $data['package_id'] ?? null,
                'quantity'    => 1,
                'unit_price'  => $pricing->base,
                'total_price' => $pricing->base,
                'snapshot'    => $data['package_snapshot'] ?? null,
            ]);
        }

        if (!empty($data['selected_options']) && is_array($data['selected_options'])) {
            foreach ($data['selected_options'] as $optionId => $qty) {
                $option = \App\Models\Service\PackageOption::find($optionId);
                if (!$option) {
                    continue;
                }

                $quantity  = max(1, (int) $qty);
                $unitPrice = (float) $option->price;
                $total     = $option->price_effect === 'per_unit'
                    ? $unitPrice * $quantity
                    : $unitPrice;

                BookingItem::create([
                    'booking_id'  => $booking->id,
                    'item_type'   => 'option',
                    'item_name'   => $option->label,
                    'item_id'     => $option->id,
                    'quantity'    => $quantity,
                    'unit_price'  => $unitPrice,
                    'total_price' => $total,
                    'snapshot'    => [
                        'price_effect'   => $option->price_effect,
                        'original_price' => $unitPrice,
                    ],
                ]);
            }
        }
    }

    /**
     * Apply a promo code to the booking if one was provided and is valid.
     */
    private function applyPromoIfPresent(Booking $booking, array $data): void
    {
        if (empty($data['promo_code'])) {
            return;
        }

        $result = $this->promoCodeService->validateAndApply(
            $data['promo_code'],
            (float) $booking->total_price
        );

        if ($result['valid']) {
            $booking->update([
                'promo_code_id'   => $result['promo_code_id'],
                'discount_amount' => $result['discount_amount'],
                'final_price'     => $result['final_price'],
            ]);
            $this->promoCodeService->incrementUsed($result['promo_code_id']);
        }
    }
}
