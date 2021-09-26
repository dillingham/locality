<?php

namespace Dillingham\Locality;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Dillingham\Locality\Locality
 */
class LocalityFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'locality';
    }
}
