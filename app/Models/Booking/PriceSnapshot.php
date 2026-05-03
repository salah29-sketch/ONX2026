<?php

namespace App\Models\Booking;

use Illuminate\Database\Eloquent\Model;

class PriceSnapshot extends Model
{
    protected $fillable = ['booking_id', 'payload'];

    protected $casts = ['payload' => 'array'];
}