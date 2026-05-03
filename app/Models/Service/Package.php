<?php
namespace App\Models\Service;

use App\Models\Booking\Booking;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    protected $fillable = [
        'service_id',
        'name',
        'subtitle',        // من OfferPackage
        'description',
        'price',
        'old_price',       // من OfferPackage — سعر قبل الخصم
        'price_note',      // من OfferPackage — "حسب الطلب"
        'duration',        // مدة الباقة / صلاحيتها
        'features',        // JSON — قائمة المميزات
        'is_featured',     // من OfferPackage — باقة مميزة
        'is_buildable',    // هل تدعم Package Builder؟ (زواج فقط)
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'price'       => 'decimal:2',
        'old_price'   => 'decimal:2',
        'features'    => 'array',
        'is_featured' => 'boolean',
        'is_buildable'=> 'boolean',
        'is_active'   => 'boolean',
    ];

    // ── Relationships ────────────────────────────────────────────────

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(PackageOption::class)->orderBy('sort_order');
    }

    public function activeOptions(): HasMany
    {
        return $this->hasMany(PackageOption::class)
            ->where('is_active', true)
            ->orderBy('sort_order');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    // ── Scopes ───────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    // ── Helpers ──────────────────────────────────────────────────────

    public function priceDisplay(): string
    {
        if ($this->price > 0) {
            return number_format((float) $this->price);
        }
        return $this->price_note ?? 'حسب الطلب';
    }

    public function currencyLabel(): string
    {
        return $this->service?->isSubscription() ? 'DA / شهر' : 'DA';
    }

    public function fullPriceDisplay(): string
    {
        if ($this->price > 0) {
            return $this->priceDisplay() . ' ' . $this->currencyLabel();
        }
        return $this->price_note ?? 'حسب الطلب';
    }

    public function hasDiscount(): bool
    {
        return $this->old_price && $this->price < $this->old_price;
    }
}