<?php

namespace App\Models\Subscription;

use Illuminate\Database\Eloquent\Model;

/**
 * سجل تجديد واحد لاشتراك (للتاريخ والمحاسبة)
 */
class SubscriptionRenewal extends Model
{
    protected $table = 'subscription_renewals';

    protected $fillable = [
        'subscription_id',
        'renewed_at',
        'next_billing_date',
        'renewal_type',
        'amount',
    ];

    protected $casts = [
        'renewed_at'         => 'datetime',
        'next_billing_date'  => 'date',
    ];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}
