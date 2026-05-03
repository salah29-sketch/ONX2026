<?php

namespace App\Models\Booking;

use App\Models\Service\Service;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingCalendarSlot extends Model
{
    protected $fillable = [
        'service_id',
        'event_date',
        'booking_id',
    ];

    protected $casts = [
        'event_date' => 'date',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
