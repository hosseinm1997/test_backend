<?php

namespace App\Repositories;

use App\Models\Province;
use Infrastructure\Interfaces\ProvinceRepositoryInterface;

class ProvinceRepository implements ProvinceRepositoryInterface
{
    public function index()
    {
        return Province::query()->latest()->filtered()->sorted()->get();
    }
}
