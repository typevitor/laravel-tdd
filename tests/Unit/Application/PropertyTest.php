<?php

namespace Tests\Unit\Application;

use App\Application\PropertyService;
use App\Domain\Entities\Property;
use App\Repository\FakePropertyRepository;

describe('Property Service', function () {

    beforeEach(function() {
        $fakePropertyRepository = new FakePropertyRepository();
        $this->propertyService = new PropertyService($fakePropertyRepository);
    });

    it('should return null when invalid ID is given', function () {
        $property = $this->propertyService->findById('2');
        expect($property)->toBe(null);
    });

    it('should return property given valid ID', function () {
        $property = $this->propertyService->findById('1');
        expect($property)->toBeInstanceOf(Property::class);
        expect($property->getId())->toBe('1');
        expect($property->getName())->toBe('Casa');
        expect($property->getDescription())->toBe('casa de campo');
        expect($property->getMaxOccupants())->toBe(5);
        expect($property->getPricePerNight())->toBe(10000);
    });

    it('should save a new property', function () {
        $property = new Property('2', 'Apto', 'Centro', 2, 30000);
        $this->propertyService->save($property);
        $savedProperty = $this->propertyService->findById('2');
        expect($savedProperty)->toBeInstanceOf(Property::class);
        expect($savedProperty->getId())->toBe('2');
        expect($savedProperty->getName())->toBe('Apto');
        expect($savedProperty->getDescription())->toBe('Centro');
        expect($savedProperty->getMaxOccupants())->toBe(2);
        expect($savedProperty->getPricePerNight())->toBe(30000);
    });
});
