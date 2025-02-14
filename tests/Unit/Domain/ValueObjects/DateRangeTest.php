<?php

use App\Domain\ValueObjects\DateRange;
use App\Exceptions\DateRangeException;
use Carbon\Carbon;

it('should create date range with correct parameters', function () {
    $startDate = Carbon::parse('2021-01-01');
    $endDate = Carbon::parse('2021-01-05');
    $dateRange = new DateRange($startDate, $endDate);
    expect($dateRange->getStartDate())->toBe($startDate);
    expect($dateRange->getEndDate())->toBe($endDate);
});

it('should return an exception if end date is before start date', function () {
    expect(function () {
        new DateRange(Carbon::parse('2021-01-01'), Carbon::parse('2020-01-01'));
    })->toThrow(DateRangeException::class, 'End date must be after start date');

    expect(function () {
        new DateRange(Carbon::parse('2021-01-01'), Carbon::parse('2021-01-01'));
    })->toThrow(DateRangeException::class, 'End date must be after start date');
});

it('should calculate the amount of nights for the reservation', function () {
    $reservation1 = new DateRange(
        Carbon::parse('2025-01-01'),
        Carbon::parse('2025-01-05')
    );
    expect($reservation1->getReservationNights())->toBe(4);

    $reservation2 = new DateRange(
        Carbon::parse('2025-01-10'),
        Carbon::parse('2025-01-13')
    );
    expect($reservation2->getReservationNights())->toBe(3);

    $reservation3 = new DateRange(
        Carbon::parse('2025-01-31'),
        Carbon::parse('2025-02-12')
    );
    expect($reservation3->getReservationNights())->toBe(12);

    $reservation4 = new DateRange(
        Carbon::parse('2025-01-01'),
        Carbon::parse('2026-01-02')
    );
    expect($reservation4->getReservationNights())->toBe(366);
});

it('should not allow reservation with a overlap date range', function () {
    $reservation1 = new DateRange(
        Carbon::parse('2025-01-01'),
        Carbon::parse('2025-01-05')
    );
    expect($reservation1->getReservationNights())->toBe(4);

    $reservation2 = new DateRange(
        Carbon::parse('2025-01-02'),
        Carbon::parse('2025-01-13')
    );
    $reservation3 = new DateRange(
        Carbon::parse('2025-01-12'),
        Carbon::parse('2025-01-22')
    );
    $reservation4 = new DateRange(
        Carbon::parse('2025-01-22'),
        Carbon::parse('2025-01-24')
    );
    expect($reservation1->overlaps($reservation2))->toBeTrue();
    expect($reservation2->overlaps($reservation3))->toBeTrue();
    expect($reservation1->overlaps($reservation3))->toBeFalse();
    expect($reservation3->overlaps($reservation4))->toBeTrue();
});

it('should not allow start date and end date to be equal', function () {
    expect(function () {
        new DateRange(Carbon::parse('2021-01-01'), Carbon::parse('2020-01-01'));
    })->toThrow(DateRangeException::class, 'End date must be after start date');
});
