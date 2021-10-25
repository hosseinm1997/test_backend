<?php

namespace App\Enumerations\Ticket;

class PriorityEnums
{
    const GROUP_ID = 50;

    const PRIORITY_LOW = 51;
    const PRIORITY_NORMAL = 52;
    const PRIORITY_EMERGENCY = 53;
    const PRIORITY_HIGH = 54;


    public static function getEnumPriority(): array
    {
        return [
            self::PRIORITY_LOW,
            self::PRIORITY_HIGH,
            self::PRIORITY_NORMAL,
            self::PRIORITY_EMERGENCY,
        ];
    }
}
