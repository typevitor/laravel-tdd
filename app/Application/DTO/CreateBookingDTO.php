<?php

namespace App\Application\DTO;

use Carbon\Carbon;

class CreateBookingDTO
{
    public function __construct(
        public int $propertyId,
        public int $userId,
        public Carbon $startDate,
        public Carbon $endDate,
        public int $occupants,
    ) {}
}
