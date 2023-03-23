<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class TokenRepositoryServicesFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'TokenRepositoryServices';
    }
}
