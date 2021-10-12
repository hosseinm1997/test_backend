<?php

namespace App\Enumerations;

final class OrganizationTypeEnums extends EnumerationAbstract
{
    const PARENT_ID = 1;

    // انجمن
    const COMMUNITY = 2;
    // کانون
    const CLUB = 3;

    public static function provideDataToSeed(): array
    {
        return [
            [
                'id' => self::PARENT_ID,
                'title' => 'انواع تشکل',
                'parent_id' => 0,
                'meta_data' => null,
            ],

            [
                'id' => self::COMMUNITY,
                'title' => 'انجمن',
                'parent_id' => self::PARENT_ID,
                'meta_data' => null,
            ],

            [
                'id' => self::CLUB,
                'title' => 'کانون',
                'parent_id' => self::PARENT_ID,
                'meta_data' => null,
            ]
        ];
    }
}
