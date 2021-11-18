<?php

namespace Database\Seeders;

use App\Models\Enumeration;
use Illuminate\Database\Seeder;
use App\Enumerations;

class EnumerationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $data = array_merge(
            // 1 - 3
            Enumerations\OrganizationTypeEnums::provideDataToSeed(),

            // 4 - 7
            Enumerations\OrganizationCategoryEnums::provideDataToSeed(),

            // 8 - 13 , 44 - 46
            Enumerations\OrganizationStatusEnums::provideDataToSeed(),

            // 14 - 17
            Enumerations\FileCategoryEnums::provideDataToSeed(),

            // 18 - 26
            Enumerations\DocumentStatusEnums::provideDataToSeed(),

            // 27 - 43
            Enumerations\DocumentTypeEnums::provideDataToSeed(),

            Enumerations\Ticket\TypeEnum::provideDataToSeed(),
        );

        Enumeration::query()->upsert($data, 'id');
    }
}
