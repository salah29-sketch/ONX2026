<?php
namespace App\Models\Service;

use App\Enums\BookingType;
use App\Models\Booking\Booking;
use App\Models\Content\PortfolioItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    protected $fillable = [
        // ── معلومات أساسية
        'category_id',
        'name',
        'slug',
        'description',
        'icon',
        'hero_image',
        'capabilities',   // JSON — مميزات تظهر في الصفحة
        'sort_order',
        'is_active',

        // ── نوع الحجز (ما كان في Offer.pricing_type + Offer.type)
        'booking_type',          // BookingType enum: event | package | subscription
        'pricing_mode',          // 'fixed' | 'custom' | 'quote'
        'availability_required', // هل يحتاج تاريخ ومكان؟ (من Offer)

        // ── إعدادات الحجز بالوقت (للأحداث)
        'time_mode',
        'free_hours',
        'extra_hour_price',
        'early_start_price',
        'late_end_price',
        'default_start_time',
        'default_end_time',

        // ── إعدادات المكان
        'show_venue_selector',
        'show_wilaya_selector',

        // ── أسعار
        'deposit_amount',
        'base_price',
    ];

    protected $casts = [
        'is_active'              => 'boolean',
        'availability_required'  => 'boolean',
        'show_venue_selector'    => 'boolean',
        'show_wilaya_selector'   => 'boolean',
        'capabilities'           => 'array',
        'booking_type'           => BookingType::class,
        'extra_hour_price'       => 'decimal:2',
        'early_start_price'      => 'decimal:2',
        'late_end_price'         => 'decimal:2',
        'deposit_amount'         => 'decimal:2',
        'base_price'             => 'decimal:2',
    ];

    // ── Relationships ────────────────────────────────────────────────

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function packages(): HasMany
    {
        return $this->hasMany(Package::class)->orderBy('sort_order');
    }

    public function activePackages(): HasMany
    {
        return $this->hasMany(Package::class)
            ->where('is_active', true)
            ->orderBy('sort_order');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function portfolioItems(): HasMany
    {
        return $this->hasMany(PortfolioItem::class);
    }

    // ── Scopes ───────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOfType($query, BookingType $type)
    {
        return $query->where('booking_type', $type->value);
    }

    // ── Helpers ──────────────────────────────────────────────────────

    public function cap(string $key, mixed $default = null): mixed
    {
        return data_get($this->capabilities, $key, $default);
    }

    public function isEvent(): bool
    {
        return $this->booking_type === BookingType::EVENT;
    }

    public function isSubscription(): bool
    {
        return $this->booking_type === BookingType::SUBSCRIPTION;
    }

    public function needsCalendar(): bool
    {
        return $this->availability_required;
    }
}