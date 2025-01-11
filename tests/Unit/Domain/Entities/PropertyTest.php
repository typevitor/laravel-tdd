<?php

namespace Tests\Unit\Domain\Entities;

use App\Domain\Entities\Booking;
use App\Domain\Entities\Property;
use App\Domain\Entities\User;
use App\Domain\ValueObjects\DateRange;
use App\Enum\BookStatus;
use App\Exceptions\Property\PropertyInvalidOccupantsNumberException;
use App\Exceptions\Property\PropertyInvalidPricePerNightException;
use App\Exceptions\Property\PropertyMaxOccupantsException;
use App\Exceptions\Property\PropertyNameEmptyException;
use Carbon\Carbon;
use Mockery;

it('should create an instance with id and name', function () {
    $property = new Property('1', 'Name', 'Descripton', 5, 10.0);
    expect($property->getId())->toBe('1');
    expect($property->getName())->toBe('Name');
    expect($property->getDescription())->toBe('Descripton');
    expect($property->getMaxOccupants())->toBe(5);
    expect($property->getPricePerNight())->toBe(10);
});

it('should throw an error if name is empty', function () {
    expect(fn() => new Property('1', '', 'Descripton', 5, 10.0))
        ->toThrow(PropertyNameEmptyException::class, 'Property name cannot be empty');
});

it('should throw an error if occupants number is 0 or lower', function () {
    expect(fn() => new Property('1', 'Name', 'Descripton', 0, 10.0))
        ->toThrow(PropertyInvalidOccupantsNumberException::class, 'Property occupants quantity cannot be 0 or lower');

    expect(fn() => new Property('1', 'Name', 'Descripton', -1, 10.0))
        ->toThrow(PropertyInvalidOccupantsNumberException::class, 'Property occupants quantity cannot be 0 or lower');
});

it('should throw an error if price per night is 0 or lower', function () {
    expect(fn() => new Property('1', 'Name', 'Descripton', 5, 0))
        ->toThrow(PropertyInvalidPricePerNightException::class, 'Property price per night cannot be 0 or lower');

    expect(fn() => new Property('1', 'Name', 'Descripton', 2, -10))
        ->toThrow(PropertyInvalidPricePerNightException::class, 'Property price per night cannot be 0 or lower');
});

it('should throw an error if max occupants validation is exceed', function () {
    $property = new Property('1', 'Name', 'Descripton', 5, 100);
    $occupants = 6;
    expect(fn() => $property->validateOccupantsQuantity($occupants))
        ->toThrow(PropertyMaxOccupantsException::class, 'Property occupants exceed. Max allowed is: ' . $property->getMaxOccupants());
});

it('should not give discount if amount of nights booked is lower than 7', function () {
    $property = new Property('1', 'Name', 'Descripton', 5, 10000);
    $dateRange = new DateRange(Carbon::parse('2025-01-01'), Carbon::parse('2025-01-05'));
    $totalPrice = $property->calculateTotalPrice($dateRange);
    expect($totalPrice)->toBe(40000);

    $dateRange = new DateRange(Carbon::parse('2025-01-01'), Carbon::parse('2025-01-03'));
    $totalPrice = $property->calculateTotalPrice($dateRange);
    expect($totalPrice)->toBe(20000);
});

it('should give 10% discount if amount of nights is 7 or higher', function () {
    $property = new Property('1', 'Name', 'Descripton', 5, 10000);
    $dateRange = new DateRange(Carbon::parse('2025-01-01'), Carbon::parse('2025-01-08'));
    $totalPrice = $property->calculateTotalPrice($dateRange);
    expect($totalPrice)->toBe(63000);

    $property = new Property('1', 'Name', 'Descripton', 5, 30000);
    $dateRange = new DateRange(Carbon::parse('2025-01-01'), Carbon::parse('2025-01-18'));
    $totalPrice = $property->calculateTotalPrice($dateRange);
    expect($totalPrice)->toBe(459000);

    $property = new Property('1', 'Name', 'Descripton', 5, 13995);
    $dateRange = new DateRange(Carbon::parse('2025-01-10'), Carbon::parse('2025-01-22'));
    $totalPrice = $property->calculateTotalPrice($dateRange);
    expect($totalPrice)->toBe(151146);

    $property = new Property('1', 'Name', 'Descripton', 5, 14599);
    $dateRange = new DateRange(Carbon::parse('2025-01-10'), Carbon::parse('2025-01-19'));
    $totalPrice = $property->calculateTotalPrice($dateRange);
    expect($totalPrice)->toBe(118252);
});

it('should mock Book to test add booking', function () {
    $property = new Property('1', 'Name', 'Descripton', 5, 10000);
    $dateRange = new DateRange(Carbon::parse('2025-01-10'), Carbon::parse('2025-01-18'));

    $book = Mockery::mock(Booking::class);
    $book->shouldReceive('getId')->andReturn('1');
    $book->shouldReceive('getProperty')->andReturn($property);
    $book->shouldReceive('getBookStatus')->andReturn(BookStatus::CONFIRMED);
    $book->shouldReceive('getDateRange')->andReturn($dateRange);
    $property->addBooking($book);

    expect($property->getBookings()[0]->getId())->toBe('1');
    expect($property->getBookings()[0]->getProperty())->toEqual($property);
    expect($property->getBookings()[0]->getBookStatus())->toBe(BookStatus::CONFIRMED);
    expect($property->getBookings()[0]->getDateRange())->toEqual($dateRange);
});


it('should validate if property is avaliable', function () {
    $property = new Property('1', 'Name', 'Descripton', 5, 10000);
    $dateRange = new DateRange(Carbon::parse('2025-01-10'), Carbon::parse('2025-01-18'));
    $user = new User('1', 'UserName');

    $book = Mockery::mock(Booking::class);
    $book->shouldReceive('getId')->andReturn('1');
    $book->shouldReceive('getProperty')->andReturn($property);
    $book->shouldReceive('getBookStatus')->andReturn(BookStatus::CONFIRMED);
    $book->shouldReceive('getDateRange')->andReturn($dateRange);
    $property->addBooking($book);

    $dateRange2 = new DateRange(Carbon::parse('2025-01-15'), Carbon::parse('2025-01-20'));
    $dateRange3 = new DateRange(Carbon::parse('2025-01-20'), Carbon::parse('2025-01-24'));
    expect($property->isAvaliable($dateRange))->toBe(false);
    expect($property->isAvaliable($dateRange2))->toBe(false);
    expect($property->isAvaliable($dateRange3))->toBe(true);
});

