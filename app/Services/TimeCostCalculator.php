<?php

namespace App\Services;

use App\Enums\TimeMode;
use App\Models\Service\Service;
use Carbon\Carbon;

class TimeCostCalculator
{
    public function calculate(Service $service, string $startTime, string $endTime): float
    {
        $mode = $service->time_mode instanceof TimeMode
            ? $service->time_mode
            : TimeMode::tryFrom((string) $service->time_mode) ?? TimeMode::Standard;

        return match ($mode) {
            TimeMode::Wedding => $this->calculateWedding($service, $startTime, $endTime),
            TimeMode::Standard => $this->calculateStandard($service, $startTime, $endTime),
        };
    }

    /**
     * @return array{0: Carbon, 1: Carbon}
     */
    private function parseClientWindow(string $start, string $end): array
    {
        $base = Carbon::create(2000, 1, 1, 0, 0, 0);
        $startC = $base->copy()->setTimeFromTimeString($start);
        $endC = $base->copy()->setTimeFromTimeString($end);
        if ($endC->lte($startC)) {
            $endC->addDay();
        }

        return [$startC, $endC];
    }

    private function calculateWedding(Service $service, string $start, string $end): float
    {
        [$clientStart, $clientEnd] = $this->parseClientWindow($start, $end);

        $defaultStartStr = $service->default_start_time
            ? Carbon::parse($service->default_start_time)->format('H:i')
            : '19:00';
        $defaultEndStr = $service->default_end_time
            ? Carbon::parse($service->default_end_time)->format('H:i')
            : '04:00';

        $base = Carbon::create(2000, 1, 1, 0, 0, 0);
        $defaultStart = $base->copy()->setTimeFromTimeString($defaultStartStr);
        $defaultEnd = $base->copy()->addDay()->setTimeFromTimeString($defaultEndStr);

        $cost = 0.0;

        if ($clientStart->lt($defaultStart)) {
            $hoursEarly = (int) ceil(abs($clientStart->diffInHours($defaultStart)));
            $cost += $hoursEarly * (float) $service->early_start_price;
        }

        if ($clientEnd->gt($defaultEnd)) {
            $minutesLate = abs($clientEnd->diffInMinutes($defaultEnd));
            $blocks = (int) ceil($minutesLate / 30);
            $cost += $blocks * (float) $service->late_end_price;
        }

        return $cost;
    }

    private function calculateStandard(Service $service, string $start, string $end): float
    {
        [$startC, $endC] = $this->parseClientWindow($start, $end);

        $durationHours = $startC->diffInMinutes($endC) / 60.0;
        $free = (int) $service->free_hours;

        if ($durationHours <= $free) {
            return 0.0;
        }

        $extraHours = $durationHours - $free;

        return (int) ceil($extraHours) * (float) $service->extra_hour_price;
    }
}
