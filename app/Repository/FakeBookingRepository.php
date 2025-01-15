<?php

namespace App\Repository;

use App\Domain\Entities\Booking;

class FakeBookingRepository implements IBookingRepository
{
    private array $bookings = [];

    public function __construct() {}

    public function findById(string $id): Booking|null
    {
        foreach ($this->bookings as $booking) {
            if ($booking->getId() === $id) {
                return $booking;
            }
        }

        return null;
    }

    public function save(Booking $booking): void
    {
        $this->bookings[] = $booking;
    }
}
