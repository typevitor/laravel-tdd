<?php

namespace Tests\Unit\Domain\Entities;

use App\Domain\Entities\Property;
use App\Exceptions\Property\PropertyNameEmptyException;

it('should create an instance with id and name', function () {
    $property = new Property('1', 'Name', 'Descripton', 5, 10.0);
    expect($property->getId())->toBe('1');
    expect($property->getName())->toBe('Name');
    expect($property->getDescription())->toBe('Descripton');
    expect($property->getMaxOccupants())->toBe(5);
    expect($property->getPricePerNight())->toBe(10);
});

it('should throw an error if name is empty', function () {
    expect(fn () => new Property('1', '', 'Descripton', 5, 10.0))
        ->toThrow(PropertyNameEmptyException::class, 'Property name cannot be empty');
});
