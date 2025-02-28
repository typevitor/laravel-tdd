<?php

namespace Tests\Unit\Application;

use App\Domain\Entities\Property as EntitiesProperty;
use App\Repository\EloquentPropertyRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

describe('EloquentPropertyRepository', function () {

    beforeEach(function () {
        $this->fakePropertyRepository = new EloquentPropertyRepository();
    });

    it('should return null when invalid ID is given', function () {
        $property = $this->fakePropertyRepository->findById(2);
        expect($property)->toBe(null);
    });

    it('should return property given valid ID', function () {
        $property = $this->fakePropertyRepository->findById(1);
        expect($property)->toBeInstanceOf(EntitiesProperty::class);
        expect($property->getId())->toBe(1);
        expect($property->getName())->toBe('Name');
    });

    it('should save a new property', function () {
        $property = new EntitiesProperty(
            2,
            'Name 2',
            'Description 2',
            4,
            20000
        );
        $this->fakePropertyRepository->save($property);
        $savedProperty = $this->fakePropertyRepository->findById(2);
        expect($savedProperty)->toBeInstanceOf(EntitiesProperty::class);
        expect($savedProperty->getId())->toBe(2);
        expect($savedProperty->getName())->toBe('Name 2');
    });
});
