<?php

namespace App\Models\Booking;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingAccessToken extends Model
{
    public const PURPOSE_CONFIRMATION = 'confirmation';

    public const PURPOSE_PASSWORD_SETUP = 'password_setup';

    protected $fillable = [
        'booking_id',
        'token_hash',
        'purpose',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
