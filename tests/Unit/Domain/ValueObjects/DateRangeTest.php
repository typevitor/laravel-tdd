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
