<?php

namespace App\Domain\ValueObjects;

use App\Exceptions\DateRangeException;
use Carbon\Carbon;

class DateRange
{
    public function __construct(private Carbon $startDate, private Carbon $endDate)
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
}
