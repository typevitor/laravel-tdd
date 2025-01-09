<?php

namespace Tests\Unit\Domain\Entities;

use App\Domain\Entities\Property;
use App\Domain\ValueObjects\DateRange;
use App\Exceptions\Property\PropertyInvalidOccupantsNumberException;
use App\Exceptions\Property\PropertyInvalidPricePerNightException;
use App\Exceptions\Property\PropertyMaxOccupantsException;
use App\Exceptions\Property\PropertyNameEmptyException;
use Carbon\Carbon;

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


it('should throw an error if occupants number is 0 or lower', function () {
    expect(fn () => new Property('1', 'Name', 'Descripton', 0, 10.0))
        ->toThrow(PropertyInvalidOccupantsNumberException::class, 'Property occupants quantity cannot be 0 or lower');

    expect(fn () => new Property('1', 'Name', 'Descripton', -1, 10.0))
        ->toThrow(PropertyInvalidOccupantsNumberException::class, 'Property occupants quantity cannot be 0 or lower');
});

it('should throw an error if price per night is 0 or lower', function () {
    expect(fn () => new Property('1', 'Name', 'Descripton', 5, 0))
        ->toThrow(PropertyInvalidPricePerNightException::class, 'Property price per night cannot be 0 or lower');

    expect(fn () => new Property('1', 'Name', 'Descripton', 2, -10))
        ->toThrow(PropertyInvalidPricePerNightException::class, 'Property price per night cannot be 0 or lower');
});

it('should throw an error if max occupants validation is exceed', function () {
    $property = new Property('1', 'Name', 'Descripton', 5, 10.0);
    $occupants = 6;
    expect(fn () => $property->validateOccupantsQuantity($occupants))
        ->toThrow(PropertyMaxOccupantsException::class, 'Property occupants exceed. Max allowed is: '. $property->getMaxOccupants());
});

it('should not give discount if amount of nights booked is lower than 7', function () {
    $property = new Property('1', 'Name', 'Descripton', 5, 10.0);
    $dateRange = new DateRange(Carbon::parse('2025-01-01'), Carbon::parse('2025-01-05'));
    $totalPrice = $property->calculateTotalPrice($dateRange);
    expect($totalPrice)->toBe($property->getPricePerNight() * $dateRange->getReservationNights());
});

it('should give 10% discount if amount of nights is 7 or higher', function () {
    $property = new Property('1', 'Name', 'Descripton', 5, 10.0);
    $dateRange = new DateRange(Carbon::parse('2025-01-01'), Carbon::parse('2025-01-08'));
    $totalPrice = $property->calculateTotalPrice($dateRange);
    $priceWithDiscount = $property->getPricePerNight() * $dateRange->getReservationNights() * Property::BASE_DISCOUNT;
    expect($totalPrice)->toBe(intval($priceWithDiscount));
});
