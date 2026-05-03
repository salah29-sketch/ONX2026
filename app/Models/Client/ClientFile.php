<?php

namespace App\Models\Client;

use App\Models\Booking\Booking;
use Illuminate\Database\Eloquent\Model;

class ClientFile extends Model
{
    protected $fillable = [
        'client_id',
        'booking_id',
        'type',
        'path',
        'thumbnail_path',
        'poster_path',
        'label',
        'size',
        'sort_order',
        'is_visible',
    ];

    protected $casts = [
        'is_visible' => 'boolean',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function thumbnailUrl(): ?string
    {
        if ($this->thumbnail_path) {
            return str_starts_with($this->thumbnail_path, 'http') ? $this->thumbnail_path : asset($this->thumbnail_path);
        }
        return $this->type === 'image' ? asset($this->path) : null;
    }

    public function posterUrl(): ?string
    {
        if ($this->poster_path) {
            return str_starts_with($this->poster_path, 'http') ? $this->poster_path : asset($this->poster_path);
        }
        return null;
    }

    public function fileUrl(): string
    {
        return str_starts_with($this->path, 'http') ? $this->path : asset($this->path);
    }
}
