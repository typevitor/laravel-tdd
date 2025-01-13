<?php

namespace App\Domain\Entities;

use App\Domain\Cancelation\FullRefund;
use App\Domain\Cancelation\PartialRefund;
use App\Domain\Cancelation\RefundFactory;
use App\Domain\ValueObjects\DateRange;
use App\Enum\BookStatus;
use App\Exceptions\Booking\BookingAlreadyCancelledException;
use App\Exceptions\Booking\BookMinimumOccupantsException;
use App\Exceptions\Booking\UnavaliablePropertyException;
use Carbon\Carbon;

class Booking
{
    private BookStatus $status = BookStatus::CONFIRMED;
    private int $totalPrice;

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

        if ($property->isUnavaliable($dateRange)) {
            throw new UnavaliablePropertyException();
        }

        $property->validateOccupantsQuantity($occupants);
        $property->addBooking($this);
        $this->totalPrice = $property->calculateTotalPrice($dateRange);
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

    public function getTotalPrice(): int
    {
        return $this->totalPrice;
    }

    public function cancel(Carbon $cancelDate): void
    {
        if ($this->getBookStatus() === BookStatus::CANCELLED) {
            throw new BookingAlreadyCancelledException();
        }
        $checkIn = $this->dateRange->getStartDate();
        $diffUntilCheckinInDays = $cancelDate->diffInDays($checkIn);
        $refundRule = RefundFactory::getRefundRule((int) $diffUntilCheckinInDays);
        $this->totalPrice = $refundRule->calculateRefund($this->totalPrice);
        $this->status = BookStatus::CANCELLED;
    }

}
