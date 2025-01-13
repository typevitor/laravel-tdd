<?php

namespace App\Repository;

use App\Domain\Entities\User;
use App\Models\User as ModelsUser;

class EloquentUserRepository implements IUserRepository
{
    public function __construct()
    {
        ModelsUser::create([
            'id' => '1',
            'name' => 'Vitor',
            'email' => 'email@test.com',
            'password' => 'password'
        ]);
    }

    public function findById(string $id): User|null
    {
        $modelUser = ModelsUser::findById($id);
        if ($modelUser) {
            return new User(
                $modelUser->id,
                $modelUser->name,
            );
        }
        return null;
    }
}
