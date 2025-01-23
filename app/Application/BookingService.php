<?php

namespace App\Application;

use App\Application\DTO\CreateBookingDTO;
use App\Domain\Entities\Booking;
use App\Domain\ValueObjects\DateRange;
use App\Exceptions\Property\PropertyNotFoundException;
use App\Exceptions\User\UserNotFoundException;
use App\Repository\IBookingRepository;
use Illuminate\Support\Str;

class BookingService
{
    public function __construct(
        private readonly IBookingRepository $iBookingRepository,
        private readonly PropertyService $propertyService,
        private readonly UserService $userService,
        private readonly DateRange $dateRange,
    ) {}

    public function findById(string $id): Booking|null
    {
        return $this->iBookingRepository->findById($id);
    }


    public function save(CreateBookingDTO $bookingDTO): Booking
    {
        $property = $this->propertyService->findById($bookingDTO->propertyId);
        if (!$property) {
            throw new PropertyNotFoundException();
        }

        $user = $this->userService->findById($bookingDTO->userId);
        if (!$user) {
            throw new UserNotFoundException();
        }

        $booking = new Booking(
            Str::uuid()->toString(),
            $property,
            $user,
            $this->dateRange,
            $bookingDTO->occupants
        );

        $this->iBookingRepository->save($booking);
        return $booking;
    }

    public function cancel(string $id): void
    {
        $booking = $this->findById($id);
        $booking->cancel(new \Carbon\Carbon('now'));
        $this->iBookingRepository->save($booking);
    }
}
