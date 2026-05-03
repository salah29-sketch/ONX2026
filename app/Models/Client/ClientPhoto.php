<?php

namespace App\Models\Client;

use App\Models\Booking\Booking;
use Illuminate\Database\Eloquent\Model;

class ClientPhoto extends Model
{
    protected $fillable = ['client_id', 'booking_id', 'path'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
