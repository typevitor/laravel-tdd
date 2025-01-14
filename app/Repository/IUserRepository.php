<?php

namespace App\Repository;

use App\Domain\Entities\User;

interface IUserRepository
{
    public function findById(string $id): User|null;
    public function save(User $user): void;
}
