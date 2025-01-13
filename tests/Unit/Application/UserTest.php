<?php

namespace Tests\Unit\Application;

use App\Application\UserService;
use App\Domain\Entities\User;
use App\Repository\FakeUserRepository;

describe('User Service', function () {
    beforeEach(function() {
        $fakeUserRepository = new FakeUserRepository();
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
});
