<?php

namespace Tests\Unit\Application;

use App\Domain\Entities\User;
use App\Repository\EloquentUserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

describe('User Eloquent Service', function () {

    beforeEach(function () {
        $this->fakeUserRepository = new EloquentUserRepository();
    });

    it('should return null when invalid ID is given', function () {
        $user = $this->fakeUserRepository->findById(2);
        expect($user)->toBe(null);
    });

    it('should return user given valid ID', function () {
        $user = $this->fakeUserRepository->findById(1);
        expect($user)->toBeInstanceOf(User::class);
        expect($user->getId())->toBe(1);
        expect($user->getName())->toBe('Name');
    });

    it('should save a new user', function () {
        $user = new User(2, 'Name 2');
        $this->fakeUserRepository->save($user);
        $savedUser = $this->fakeUserRepository->findById(2);
        expect($savedUser)->toBeInstanceOf(User::class);
        expect($savedUser->getId())->toBe(2);
        expect($savedUser->getName())->toBe('Name 2');
    });
});
