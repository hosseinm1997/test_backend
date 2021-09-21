<?php


namespace App\Enumerations;


final class OrganizationStatusEnums extends EnumerationAbstract
{

    const PARENT_ID = 8;

    const WAITING_FOR_COMPLETION = 9;
    const WAITING_FOR_VERIFICATION = 10;
    const VERIFYING = 11;
    const ACCEPTED = 12;
    const REJECTED = 13;


    public static function provideDataToSeed(): array
    {
        return [
            [
                'id' => self::PARENT_ID,
                'title' => 'انواع وضعیت های انجمن',
                'parent_id' => 0,
            ],

            [
                'id' => self::WAITING_FOR_COMPLETION,
                'title' => 'در انتظار تکمیل مدارک',
                'parent_id' => self::PARENT_ID,
            ],

            [
                'id' => self::WAITING_FOR_VERIFICATION,
                'title' => 'در انتظار بررسی',
                'parent_id' => self::PARENT_ID,
            ],

            [
                'id' => self::VERIFYING,
                'title' => 'در حال بررسی',
                'parent_id' => self::PARENT_ID,
            ],

            [
                'id' => self::ACCEPTED,
                'title' => 'تایید شده',
                'parent_id' => self::PARENT_ID,
            ],

            [
                'id' => self::REJECTED,
                'title' => 'رد شده',
                'parent_id' => self::PARENT_ID,
            ],
        ];
    }
}
