<?php

namespace App\Enumerations;

class DocumentStatusEnums extends EnumerationAbstract
{
    const PARENT_ID = 18;

    const WAITING_FOR_VERIFICATION = 19;
    const NO_NEED_TO_VERIFY = 20;

    const VERIFYING_BY_AGENT = 21;
    const ACCEPTED_BY_AGENT = 22;
    const REJECTED_BY_AGENT = 23;

    const VERIFYING_BY_MANAGER = 24;
    const ACCEPTED_BY_MANAGER = 25;
    const REJECTED_BY_MANAGER = 26;

    public static function provideDataToSeed(): array
    {
        return [
            [
                'id' => self::PARENT_ID,
                'title' => 'انواع وضعیت های سند',
                'parent_id' => 0,
                'meta_data' => null,
            ],

            [
                'id' => self::WAITING_FOR_VERIFICATION,
                'title' => 'در انتظار بررسی',
                'parent_id' => self::PARENT_ID,
                'meta_data' => null,
            ],

            [
                'id' => self::NO_NEED_TO_VERIFY,
                'title' => 'عدم نیاز به بررسی',
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
