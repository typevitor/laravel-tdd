<?php

namespace App\Repository;

use App\Domain\Entities\User;

class FakeUserRepository
{
    private array $users = array();

    public function __construct()
    {
        $this->users = [
            new User('1', 'Name'),
        ];
    }

    public function findById(string $id): User|null
    {
        foreach ($this->users as $user) {
            if ($user->getId() === $id) return $user;
        }

        return null;
    }
}
