<?php

namespace App\Domain\Entities;

use App\Domain\ValueObjects\DateRange;
use App\Exceptions\Property\PropertyInvalidOccupantsNumberException;
use App\Exceptions\Property\PropertyInvalidPricePerNightException;
use App\Exceptions\Property\PropertyMaxOccupantsException;
use App\Exceptions\Property\PropertyNameEmptyException;

class Property
{
    public function __construct(
        private readonly string $id,
        private readonly string $name,
        private readonly string $description,
        private readonly int $maxOccupants,
        private readonly int $pricePerNight,
    )
    {
        if ($name === "") {
            throw new PropertyNameEmptyException();
        }

        if ($maxOccupants <= 0) {
            throw new PropertyInvalidOccupantsNumberException();
        }

        if ($pricePerNight <= 0) {
            throw new PropertyInvalidPricePerNightException();
        }
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getMaxOccupants(): int
    {
        return $this->maxOccupants;
    }

    public function getPricePerNight(): int
    {
        return $this->pricePerNight;
    }

    public function validateOccupantsQuantity(int $occupants): void
    {
        if ($occupants > $this->maxOccupants) {
            throw new PropertyMaxOccupantsException($this->maxOccupants);
        }
    }

    public function calculateTotalPrice(DateRange $dateRange): int
    {
        return $dateRange->getReservationNights() * $this->pricePerNight;
    }
}
