<?php

namespace Tests\Unit\Domain\Entities;

use App\Domain\Entities\Booking;
use App\Domain\Entities\Property;
use App\Domain\Entities\User;
use App\Domain\ValueObjects\DateRange;
use App\Exceptions\Booking\BookMinimumOccupantsException;
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
