<?php

namespace App\Models\Booking;

use App\Models\Subscription\Subscription;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubscriptionBooking extends Model
{
    protected $fillable = [
        'booking_id',
        'subscription_id',
        'billing_cycle',
        'plan_price',
    ];

    protected $casts = [
        'plan_price' => 'decimal:2',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }
}
