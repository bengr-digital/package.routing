<?php

namespace Bengr\Routing\Facades;

use Illuminate\Support\Facades\Facade;

class Routing extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "routing";
    }
}
