<?php
namespace App\Models\Service;

use App\Enums\CategoryType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Category extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'type',
        'icon', 'bg_color', 'sort_order', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'type'      => CategoryType::class,
    ];

    // ── Relationships ────────────────────────────────────────────────

    public function services(): HasMany
    {
        return $this->hasMany(Service::class)->orderBy('sort_order');
    }

    public function activeServices(): HasMany
    {
        return $this->hasMany(Service::class)
            ->where('is_active', true)
            ->orderBy('sort_order');
    }

    // ── Scopes ──────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOfType($query, CategoryType $type)
    {
        return $query->where('type', $type->value);
    }
}