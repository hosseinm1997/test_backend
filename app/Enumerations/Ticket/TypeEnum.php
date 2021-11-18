<?php

namespace App\Enumerations\Ticket;

final class TypeEnum
{
    const PARENT_ID = 56;

    const PEOPLE = 58;
    const ORGANIZATION = 57;
    const MANAGEMENT = 59;
    const DEVELOP_MANAGEMENT = 65;

    public static function getAllEnumType(): array
    {
        return [
            self::PEOPLE,
            self::MANAGEMENT,
            self::ORGANIZATION,
            self::DEVELOP_MANAGEMENT,
        ];
    }

    public static function provideDataToSeed()
    {
        return [
            [
                'id' => self::PARENT_ID,
                'title' => 'نوع ارسال و دریافت تیکت',
                'parent_id' => 0,
                'meta_data' => null,
            ],
            [
                'id' => self::PEOPLE,
                'title' => 'مردم',
                'parent_id' => self::PARENT_ID,
                'meta_data' => null,
            ],
            [
                'id' => self::MANAGEMENT,
                'title' => 'مدیریت',
                'parent_id' => self::PARENT_ID,
                'meta_data' => null,
            ],
            [
                'id' => self::DEVELOP_MANAGEMENT,
                'title' => 'مدیریت توسعه',
                'parent_id' => self::PARENT_ID,
                'meta_data' => null,
            ],
            [
                'id' => self::ORGANIZATION,
                'title' => 'بخش',
                'parent_id' => self::PARENT_ID,
                'meta_data' => null,
            ]
        ];
    }
}
