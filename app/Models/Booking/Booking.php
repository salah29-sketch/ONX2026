<?php

namespace App\Models\Booking;

use App\Enums\BookingStatus;
use App\Enums\BookingType;
use App\Models\Client\Client;
use App\Models\Promo\PromoCode;
use App\Models\Service\Package;
use App\Models\Service\Service;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'service_id',
        'package_id',
        'booking_type',
        'name',
        'phone',
        'email',
        'event_date',
        'business_name',
        'budget',
        'deadline',
        'notes',
        'status',
        'final_video_path',
        'total_price',
        'promo_code_id',
        'discount_amount',
        'final_price',
    ];

    protected $casts = [
        'event_date'      => 'date',
        'deadline'        => 'date',
        'budget'          => 'decimal:2',
        'total_price'     => 'decimal:2',
        'final_price'     => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'booking_type'    => BookingType::class,
        'status'          => BookingStatus::class,
    ];

    // ── Relations ────────────────────────────────────────────────

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function promoCode(): BelongsTo
    {
        return $this->belongsTo(PromoCode::class);
    }

    public function priceSnapshot(): HasOne
    {
        return $this->hasOne(PriceSnapshot::class);
    }

    public function eventBooking(): HasOne
    {
        return $this->hasOne(EventBooking::class);
    }

    public function appointmentBooking(): HasOne
    {
        return $this->hasOne(AppointmentBooking::class);
    }

    public function subscriptionBooking(): HasOne
    {
        return $this->hasOne(SubscriptionBooking::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(BookingItem::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(BookingPhoto::class)->orderBy('sort_order');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(BookingPayment::class)->orderBy('created_at');
    }

    public function files(): HasMany
    {
        return $this->hasMany(BookingFile::class)->orderBy('created_at');
    }

    public function visibleFiles(): HasMany
    {
        return $this->hasMany(BookingFile::class)
            ->where('is_visible', true)
            ->orderBy('created_at');
    }

    // ── Payment helpers ──────────────────────────────────────────

    public function paidAmount(): float
    {
        return (float) $this->payments()->sum('amount');
    }

    public function remainingAmount(): float
    {
        $total = $this->final_price ?? $this->total_price;
        if (! $total) return 0;
        return max(0, (float) $total - $this->paidAmount());
    }

    public function paymentPercent(): int
    {
        $total = $this->final_price ?? $this->total_price;
        if (! $total || $total <= 0) return 0;
        return min(100, (int) round(($this->paidAmount() / (float) $total) * 100));
    }

    public function isFullyPaid(): bool
    {
        $total = $this->final_price ?? $this->total_price;
        return $total && $this->paidAmount() >= (float) $total;
    }

    public function displayPrice(): float
    {
        return (float) ($this->final_price ?? $this->total_price ?? 0);
    }

    // ── Type helpers ─────────────────────────────────────────────

    public function isEvent(): bool
    {
        return $this->booking_type === BookingType::EVENT;
    }

    public function isAppointment(): bool
    {
        return $this->booking_type === BookingType::APPOINTMENT;
    }

    public function isSubscription(): bool
    {
        return $this->booking_type === BookingType::SUBSCRIPTION;
    }

    // ── Status helpers ───────────────────────────────────────────

    public function statusLabel(): string
    {
        return $this->status?->label() ?? '—';
    }

    public function statusStep(): int
    {
        return $this->status?->step() ?? 0;
    }

    public function projectTypeLabel(): string
    {
        return $this->service?->name ?? '—';
    }

    /**
     * نص معلومة التسليم حسب حالة الحجز
     */
    public function deliveryInfoText(): string
    {
        return match ($this->status) {
            BookingStatus::PENDING     => 'حجزك قيد المراجعة. سنتواصل معك قريبا للتأكيد.',
            BookingStatus::CONFIRMED   => 'تم تأكيد حجزك! سنتواصل معك قبل الموعد لتنسيق التفاصيل.',
            BookingStatus::ASSIGNED    => 'تم تعيين فريق العمل. جاري التحضير لموعدك.',
            BookingStatus::IN_PROGRESS => 'مشروعك قيد التنفيذ. سيتم تسليم الملفات عند الانتهاء.',
            BookingStatus::COMPLETED   => 'تم إكمال مشروعك! يمكنك تحميل ملفاتك من قسم الوسائط.',
            BookingStatus::CANCELLED   => 'تم إلغاء هذا الحجز.',
            default                    => 'تابع حالة حجزك من هذه الصفحة.',
        };
    }


    public function subscription(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(\App\Models\Subscription\Subscription::class);
    }


    /**
     * هل هذا الحجز اشتراك شهري
     */
    public function isMonthlySubscription(): bool
    {
        return $this->isSubscription();
    }

}
