<?php

namespace Tests\Unit\Domain\Entities;

use App\Domain\Entities\User;
use App\Exceptions\User\UserEmptyIdException;
use App\Exceptions\User\UserEmptyNameException;

it('should create an instance with id and name', function () {
    $user = new User('1', 'Name');
    expect($user->getId())->toBe('1');
    expect($user->getName())->toBe('Name');
});

it('should throw an error if name is empty', function () {
    expect(fn() => new User('1', ''))->toThrow(UserEmptyNameException::class, 'User name cannot be empty');
});

it('should throw an error if id is empty', function () {
    expect(fn() => new User('', 'Name'))->toThrow(UserEmptyIdException::class, 'User Id cannot be empty');
});
