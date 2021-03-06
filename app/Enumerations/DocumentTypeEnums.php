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
    const THREAD = 55;

    //news
    const NEWS = 165;

    public function getDirMapping(): array
    {
        return [
            self::NATIONAL_CARD_PICTURE => 'documents/users/identities',
            self::STATUTE_PICTURE => 'documents/organizations/identities',
            self::OFFICIAL_JOURNAL_PICTURE => 'documents/organizations/identities',
            self::SERVICE_INTRODUCTION_FORM => 'documents/organizations/identities',
            self::SECRETARY_INTRODUCTION_LETTER_PICTURE => 'documents/organizations/identities',
            self::REGISTRATION_CERTIFICATE => 'documents/organizations/identities',
            self::ACTIVITY_LICENSE => 'documents/organizations/identities',
            self::LOCATION_INFO_PICTURE => 'documents/organizations/identities',
            self::ORGANIZATION_MEMBERS => 'documents/organizations/introductions',
            self::ORGANIZATION_COMPANIES => 'documents/organizations/introductions',
            self::ORGANIZATION_BOOK => 'documents/organizations/introductions',
            self::ORGANIZATION_MAGAZINE => 'documents/organizations/introductions',
            self::ORGANIZATION_ARTICLE => 'documents/organizations/introductions',
            self::RESEARCH_COURSE => 'documents/organizations/introductions',
            self::MEETING_AND_SEMINAR => 'documents/organizations/introductions',
            self::INVENTION => 'documents/organizations/introductions',
            self::THREAD => 'documents/organizations/attachment/thread',
        ];
    }

    public function getMandatoryDocument(): array
    {
        return [
            DocumentTypeEnums::NATIONAL_CARD_PICTURE,
            DocumentTypeEnums::STATUTE_PICTURE,
            DocumentTypeEnums::OFFICIAL_JOURNAL_PICTURE,
            DocumentTypeEnums::SERVICE_INTRODUCTION_FORM,
            DocumentTypeEnums::SECRETARY_INTRODUCTION_LETTER_PICTURE,
            DocumentTypeEnums::REGISTRATION_CERTIFICATE,
            DocumentTypeEnums::ACTIVITY_LICENSE,
            DocumentTypeEnums::LOCATION_INFO_PICTURE,
        ];
    }

    public static function provideDataToSeed(): array
    {
        return [

            // mandatory
            [
                'id' => self::PARENT_ID,
                'title' => '?????????? ??????????',
                'parent_id' => 0,
                'meta_data' => null,
            ],

            [
                'id' => self::NATIONAL_CARD_PICTURE,
                'title' => '?????????? ???????? ??????',
                'parent_id' => self::PARENT_ID,
                'meta_data' => json_encode([
                    'optional' => false
                ])
            ],

            [
                'id' => self::STATUTE_PICTURE,
                'title' => '?????????? ???????? ????????',
                'parent_id' => self::PARENT_ID,
                'meta_data' => json_encode([
                    'optional' => false
                ])
            ],

            [
                'id' => self::OFFICIAL_JOURNAL_PICTURE,
                'title' => '?????????? ?????????? ?????????? ?????????????? ????????',
                'parent_id' => self::PARENT_ID,
                'meta_data' => json_encode([
                    'optional' => false
                ])
            ],

            [
                'id' => self::SERVICE_INTRODUCTION_FORM,
                'title' => '?????????? ?????? ?????????? ?????????? ?? ??????????????',
                'parent_id' => self::PARENT_ID,
                'meta_data' => json_encode([
                    'optional' => false
                ])
            ],

            [
                'id' => self::SECRETARY_INTRODUCTION_LETTER_PICTURE,
                'title' => '?????????? ?????????? ???????? ??????????????',
                'parent_id' => self::PARENT_ID,
                'meta_data' => json_encode([
                    'optional' => false
                ])
            ],

            [
                'id' => self::REGISTRATION_CERTIFICATE,
                'title' => '?????????? ?????????? ??????',
                'parent_id' => self::PARENT_ID,
                'meta_data' => json_encode([
                    'optional' => false
                ])
            ],

            [
                'id' => self::ACTIVITY_LICENSE,
                'title' => '?????????? ???????????? ????????????',
                'parent_id' => self::PARENT_ID,
                'meta_data' => json_encode([
                    'optional' => false
                ])
            ],

            [
                'id' => self::LOCATION_INFO_PICTURE,
                'title' => '?????????? ?????????????? ?????? ????????/??????????',
                'parent_id' => self::PARENT_ID,
                'meta_data' => json_encode([
                    'optional' => false
                ])
            ],

            // optional
            [
                'id' => self::ORGANIZATION_MEMBERS,
                'title' => '?????????? ??????????',
                'parent_id' => self::PARENT_ID,
                'meta_data' => json_encode([
                    'optional' => true
                ])
            ],

            [
                'id' => self::ORGANIZATION_COMPANIES,
                'title' => '???????? ?????? ??????????',
                'parent_id' => self::PARENT_ID,
                'meta_data' => json_encode([
                    'optional' => true
                ])
            ],

            [
                'id' => self::ORGANIZATION_BOOK,
                'title' => '???????? ??????????',
                'parent_id' => self::PARENT_ID,
                'meta_data' => json_encode([
                    'optional' => true
                ])
            ],

            [
                'id' => self::ORGANIZATION_MAGAZINE,
                'title' => '???????? ?? ???????????? ???????? ?? ??????????',
                'parent_id' => self::PARENT_ID,
                'meta_data' => json_encode([
                    'optional' => true
                ])
            ],

            [
                'id' => self::ORGANIZATION_ARTICLE,
                'title' => '???????????? ????????',
                'parent_id' => self::PARENT_ID,
                'meta_data' => json_encode([
                    'optional' => true
                ])
            ],

            [
                'id' => self::RESEARCH_COURSE,
                'title' => '???????? ?????? ????????????',
                'parent_id' => self::PARENT_ID,
                'meta_data' => json_encode([
                    'optional' => true
                ])
            ],

            [
                'id' => self::MEETING_AND_SEMINAR,
                'title' => '???????? ???? ?? ????????????????',
                'parent_id' => self::PARENT_ID,
                'meta_data' => json_encode([
                    'optional' => true
                ])
            ],

            [
                'id' => self::INVENTION,
                'title' => '????????????????',
                'parent_id' => self::PARENT_ID,
                'meta_data' => json_encode([
                    'optional' => true
                ])
            ],

        ];
    }
}
