<?php

namespace Tests\Unit\Application;

use App\Application\UserService;
use App\Domain\Entities\User;
use App\Models\User as ModelsUser;
use App\Repository\EloquentUserRepository;
use App\Repository\FakeUserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

describe('User Service', function () {

    beforeEach(function() {
        $fakeUserRepository = new FakeUserRepository();
        // $fakeUserRepository = new EloquentUserRepository();
        $this->userService = new UserService($fakeUserRepository);
    });

    it('should return null when invalid ID is given', function () {
        $user = $this->userService->findById('2');
        expect($user)->toBe(null);
    });

    it('should return user given valid ID', function () {
        $user = $this->userService->findById('1');
        expect($user)->toBeInstanceOf(User::class);
        expect($user->getId())->toBe('1');
        expect($user->getName())->toBe('Name');
    });

    it('should save a new user', function () {
        $user = new User('2', 'Name 2');
        $this->userService->save($user);
        $savedUser = $this->userService->findById('2');
        expect($savedUser)->toBeInstanceOf(User::class);
        expect($savedUser->getId())->toBe('2');
        expect($savedUser->getName())->toBe('Name 2');
    });
});
