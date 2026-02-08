<?php

namespace App\Enums;

enum BookingStatusEnum: string
{
    case Pending = 'pending';
    case Confirmed = 'confirmed';
    case Cancelled = 'cancelled';
    case Completed = 'completed';
    case Absent = 'absent'; // неявка: не отменил бронь, не пришёл (для чёрного списка убыточных игроков)

    /**
     * Статусы, при которых бронь считается активной (сеанс занят).
     */
    public static function activeValues(): array
    {
        return [
            self::Pending->value,
            self::Confirmed->value,
        ];
    }
}
