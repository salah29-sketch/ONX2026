<?php

namespace App\Models\Content;

use App\Models\Booking\Booking;
use App\Models\Client\Client;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'client_id',
        'booking_id',
        'client_name',
        'client_role',
        'subtitle',
        'content',
        'rating',
        'initial',
        'sort_order',
        'is_active',
        'status',
    ];

    protected $casts = [
        'rating'    => 'integer',
        'is_active' => 'boolean',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }
}
