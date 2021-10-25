<?php

namespace App\Enumerations\Ticket;

final class TypeEnum
{
    const GROUP_ID = 56;

    const PEOPLE = 58;
    const ORGANIZATION = 57;
    const MANAGEMENT = 59;

    public static function getAllEnumType(): array
    {
        return [
            self::PEOPLE,
            self::MANAGEMENT,
            self::ORGANIZATION,
        ];
    }
}
