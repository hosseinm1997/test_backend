<?php

namespace App\Enumerations;

abstract class EnumerationAbstract
{
    abstract public static function provideDataToSeed(): array;
}
