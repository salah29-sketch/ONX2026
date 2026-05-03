<?php

namespace App\Models\Client;

use App\Models\Booking\BookingPhoto;
use Illuminate\Database\Eloquent\Model;

class ClientSelectedPhoto extends Model
{
    protected $fillable = ['client_id', 'booking_photo_id'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function bookingPhoto()
    {
        return $this->belongsTo(BookingPhoto::class, 'booking_photo_id');
    }
}
