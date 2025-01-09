<?php

namespace Tests\Unit\Domain\Entities;

use App\Domain\Entities\Property;

it('should create an instance with id and name', function () {
    $property = new Property('1', 'Name', 'Descripton', 5, 10.0);
    expect($property->getId())->toBe('1');
    expect($property->getName())->toBe('Name');
    expect($property->getDescription())->toBe('Descripton');
    expect($property->getMaxOccupants())->toBe(5);
    expect($property->getPricePerNight())->toBe(10);
});
