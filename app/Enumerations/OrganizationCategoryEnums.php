<?php

namespace App\Enumerations;

final class OrganizationCategoryEnums extends EnumerationAbstract
{
    const PARENT_ID = 4;

    const SCIENTIFIC = 5;
    const ENGINEERING = 6;
    const MANUFACTURE = 7;

    public static function provideDataToSeed(): array
    {
        return [
            [
                'id' => self::PARENT_ID,
                'title' => 'دسته بندی تشکل ها',
                'parent_id' => 0,
            ],

            [
                'id' => self::SCIENTIFIC,
                'title' => 'علمی',
                'parent_id' => self::PARENT_ID,
            ],

            [
                'id' => self::ENGINEERING,
                'title' => 'صنفی و مهندسی',
                'parent_id' => self::PARENT_ID,
            ],

            [
                'id' => self::MANUFACTURE,
                'title' => 'تولید',
                'parent_id' => self::PARENT_ID,
            ]
        ];
    }
}
