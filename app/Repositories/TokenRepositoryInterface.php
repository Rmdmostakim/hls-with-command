<?php

namespace App\Repositories;

interface TokenRepositoryInterface
{
    public function create($payload);
    public function decode($token);
}
