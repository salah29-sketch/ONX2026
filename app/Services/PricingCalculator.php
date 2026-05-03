<?php

namespace App\Services;

use App\DTOs\PricingResult;
use App\Enums\BookingType;
use App\Models\Service\Package;
use App\Models\Service\PackageOption;
use App\Models\Service\Service;

class PricingCalculator
{
    public function __construct(
        protected TimeCostCalculator $timeCost,
        protected TravelCostCalculator $travelCost,
    ) {}

    // ── Event pricing ───────────────────────────────────────────────

    public function calculateEvent(
        Service $service,
        ?Package $package,
        array $selectedOptions,
        ?string $startTime,
        ?string $endTime,
        ?int $venueId,
        ?int $wilayaId,
    ): PricingResult {
        $base = $package ? (float) $package->price : (float) $service->base_price;
        $optionsCost = $this->calculateOptionsCost($selectedOptions);
        $timeCostAmount = ($startTime && $endTime)
            ? $this->timeCost->calculate($service, $startTime, $endTime)
            : 0.0;
        $travelCostAmount = $this->travelCost->calculate($venueId, $wilayaId);
        $subtotal = $base + $optionsCost;

        return new PricingResult(
            base: $base,
            optionsCost: $optionsCost,
            timeCost: $timeCostAmount,
            travelCost: $travelCostAmount,
            subtotal: $subtotal,
            total: $subtotal + $timeCostAmount + $travelCostAmount,
            deposit: (float) $service->deposit_amount,
        );
    }

    // ── Appointment pricing ─────────────────────────────────────────

    public function calculateAppointment(
        Service $service,
        ?Package $package,
    ): PricingResult {
        $base = $package
            ? (float) ($package->price ?? 0)
            : (float) $service->base_price;

        return new PricingResult(
            base: $base,
            optionsCost: 0,
            timeCost: 0,
            travelCost: 0,
            subtotal: $base,
            total: $base,
            deposit: (float) $service->deposit_amount,
        );
    }

    // ── Subscription pricing ────────────────────────────────────────

    public function calculateSubscription(
        ?Package $package,
    ): PricingResult {
        $base = $package ? (float) ($package->price ?? 0) : 0;

        return new PricingResult(
            base: $base,
            optionsCost: 0,
            timeCost: 0,
            travelCost: 0,
            subtotal: $base,
            total: $base,
            deposit: 0,
        );
    }

    // ── Backward-compatible calculate (used by old EventBookingForm) ─

    /**
     * @deprecated Use calculateEvent() instead
     */
    public function calculate(
        Service $service,
        ?Package $package,
        array $selectedOptions,
        ?string $startTime,
        ?string $endTime,
        ?int $venueId,
        ?int $wilayaId,
    ): array {
        return $this->calculateEvent(
            $service, $package, $selectedOptions,
            $startTime, $endTime, $venueId, $wilayaId,
        )->toArray();
    }

    // ── Options cost ────────────────────────────────────────────────

    /**
     * @param  array<int, int>  $selectedOptions  option_id => quantity
     */
    private function calculateOptionsCost(array $selectedOptions): float
    {
        if ($selectedOptions === []) {
            return 0.0;
        }

        $options = PackageOption::query()->whereIn('id', array_keys($selectedOptions))->get();

        return (float) $options->sum(function (PackageOption $opt) use ($selectedOptions) {
            $value = $selectedOptions[$opt->id] ?? null;
            return $opt->calculatePrice($value);
        });
    }
}
