<?php
namespace App\Models\Subscription;

use App\Models\Client\Client;
use App\Models\Booking\Booking;
use App\Models\Service\Package;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subscription extends Model
{
    protected $table = 'subscriptions';

    protected $fillable = [
        'client_id',
        'booking_id',
        'package_id',
        'start_date',
        'next_billing_date',
        'end_date',
        'renewal_type',
        'status',
        'plan_price',
        'used_ads',
        'cancelled_at',
    ];

    protected $casts = [
        'start_date'        => 'date',
        'next_billing_date' => 'date',
        'end_date'          => 'date',
        'cancelled_at'      => 'datetime',
        'plan_price'        => 'decimal:2',
    ];

    // -- Relationships --------------------------------------------

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function renewals(): HasMany
    {
        return $this->hasMany(SubscriptionRenewal::class)->orderByDesc('renewed_at');
    }

    // -- Scopes ---------------------------------------------------

    public function scopeManualPastDue(Builder $query): Builder
    {
        return $query->where('renewal_type', 'manual')
            ->where('status', 'active')
            ->where('next_billing_date', '<', now()->startOfDay());
    }

    public function scopeDueForAutoRenewal(Builder $query): Builder
    {
        return $query->where('renewal_type', 'automatic')
            ->where('status', 'active')
            ->where('next_billing_date', '<=', now()->startOfDay());
    }

    // -- Helpers --------------------------------------------------

    public function planName(): string
    {
        return $this->package?->name ?? '—';
    }

    public function renewalTypeLabel(): string
    {
        return match ($this->renewal_type ?? 'manual') {
            'automatic' => '??????',
            'manual'    => '????',
            default     => $this->renewal_type,
        };
    }

    public function statusLabel(): string
    {
        return match ($this->status ?? 'active') {
            'active'    => '???',
            'expired'   => '?????',
            'cancelled' => '????',
            default     => $this->status,
        };
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isRenewable(): bool
    {
        return $this->isActive();
    }
}
