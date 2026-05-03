<?php

namespace App\Models\Booking;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppointmentBooking extends Model
{
    protected $fillable = [
        'booking_id',
        'slot_start',
        'slot_end',
        'duration_minutes',
    ];

    protected $casts = [
        'duration_minutes' => 'integer',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
