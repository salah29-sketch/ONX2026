<?php

namespace App\DTOs;

class PricingResult
{
    public function __construct(
        public readonly float $base,
        public readonly float $optionsCost,
        public readonly float $timeCost,
        public readonly float $travelCost,
        public readonly float $subtotal,
        public readonly float $total,
        public readonly float $deposit,
    ) {}

    public function toArray(): array
    {
        return [
            'base'         => $this->base,
            'options_cost' => $this->optionsCost,
            'time_cost'    => $this->timeCost,
            'travel_cost'  => $this->travelCost,
            'subtotal'     => $this->subtotal,
            'total'        => $this->total,
            'deposit'      => $this->deposit,
        ];
    }
}
