<?php

namespace Carisma\Facades;

use Illuminate\Support\Facades\Facade;

class Carisma extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Carisma\Carisma::class;
    }
}