<?php

namespace App\Models\Booking;

use Illuminate\Database\Eloquent\Model;

/**
 * @property bool \
 * @property string|null \
 * @property string|null \
 */
class BookingFile extends Model
{
    protected $fillable = [
        'booking_id',
        'label',
        'path',
        'thumbnail_path',
        'poster_path',
        'type',
        'size',
        'is_visible',
    ];

    protected $casts = [
        'is_visible' => 'boolean',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function humanSize(): string
    {
        if (!$this->size) return '';
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        $s = (float) $this->size;
        while ($s >= 1024 && $i < count($units) - 1) {
            $s /= 1024;
            $i++;
        }
        return round($s, 1) . ' ' . $units[$i];
    }

    public function typeIcon(): string
    {
        return match ($this->type) {
            'video' => 'bi-camera-video-fill',
            'zip'   => 'bi-file-zip-fill',
            'pdf'   => 'bi-file-pdf-fill',
            default => 'bi-file-earmark-fill',
        };
    }

    public function typeColor(): string
    {
        return match ($this->type) {
            'video' => '#8b5cf6',
            'zip'   => '#f59e0b',
            'pdf'   => '#ef4444',
            default => '#6b7280',
        };
    }

    public function thumbnailUrl(): ?string
    {
        if ($this->thumbnail_path) {
            return str_starts_with($this->thumbnail_path, 'http') ? $this->thumbnail_path : asset($this->thumbnail_path);
        }
        return null;
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
