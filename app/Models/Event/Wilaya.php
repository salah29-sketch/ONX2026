<?php
namespace App\Models\Event;

use Illuminate\Database\Eloquent\Model;

class Wilaya extends Model
{
    protected $fillable = ['name', 'code', 'travel_zone_id', 'is_local'];
    protected $casts = ['is_local' => 'boolean'];

    public function travelZone()
    {
        return $this->belongsTo(TravelZone::class);
    }

    public function venues()
    {
        return $this->hasMany(Venue::class);
    }
}