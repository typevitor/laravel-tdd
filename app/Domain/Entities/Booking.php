<?php

namespace App\Domain\Entities;

use App\Domain\ValueObjects\DateRange;
use App\Enum\BookStatus;
use App\Exceptions\Booking\BookMaximumOccupantsException;
use App\Exceptions\Booking\BookMinimumOccupantsException;

class Booking
{
    private BookStatus $status = BookStatus::CONFIRMED;

    public function __construct(
        private readonly string $id,
        private readonly Property $property,
        private readonly User $user,
        private readonly DateRange $dateRange,
        private readonly int $occupants,
    ) {
        if ($occupants <= 0) {
            throw new BookMinimumOccupantsException();
        }
        if ($occupants > $property->getMaxOccupants()) {
            throw new BookMaximumOccupantsException($property->getMaxOccupants());
        }
        $property->addBooking($this);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getProperty(): Property
    {
        return $this->property;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getDateRange(): DateRange
    {
        return $this->dateRange;
    }

    public function getOccupants(): int
    {
        return $this->occupants;
    }

    public function getBookStatus(): BookStatus
    {
        return $this->status;
    }
}
