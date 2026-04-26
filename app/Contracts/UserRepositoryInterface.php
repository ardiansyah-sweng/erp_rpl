<?php

namespace App\Contracts;

interface UserRepositoryInterface
{
    public function findById(int $id): ?array;
}
