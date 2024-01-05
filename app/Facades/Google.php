<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Google extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Infrastructures\Google\Google::class;
    }
}
