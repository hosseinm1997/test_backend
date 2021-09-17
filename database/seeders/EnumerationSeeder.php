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
            Enumerations\OrganizationTypeEnums::provideDataToSeed(),
            Enumerations\OrganizationCategoryEnums::provideDataToSeed(),
            Enumerations\OrganizationStatusEnums::provideDataToSeed(),
        );

        Enumeration::query()->insert($data);
    }
}
