<?php

namespace App\Enumerations;

class FileCategoryEnums extends EnumerationAbstract
{
    const PARENT_ID = 14;

    const DOCUMENT = 15;
    const THREAD_ATTACHMENT = 16;
    const NEWS_MEDIA = 17;
    const ORGANIZATION_ATTRIBUTES = 60;

    public static function provideDataToSeed(): array
    {
        return [
            [
                'id' => self::PARENT_ID,
                'title' => 'انواع دسته بندی فایل ها',
                'parent_id' => 0,
                'meta_data' => null,
            ],

            [
                'id' => self::DOCUMENT,
                'title' => 'سند',
                'parent_id' => self::PARENT_ID,
                'meta_data' => null,
            ],

            [
                'id' => self::THREAD_ATTACHMENT,
                'title' => 'پیوست نامه',
                'parent_id' => self::PARENT_ID,
                'meta_data' => null,
            ],

            [
                'id' => self::NEWS_MEDIA,
                'title' => 'عکس و ویدیو خبر',
                'parent_id' => self::PARENT_ID,
                'meta_data' => null,
            ],

            [
                'id' => self::ORGANIZATION_ATTRIBUTES,
                'title' => 'مشخصات  فایلی انجمن',
                'parent_id' => self::PARENT_ID,
                'meta_data' => null,
            ]
        ];
    }
}
