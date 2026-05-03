<?php

namespace App\Models\Booking;

use App\Models\Event\Venue;
use App\Models\Event\Wilaya;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventBooking extends Model
{
    protected $fillable = [
        'booking_id',
        'start_time',
        'end_time',
        'venue_id',
        'venue_custom',
        'wilaya_id',
        'time_cost',
        'travel_cost',
        'late_fee',
    ];

    protected $casts = [
        'time_cost'   => 'decimal:2',
        'travel_cost' => 'decimal:2',
        'late_fee'    => 'decimal:2',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    public function wilaya(): BelongsTo
    {
        return $this->belongsTo(Wilaya::class);
    }

    public function venueName(): string
    {
        if ($this->venue_custom) {
            return $this->venue_custom;
        }

        return $this->venue?->name ?? '—';
    }
}
