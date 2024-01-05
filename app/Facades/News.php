<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class News extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Infrastructures\News\News::class;
    }
}
