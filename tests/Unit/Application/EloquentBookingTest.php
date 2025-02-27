<?php

namespace Tests\Unit\Application;

use App\Domain\Entities\Booking;
use App\Domain\Entities\Property as EntitiesProperty;
use App\Domain\Entities\User as EntitiesUser;
use App\Domain\ValueObjects\DateRange;
use App\Enum\BookStatus;
use App\Models\Property;
use App\Models\User;
use App\Repository\EloquentBookingRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

describe('EloquentBookingTest', function () {

    beforeEach(function () {
        $this->fakeBookingRepository = new EloquentBookingRepository();
    });

    it('should create a booking successfully', function () {
        Property::factory()->create([
            'id' => '3',
            'name' => 'Name',
            'description' => 'Description',
            'max_occupants' => 5,
            'price_per_night' => 10000,
        ]);
        User::factory()->create([
            'id' => '1',
            'name' => 'Name',
        ]);

        $entityUser = new EntitiesUser('1', 'Name');
        $entityProperty = new EntitiesProperty('3', 'Name', 'Description', 5, 10000);
        $dateRange = new DateRange(\Carbon\Carbon::parse('2025-01-01'), \Carbon\Carbon::parse('2025-01-05'));

        $entityBooking = new Booking(
            '1',
            $entityProperty,
            $entityUser,
            $dateRange,
            4
        );

        $this->fakeBookingRepository->save($entityBooking);

        $booking = $this->fakeBookingRepository->findById('1');
        expect($booking)->toBeInstanceOf(Booking::class);
        expect($booking->getId())->toBe('1');
        expect($booking->getBookStatus())->toBe(BookStatus::CONFIRMED);
        expect($booking->getTotalPrice())->toBe(40000);
        expect($booking->getUser()->getId())->toBe('1');
        expect($booking->getProperty()->getId())->toBe('3');
    });

    it('should return null when searching for a invalid Booking', function () {
        $booking = $this->fakeBookingRepository->findById('1');
        expect($booking)->toBeNull();
    });

    it('should cancel a valid booking', function () {
        Property::factory()->create([
            'id' => '3',
            'name' => 'Name',
            'description' => 'Description',
            'max_occupants' => 5,
            'price_per_night' => 10000,
        ]);
        User::factory()->create([
            'id' => '1',
            'name' => 'Name',
        ]);

        $entityUser = new EntitiesUser('1', 'Name');
        $entityProperty = new EntitiesProperty('3', 'Name', 'Description', 5, 10000);
        $dateRange = new DateRange(\Carbon\Carbon::parse('2025-01-01'), \Carbon\Carbon::parse('2025-01-05'));

        $entityBooking = new Booking(
            '1',
            $entityProperty,
            $entityUser,
            $dateRange,
            4
        );

        $this->fakeBookingRepository->save($entityBooking);

        $booking = $this->fakeBookingRepository->findById('1');
        expect($booking->getBookStatus())->toBe(BookStatus::CONFIRMED);
        $booking->cancel(\Carbon\Carbon::parse('2024-12-20'));
        expect($booking->getBookStatus())->toBe(BookStatus::CANCELLED);
    });
});
