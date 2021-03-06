<?php


namespace App\Enumerations;


final class OrganizationStatusEnums extends EnumerationAbstract
{

    const PARENT_ID = 8;

    const WAITING_FOR_COMPLETION = 9;
    const WAITING_FOR_VERIFICATION = 10;

    const VERIFYING_BY_AGENT = 11;
    const ACCEPTED_BY_AGENT = 12;
    const REJECTED_BY_AGENT = 13;

    const VERIFYING_BY_MANAGER = 44;
    const ACCEPTED_BY_MANAGER = 45;
    const REJECTED_BY_MANAGER = 46;


    public static function provideDataToSeed(): array
    {
        return [
            [
                'id' => self::PARENT_ID,
                'title' => 'انواع وضعیت های انجمن',
                'parent_id' => 0,
                'meta_data' => null,
            ],

            [
                'id' => self::WAITING_FOR_COMPLETION,
                'title' => 'در انتظار تکمیل مدارک',
                'parent_id' => self::PARENT_ID,
                'meta_data' => null,
            ],

            [
                'id' => self::WAITING_FOR_VERIFICATION,
                'title' => 'در انتظار بررسی',
                'parent_id' => self::PARENT_ID,
                'meta_data' => null,
            ],

            [
                'id' => self::VERIFYING_BY_AGENT,
                'title' => 'در حال بررسی توسط کارشناس',
                'parent_id' => self::PARENT_ID,
                'meta_data' => null,
            ],

            [
                'id' => self::ACCEPTED_BY_AGENT,
                'title' => 'تایید شده توسط کارشناس',
                'parent_id' => self::PARENT_ID,
                'meta_data' => null,
            ],

            [
                'id' => self::REJECTED_BY_AGENT,
                'title' => 'رد شده توسط کارشناس',
                'parent_id' => self::PARENT_ID,
                'meta_data' => null,
            ],

            [
                'id' => self::VERIFYING_BY_MANAGER,
                'title' => 'در حال بررسی توسط مدیر',
                'parent_id' => self::PARENT_ID,
                'meta_data' => null,
            ],

            [
                'id' => self::ACCEPTED_BY_MANAGER,
                'title' => 'تایید شده توسط مدیر',
                'parent_id' => self::PARENT_ID,
                'meta_data' => null,
            ],

            [
                'id' => self::REJECTED_BY_MANAGER,
                'title' => 'رد شده توسط مدیر',
                'parent_id' => self::PARENT_ID,
                'meta_data' => null,
            ],
        ];
    }
}
