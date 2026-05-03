<?php

namespace App\Enums;

enum BookingType: string
{
    case EVENT        = 'event';
    case APPOINTMENT  = 'appointment';
    case SUBSCRIPTION = 'subscription';

    public function label(): string
    {
        return match ($this) {
            self::EVENT        => 'حفلة / فعالية',
            self::APPOINTMENT  => 'موعد',
            self::SUBSCRIPTION => 'اشتراك',
        };
    }
}
