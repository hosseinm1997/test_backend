<?php

namespace Infrastructure\Interfaces;

interface CityRepositoryInterface
{
    public function show(int $provinceId);
}
