<?php

use App\Domain\ValueObjects\DateRange;
use App\Exceptions\DateRangeException;
use Carbon\Carbon;




it('should return an exception if end date is before start date', function () {
    expect(function () {
        new DateRange(Carbon::parse('2021-01-01'), Carbon::parse('2020-01-01'));
    })->toThrow(DateRangeException::class, 'End date must be after start date');

    expect(function () {
        new DateRange(Carbon::parse('2021-01-01'), Carbon::parse('2021-01-01'));
    })->toThrow(DateRangeException::class, 'End date must be after start date');
});
