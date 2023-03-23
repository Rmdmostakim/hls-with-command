<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class FeedRepositoryServicesFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'FeedRepositoryServices';
    }
}
