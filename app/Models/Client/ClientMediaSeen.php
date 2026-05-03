<?php

namespace App\Models\Client;

use App\Models\Booking\Booking;
use Illuminate\Database\Eloquent\Model;

class ClientMediaSeen extends Model
{
    protected $table = 'client_media_seen';

    protected $fillable = ['client_id', 'booking_id', 'seen_at'];

    protected $casts = [
        'seen_at' => 'datetime',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
