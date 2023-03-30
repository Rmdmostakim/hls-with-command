<?php

namespace App\Services;

use App\Repositories\LearnRepositoryInterface;


class LearnRepositoryServices implements LearnRepositoryInterface
{
    public function test()
    {
        return "ok";
    }
}
