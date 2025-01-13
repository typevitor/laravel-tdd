<?php

namespace App\Application;

use App\Domain\Entities\User;
use App\Repository\FakeUserRepository;

class UserService
{
    public function __construct(private readonly FakeUserRepository $userRepository)
    {
    }

    public function findById(string $id): User|null
    {
        return $this->userRepository->findById($id);
    }
}
