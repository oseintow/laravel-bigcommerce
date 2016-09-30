<?php

namespace Oseintow\Bigcommerce\Facades;

use Illuminate\Support\Facades\Facade;

class Bigcommerce extends Facade {

    protected static function getFacadeAccessor() { return 'bigcommerce'; }

}