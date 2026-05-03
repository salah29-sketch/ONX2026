<?php

namespace App\Services;

use App\Models\Event\Venue;
use App\Models\Event\Wilaya;

class TravelCostCalculator
{
    public function calculate(?int $venueId, ?int $wilayaId): float
    {
        if ($venueId) {
            $venue = Venue::query()->with('wilaya.travelZone')->find($venueId);
            if ($venue && $venue->travel_cost_override !== null) {
                return (float) $venue->travel_cost_override;
            }
            if ($wilayaId === null && $venue?->wilaya_id) {
                $wilayaId = $venue->wilaya_id;
            }
        }

        if ($wilayaId) {
            $wilaya = Wilaya::query()->with('travelZone')->find($wilayaId);
            if ($wilaya?->is_local) {
                return 0.0;
            }

            return (float) ($wilaya?->travelZone?->price ?? 0);
        }

        return 0.0;
    }
}
