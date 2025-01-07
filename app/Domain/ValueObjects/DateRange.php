<?php

namespace App\Domain\ValueObjects;

use App\Exceptions\DateRangeException;
use Carbon\Carbon;

class DateRange
{
    public function __construct(Carbon $startDate, Carbon $endDate)
    {
        if ($endDate->lte($startDate)) {
            throw new DateRangeException();
        }
   }
}
