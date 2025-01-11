<?php

namespace Tests\Unit\Domain\Entities;

use App\Domain\Entities\Booking;
use App\Domain\Entities\Property;
use App\Domain\Entities\User;
use App\Domain\ValueObjects\DateRange;
use App\Exceptions\Booking\BookMinimumOccupantsException;
use App\Exceptions\Booking\UnavaliablePropertyException;
use App\Exceptions\Property\PropertyMaxOccupantsException;
use Carbon\Carbon;

it('should create an instance of Book given property, user and DateRange and occupants, status', function () {
    $property = new Property('1', 'Casa', 'Casa', 5, 10000);
    $user = new User('1', 'UserName');
    $startDate = Carbon::parse('2025-01-01');
    $endDate = Carbon::parse('2025-01-05');
    $dateRange = new DateRange($startDate, $endDate);
    $occupants = 4;
    $booking = new Booking('1', $property, $user, $dateRange, $occupants);
    expect($booking->getId())->toBe('1');
    expect($booking->getProperty())->toEqual($property);
    expect($booking->getUser())->toEqual($user);
    expect($booking->getDateRange())->toEqual($dateRange);
    expect($booking->getOccupants())->toBe($occupants);
});

it('should throw an exception if number of occupants is zero or lower', function () {
    $property = new Property('1', 'Name', 'Descripton', 5, 10000);
    $dateRange = new DateRange(Carbon::parse('2025-01-10'), Carbon::parse('2025-01-18'));
    $user = new User('1', 'UserName');
    expect(fn () => new Booking('1', $property, $user, $dateRange, 0))
        ->toThrow(BookMinimumOccupantsException::class, 'Number of occupants should be higher than zero.');
});

it('should throw an exception if number of occupants is higher than property max occupants', function () {
    $property = new Property('1', 'Name', 'Descripton', 5, 10000);
    $dateRange = new DateRange(Carbon::parse('2025-01-10'), Carbon::parse('2025-01-18'));
    $user = new User('1', 'UserName');
    expect(fn () => new Booking('1', $property, $user, $dateRange, 10))
        ->toThrow(PropertyMaxOccupantsException::class);
});

it('should book a property with discount', function() {
    $property = new Property('1', 'Casa', 'Casa', 5, 9000);
    $user = new User('1', 'UserName');
    $startDate = Carbon::parse('2025-01-10');
    $endDate = Carbon::parse('2025-01-20');
    $dateRange = new DateRange($startDate, $endDate);
    $occupants = 4;
    $booking = new Booking('1', $property, $user, $dateRange, $occupants);
    expect($booking->getTotalPrice())->toBe(intval(9000 * 10 * Property::BASE_DISCOUNT));
});

it('should book a property without discount', function() {
    //Arrange
    $property = new Property('1', 'Casa', 'Casa', 5, 9000);
    $user = new User('1', 'UserName');
    $startDate = Carbon::parse('2025-01-10');
    $endDate = Carbon::parse('2025-01-14');
    $dateRange = new DateRange($startDate, $endDate);
    $occupants = 4;

    //Act
    $booking = new Booking('1', $property, $user, $dateRange, $occupants);

    //Assert
    expect($booking->getTotalPrice())->toBe(intval(9000 * 4));
});


it('should throw an exception when booking a unavailable property', function () {
    //Arrange
    $property = new Property('1', 'Casa', 'Casa', 5, 10000);
    $user = new User('1', 'UserName');
    $startDate = Carbon::parse('2025-01-01');
    $endDate = Carbon::parse('2025-01-05');
    $dateRange = new DateRange($startDate, $endDate);
    $occupants = 4;
    new Booking('1', $property, $user, $dateRange, $occupants);

    $startDate2 = Carbon::parse('2025-01-04');
    $endDate2 = Carbon::parse('2025-01-10');
    $dateRange2 = new DateRange($startDate2, $endDate2);

    //Act ~ Assert
    expect(fn () => new Booking('2', $property, $user, $dateRange2, 5))
        ->toThrow(UnavaliablePropertyException::class, 'Property is unavaliable for given date range.');
});

