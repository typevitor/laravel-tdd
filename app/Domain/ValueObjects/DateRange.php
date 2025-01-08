<?php

namespace App\Domain\ValueObjects;

use App\Exceptions\DateRangeException;
use Carbon\Carbon;

class DateRange
{
    public function __construct(private readonly Carbon $startDate, private readonly  Carbon $endDate)
    {
        if ($endDate->lte($startDate)) {
            throw new DateRangeException();
        }
    }

    public function getStartDate(): Carbon
    {
        return $this->startDate;
    }

    public function getEndDate(): Carbon
    {
        return $this->endDate;
    }

    public function getReservationNights(): int
    {
        return $this->startDate->diffInDays($this->endDate);
    }

    public function overlaps(DateRange $dateRange): bool
    {
        // return $this->getStartDate() <= $dateRange->getEndDate()
        //     && $this->getEndDate() >= $dateRange->getStartDate();
        return $this->getStartDate()->between($dateRange->getStartDate(), $dateRange->getEndDate())
            || $this->getEndDate()->between($dateRange->getStartDate(), $dateRange->getEndDate());
    }
}
