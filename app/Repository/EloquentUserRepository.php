<?php

namespace App\Repository;

use App\Domain\Entities\User;
use App\Models\User as ModelsUser;

class EloquentUserRepository implements IUserRepository
{
    public function __construct()
    {
        ModelsUser::factory()->create([
            'id' => '1',
            'name' => 'Name',
        ]);
    }

    public function findById(string $id): User|null
    {
        $modelUser = ModelsUser::find($id);
        if ($modelUser) {
            return new User(
                $modelUser->id,
                $modelUser->name,
            );
        }
        return null;
    }

    public function save(User $user): void
    {
        //
    }
}
