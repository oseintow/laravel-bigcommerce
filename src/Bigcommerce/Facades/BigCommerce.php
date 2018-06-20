<?php

namespace VerveCommerce\Bigcommerce\Facades;

use Illuminate\Support\Facades\Facade;

class BigCommerce extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'bigcommerce';
    }
}
