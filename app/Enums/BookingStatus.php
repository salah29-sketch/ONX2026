<?php

namespace App\Enums;

enum BookingStatus: string
{
    case PENDING     = 'pending';
    case CONFIRMED   = 'confirmed';
    case ASSIGNED    = 'assigned';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED   = 'completed';
    case CANCELLED   = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::PENDING     => 'قيد الانتظار',
            self::CONFIRMED   => 'مؤكد',
            self::ASSIGNED    => 'تم التعيين',
            self::IN_PROGRESS => 'قيد التنفيذ',
            self::COMPLETED   => 'مكتمل',
            self::CANCELLED   => 'ملغى',
        };
    }

    public function step(): int
    {
        return match ($this) {
            self::PENDING     => 1,
            self::CONFIRMED   => 2,
            self::ASSIGNED    => 3,
            self::IN_PROGRESS => 4,
            self::COMPLETED   => 5,
            self::CANCELLED   => 0,
        };
    }

    /** قيم الحالات التي تُعدّ حجزًا نشطًا (تمنع التاريخ) */
    public static function activeValues(): array
    {
        return [
            self::PENDING->value,
            self::CONFIRMED->value,
            self::ASSIGNED->value,
            self::IN_PROGRESS->value,
        ];
    }

    /** قيم الحالات المقبولة في validation */
    public static function validationValues(): string
    {
        return implode(',', array_column(self::cases(), 'value'));
    }
}
