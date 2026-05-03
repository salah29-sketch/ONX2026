<?php
namespace App\Models\Event;

use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    protected $fillable = [
        'name', 'wilaya_id', 'address',
        'travel_cost_override', 'is_active', 'sort_order'
    ];
    protected $casts = [
        'is_active' => 'boolean',
        'travel_cost_override' => 'decimal:2',
    ];

    public function wilaya()
    {
        return $this->belongsTo(Wilaya::class);
    }

    public function getEffectiveTravelCost(): float
    {
        if ($this->travel_cost_override !== null) {
            return (float) $this->travel_cost_override;
        }
        return (float) ($this->wilaya?->travelZone?->price ?? 0);
    }
}