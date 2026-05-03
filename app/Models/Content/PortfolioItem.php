<?php

namespace App\Models\Content;

use App\Models\Service\Category;
use App\Models\Service\Service;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class PortfolioItem extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'category_id',
        'service_id',
        'service_type',
        'category',
        'media_type',
        'image_path',
        'youtube_url',
        'youtube_video_id',
        'preview_video_path',
        'caption',
        'description',
        'client_name',
        'location_name',
        'is_featured',
        'is_active',
        'is_reel',
        'reel_source',
        'reel_url',
        'video_path',
        'sort_order',
        'published_at',
    ];

    protected $casts = [
        'is_featured'  => 'boolean',
        'is_active'    => 'boolean',
        'is_reel'      => 'boolean',
        'published_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $item) {
            if (empty($item->slug) && !empty($item->title)) {
                $item->slug = Str::slug($item->title);
            }
            if ($item->media_type === 'youtube' && !empty($item->youtube_url)) {
                $item->youtube_video_id = self::extractYoutubeVideoId($item->youtube_url);
            }
            if ($item->media_type !== 'youtube') {
                $item->youtube_url      = null;
                $item->youtube_video_id = null;
            }
        });
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function categoryRelation(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function getYoutubeThumbnailAttribute(): ?string
    {
        if (!$this->youtube_video_id) return null;
        return 'https://img.youtube.com/vi/' . $this->youtube_video_id . '/hqdefault.jpg';
    }

    public static function extractYoutubeVideoId(?string $url): ?string
    {
        if (empty($url)) return null;
        $patterns = [
            '/youtube\.com\/watch\?v=([^\&\?\/]+)/',
            '/youtube\.com\/embed\/([^\&\?\/]+)/',
            '/youtu\.be\/([^\&\?\/]+)/',
            '/youtube\.com\/shorts\/([^\&\?\/]+)/',
        ];
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return $matches[1] ?? null;
            }
        }
        return null;
    }
}