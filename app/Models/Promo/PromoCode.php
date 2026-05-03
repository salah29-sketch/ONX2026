<?php

namespace App\Models\Promo;

use App\Models\Booking\Booking;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PromoCode extends Model
{
    protected $table = 'promo_codes';

    protected $fillable = [
        'code',
        'discount_type',
        'value',
        'min_order_value',
        'max_uses',
        'used_count',
        'valid_from',
        'valid_to',
        'is_active',
    ];

    protected $casts = [
        'value'            => 'decimal:2',
        'min_order_value'  => 'decimal:2',
        'valid_from'       => 'datetime',
        'valid_to'         => 'datetime',
        'is_active'        => 'boolean',
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'promo_code_id');
    }

    public function isValid(): bool
    {
        if (!$this->is_active) {
            return false;
        }
        if ($this->max_uses !== null && $this->used_count >= $this->max_uses) {
            return false;
        }
        $now = Carbon::now();
        if ($this->valid_from !== null && $now->lt($this->valid_from)) {
            return false;
        }
        if ($this->valid_to !== null && $now->gt($this->valid_to)) {
            return false;
        }
        return true;
    }

    public function meetsMinOrder(float $orderTotal): bool
    {
        if ($this->min_order_value === null || $this->min_order_value <= 0) {
            return true;
        }
        return $orderTotal >= (float) $this->min_order_value;
    }

    public function calculateDiscount(float $orderTotal): float
    {
        if ($this->discount_type === 'percent') {
            $discount = $orderTotal * ((float) $this->value / 100);
            // تقييد النسبة بين 0 و 100 لتفادي خصم سالب أو أكبر من الإجمالي
            return (float) round(max(0, min($discount, $orderTotal)), 2);
        }

        // خصم ثابت: لا يتجاوز الإجمالي ولا يكون سالبًا
        return (float) round(max(0, min((float) $this->value, $orderTotal)), 2);
    }
}
