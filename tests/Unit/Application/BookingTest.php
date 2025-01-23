<?php

namespace Tests\Unit\Application;

use App\Application\BookingService;
use App\Application\DTO\CreateBookingDTO;
use App\Application\PropertyService;
use App\Application\UserService;
use App\Domain\Entities\Booking;
use App\Domain\Entities\Property;
use App\Domain\Entities\User;
use App\Domain\ValueObjects\DateRange;
use App\Enum\BookStatus;
use App\Exceptions\Booking\UnavaliablePropertyException;
use App\Exceptions\Property\PropertyNotFoundException;
use App\Exceptions\User\UserNotFoundException;
use App\Repository\FakeBookingRepository;
use Mockery;

describe('Booking Service', function () {

    beforeEach(function () {
        $fakeBookingRepository = new FakeBookingRepository();

        /** @var MockObject|PropertyService */
        $this->mockPropertyService = Mockery::mock(PropertyService::class);

        /** @var MockObject|UserService */
        $this->mockUserService = Mockery::mock(UserService::class);

        /** @var MockObject|UserService */
        $this->date = Mockery::mock(UserService::class);

        /** @var MockObject|DateRange */
        $this->mockDateRange = Mockery::mock(DateRange::class);

        $this->bookingService = new BookingService(
            $fakeBookingRepository,
            $this->mockPropertyService,
            $this->mockUserService,
            $this->mockDateRange
        );
    });

    it('should return null when invalid ID is given', function () {
        $booking = $this->bookingService->findById('2');
        expect($booking)->toBe(null);
    });

    it('Should successful book a property', function () {
        $mockProperty = Mockery::mock(Property::class);
        $mockProperty->shouldReceive('getId')->andReturn('1');
        $mockProperty->shouldReceive('isAvaliable')->andReturn(true);
        $mockProperty->shouldReceive('isUnavaliable')->andReturn(false);
        $mockProperty->shouldReceive('validateOccupantsQuantity');
        $mockProperty->shouldReceive('calculateTotalPrice')->andReturn(50000);
        $mockProperty->shouldReceive('addBooking');
        $this->mockPropertyService->shouldReceive('findById')->andReturn($mockProperty);

        $mockUser = Mockery::mock(User::class);
        $mockUser->shouldReceive('getId')->andReturn('1');
        $this->mockUserService->shouldReceive('findById')->andReturn($mockUser);

        $this->mockDateRange->shouldReceive('getStartDate')->andReturn(\Carbon\Carbon::parse('2025-01-01'));
        $this->mockDateRange->shouldReceive('getEndDate')->andReturn(\Carbon\Carbon::parse('2025-01-05'));
        $this->mockDateRange->shouldReceive('getReservationNights')->andReturn(4);

        $bookingDTO = new CreateBookingDTO(
            "1",
            "1",
            \Carbon\Carbon::parse('2025-01-01'),
            \Carbon\Carbon::parse('2025-01-05'),
            5,
        );

        $booking = $this->bookingService->save($bookingDTO);
        expect($booking)->toBeInstanceOf(Booking::class);
        expect($booking->getBookStatus())->toBe(BookStatus::CONFIRMED);
        expect($booking->getTotalPrice())->toBe(50000);

        $savedBooking = $this->bookingService->findById($booking->getId());
        expect($savedBooking->getId())->toBe($booking->getId());
    });

    it('Should throw exception if property is not found', function () {
        $mockProperty = Mockery::mock(Property::class);
        $mockProperty->shouldReceive('getId')->andReturn('1');
        $mockProperty->shouldReceive('isAvaliable')->andReturn(true);
        $mockProperty->shouldReceive('isUnavaliable')->andReturn(false);
        $mockProperty->shouldReceive('validateOccupantsQuantity');
        $mockProperty->shouldReceive('calculateTotalPrice')->andReturn(50000);
        $mockProperty->shouldReceive('addBooking');
        $this->mockPropertyService->shouldReceive('findById')->andReturn(null);

        $bookingDTO = new CreateBookingDTO(
            "2",
            "1",
            \Carbon\Carbon::parse('2025-01-01'),
            \Carbon\Carbon::parse('2025-01-05'),
            5,
        );
        expect(function () use ($bookingDTO) {
            $this->bookingService->save($bookingDTO);
        })->toThrow(PropertyNotFoundException::class);
    });

    it('Should throw exception if user is not found', function () {
        $mockProperty = Mockery::mock(Property::class);
        $mockProperty->shouldReceive('getId')->andReturn('1');
        $this->mockPropertyService->shouldReceive('findById')->andReturn($mockProperty);

        $mockUser = Mockery::mock(User::class);
        $mockUser->shouldReceive('getId')->andReturn('1');
        $this->mockUserService->shouldReceive('findById')->andReturn(null);

        $bookingDTO = new CreateBookingDTO(
            "1",
            "2",
            \Carbon\Carbon::parse('2025-01-01'),
            \Carbon\Carbon::parse('2025-01-05'),
            5,
        );
        expect(function () use ($bookingDTO) {
            $this->bookingService->save($bookingDTO);
        })->toThrow(UserNotFoundException::class);
    });

    it('Should throw exception try to book a unabaliable property', function () {
        $mockProperty = Mockery::mock(Property::class);
        $mockProperty->shouldReceive('validateOccupantsQuantity');
        $mockProperty->shouldReceive('isUnavaliable')->once()->andReturn(false);
        $mockProperty->shouldReceive('calculateTotalPrice')->once()->andReturn(50000);
        $mockProperty->shouldReceive('addBooking')->once();
        $this->mockPropertyService->shouldReceive('findById')->andReturn($mockProperty);

        $mockUser = Mockery::mock(User::class);
        $mockUser->shouldReceive('getId')->andReturn('1');
        $this->mockUserService->shouldReceive('findById')->andReturn($mockUser);

        $bookingDTO = new CreateBookingDTO(
            "1",
            "1",
            \Carbon\Carbon::parse('2025-01-01'),
            \Carbon\Carbon::parse('2025-01-05'),
            5,
        );
        $this->bookingService->save($bookingDTO);

        $mockProperty->shouldReceive('isUnavaliable')->andReturn(true);
        $mockProperty->shouldReceive('addBooking')
            ->never()
            ->andThrow(new UnavaliablePropertyException());
        $this->mockPropertyService->shouldReceive('findById')->andReturn($mockProperty);

        expect(function () use ($bookingDTO) {
            $this->bookingService->save($bookingDTO);
        })->toThrow(UnavaliablePropertyException::class);
    });
});
