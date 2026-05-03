<?php
namespace App\Models\Event;

use Illuminate\Database\Eloquent\Model;

class TravelZone extends Model
{
    protected $fillable = ['name', 'price', 'sort_order'];
    protected $casts = ['price' => 'decimal:2'];

    public function wilayas()
    {
        return $this->hasMany(Wilaya::class);
    }
}