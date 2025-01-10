<?php

namespace App\Domain\Entities;

use App\Domain\ValueObjects\DateRange;
use App\Enum\BookStatus;
use App\Exceptions\Property\PropertyInvalidOccupantsNumberException;
use App\Exceptions\Property\PropertyInvalidPricePerNightException;
use App\Exceptions\Property\PropertyMaxOccupantsException;
use App\Exceptions\Property\PropertyNameEmptyException;

class Property
{
    public const BASE_DISCOUNT = 0.9;

    public const BASE_QTDE_NIGHTS_FOR_DISCOUNT = 7;

    private array $bookings = array();

    public function __construct(
        private readonly string $id,
        private readonly string $name,
        private readonly string $description,
        private readonly int $maxOccupants,
        private readonly int $pricePerNight,
    ) {
        if ($name === '') {
            throw new PropertyNameEmptyException();
        }

        if ($maxOccupants <= 0) {
            throw new PropertyInvalidOccupantsNumberException();
        }

        if ($pricePerNight <= 0) {
            throw new PropertyInvalidPricePerNightException();
        }
    }

    public function addBooking(Booking $booking): void
    {
        $this->bookings[] = $booking;
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

    public function getBookings(): array
    {
        return $this->bookings;
    }

    public function validateOccupantsQuantity(int $occupants): void
    {
        if ($occupants > $this->maxOccupants) {
            throw new PropertyMaxOccupantsException($this->maxOccupants);
        }
    }

    public function calculateTotalPrice(DateRange $dateRange): int
    {
        $basePrice = $dateRange->getReservationNights() * $this->pricePerNight;
        if ($dateRange->getReservationNights() >= self::BASE_QTDE_NIGHTS_FOR_DISCOUNT) {
            return (int) round($basePrice * self::BASE_DISCOUNT, 0);
        }

        return (int) round($basePrice, 0);
    }

    public function isAvaliable(DateRange $dateRange): bool
    {
        foreach($this->bookings as $booking) {
            if (
                $booking->getBookStatus() === BookStatus::CONFIRMED &&
                $booking->getDateRange()->overlaps($dateRange)
            ) {
                return false;
            }
        }
        return true;
    }
}
