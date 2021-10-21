<?php

namespace App\Http\Controllers;

use Infrastructure\Interfaces\CityRepositoryInterface;

class CityController extends Controller
{
    public function getCitiesByProvinceId(int $provinceId)
    {
        /* @var CityRepositoryInterface $repositoryProvince  */
        $repositoryProvince = app(CityRepositoryInterface::class);

        return $repositoryProvince->show($provinceId);
    }
}
