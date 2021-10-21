<?php

namespace App\Http\Controllers;

use Infrastructure\Interfaces\ProvinceRepositoryInterface;

class ProvinceController extends Controller
{
    public function index()
    {
        /* @var ProvinceRepositoryInterface $repositoryProvince  */
        $repositoryProvince = app(ProvinceRepositoryInterface::class);

        return $repositoryProvince->index();
    }
}
