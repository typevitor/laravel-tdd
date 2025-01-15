<?php

namespace App\Application\DTO;

use Carbon\Carbon;

class CreateBookingDTO
{
    public function __construct(
        public string $propertyId,
        public string $userId,
        public Carbon $startDate,
        public Carbon $endDate,
        public int $occupants,
    ) {}
}
