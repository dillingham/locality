<?php

namespace Dillingham\Locality\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Dillingham\Locality\Locality
 */
class Locality extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'locality';
    }
}
