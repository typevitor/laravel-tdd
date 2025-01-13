<?php

namespace App\Repository;

use App\Domain\Entities\User;

interface IUserRepository
{
    public function findById(string $id): User|null;
}
