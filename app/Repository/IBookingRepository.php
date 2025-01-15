<?php

namespace App\Repository;

use App\Domain\Entities\Booking;

interface IBookingRepository
{
    public function findById(string $id): Booking|null;
    public function save(Booking $booking): void;
}
