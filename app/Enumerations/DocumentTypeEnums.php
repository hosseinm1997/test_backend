<?php

namespace App\Enumerations;

class DocumentTypeEnums extends EnumerationAbstract
{
    const PARENT_ID = 27;

    // mandatory
    const NATIONAL_CARD_PICTURE = 28;
    const STATUTE_PICTURE = 29;
    const OFFICIAL_JOURNAL_PICTURE = 30;
    const SERVICE_INTRODUCTION_FORM = 31;
    const SECRETARY_INTRODUCTION_LETTER_PICTURE = 32;
    const REGISTRATION_CERTIFICATE = 33;
    const ACTIVITY_LICENSE = 34;
    const LOCATION_INFO_PICTURE = 35;

    // optional
    const ORGANIZATION_MEMBERS = 36;
    const ORGANIZATION_COMPANIES = 37;
    const ORGANIZATION_BOOK = 38;
    const ORGANIZATION_MAGAZINE = 39;
    const ORGANIZATION_ARTICLE = 40;
    const RESEARCH_COURSE = 41;
    const MEETING_AND_SEMINAR = 42;
    const INVENTION = 43;


    public static function provideDataToSeed(): array
    {
        return [

            // mandatory
            [
                'id' => self::PARENT_ID,
                'title' => 'انواع سندها',
                'parent_id' => 0,
                'meta_data' => null,
            ],

            [
                'id' => self::NATIONAL_CARD_PICTURE,
                'title' => 'تصویر کارت ملی',
                'parent_id' => self::PARENT_ID,
                'meta_data' => json_encode([
                    'optional' => false
                ])
            ],

            [
                'id' => self::STATUTE_PICTURE,
                'title' => 'تصویر اساس نامه',
                'parent_id' => self::PARENT_ID,
                'meta_data' => json_encode([
                    'optional' => false
                ])
            ],

            [
                'id' => self::OFFICIAL_JOURNAL_PICTURE,
                'title' => 'تصویر آخرین تغییر روزنامه رسمی',
                'parent_id' => self::PARENT_ID,
                'meta_data' => json_encode([
                    'optional' => false
                ])
            ],

            [
                'id' => self::SERVICE_INTRODUCTION_FORM,
                'title' => 'فرم معرفی خدمات و اطلاعات',
                'parent_id' => self::PARENT_ID,
                'meta_data' => json_encode([
                    'optional' => false
                ])
            ],

            [
                'id' => self::SECRETARY_INTRODUCTION_LETTER_PICTURE,
                'title' => 'تصویر معرفی نامه نماینده',
                'parent_id' => self::PARENT_ID,
                'meta_data' => json_encode([
                    'optional' => false
                ])
            ],

            [
                'id' => self::REGISTRATION_CERTIFICATE,
                'title' => 'تصویر گواهی ثبت',
                'parent_id' => self::PARENT_ID,
                'meta_data' => json_encode([
                    'optional' => false
                ])
            ],

            [
                'id' => self::ACTIVITY_LICENSE,
                'title' => 'پروانه فعالیت',
                'parent_id' => self::PARENT_ID,
                'meta_data' => json_encode([
                    'optional' => false
                ])
            ],

            [
                'id' => self::LOCATION_INFO_PICTURE,
                'title' => 'تصویر اطلاعات محل تشکل/انجمن',
                'parent_id' => self::PARENT_ID,
                'meta_data' => json_encode([
                    'optional' => false
                ])
            ],

            // optional
            [
                'id' => self::ORGANIZATION_MEMBERS,
                'title' => 'اعضای انجمن',
                'parent_id' => self::PARENT_ID,
                'meta_data' => json_encode([
                    'optional' => true
                ])
            ],

            [
                'id' => self::ORGANIZATION_COMPANIES,
                'title' => 'شركت هاى انجمن',
                'parent_id' => self::PARENT_ID,
                'meta_data' => json_encode([
                    'optional' => true
                ])
            ],

            [
                'id' => self::ORGANIZATION_BOOK,
                'title' => 'کتاب انجمن',
                'parent_id' => self::PARENT_ID,
                'meta_data' => json_encode([
                    'optional' => true
                ])
            ],

            [
                'id' => self::ORGANIZATION_MAGAZINE,
                'title' => 'مجله و بروشور علمی و تخصصی',
                'parent_id' => self::PARENT_ID,
                'meta_data' => json_encode([
                    'optional' => true
                ])
            ],

            [
                'id' => self::ORGANIZATION_ARTICLE,
                'title' => 'مقالات علمی',
                'parent_id' => self::PARENT_ID,
                'meta_data' => json_encode([
                    'optional' => true
                ])
            ],

            [
                'id' => self::RESEARCH_COURSE,
                'title' => 'دوره های پژوهشی',
                'parent_id' => self::PARENT_ID,
                'meta_data' => json_encode([
                    'optional' => true
                ])
            ],

            [
                'id' => self::MEETING_AND_SEMINAR,
                'title' => 'نشست ها و سمینارها',
                'parent_id' => self::PARENT_ID,
                'meta_data' => json_encode([
                    'optional' => true
                ])
            ],

            [
                'id' => self::INVENTION,
                'title' => 'اختراعات',
                'parent_id' => self::PARENT_ID,
                'meta_data' => json_encode([
                    'optional' => true
                ])
            ],

        ];
    }
}