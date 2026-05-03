<?php

namespace App\Enums;

enum CategoryType: string
{
    case EVENTS   = 'events';    // حفلات وفعاليات
    case ADS      = 'ads';       // إعلانات تجارية
    case CREATIVE = 'creative';  // إنتاج إبداعي

    public function label(): string
    {
        return match ($this) {
            self::EVENTS   => 'حفلات وفعاليات',
            self::ADS      => 'إعلانات',
            self::CREATIVE => 'إنتاج إبداعي',
        };
    }

    public function defaultBookingType(): BookingType
    {
        return match ($this) {
            self::EVENTS   => BookingType::EVENT,
            self::ADS      => BookingType::APPOINTMENT,
            self::CREATIVE => BookingType::APPOINTMENT,
        };
    }
}