<?php

namespace App\Repositories;

use App\Models\City;
use Infrastructure\Interfaces\CityRepositoryInterface;

class CityRepository implements CityRepositoryInterface
{
    public function show(int $provinceId)
    {
        return City::query()->where('province_id' ,$provinceId)->get();
    }
}
