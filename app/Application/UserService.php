<?php

namespace App\Application;

use App\Domain\Entities\User;
use App\Repository\IUserRepository;

class UserService
{
    public function __construct(private readonly IUserRepository $iUserRepository)
    {
    }

    public function findById(string $id): User|null
    {
        return $this->iUserRepository->findById($id);
    }

    public function save(User $user): void
    {
        $this->iUserRepository->save($user);
    }
}
