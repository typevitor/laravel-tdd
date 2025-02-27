<?php

namespace App\Repository;

use App\Domain\Entities\Booking as EntityBooking;
use App\Domain\Entities\Property as EntityProperty;
use App\Domain\Entities\User as EntityUser;
use App\Domain\ValueObjects\DateRange;
use App\Models\Booking;

class EloquentBookingRepository implements IBookingRepository
{
    public function __construct() {}

    public function save(EntityBooking $booking): void
    {
        $booking = Booking::create([
            'id' => $booking->getId(),
            'property_id' => $booking->getProperty()->getId(),
            'user_id' => $booking->getUser()->getId(),
            'start_date' => $booking->getDateRange()->getStartDate(),
            'end_date' => $booking->getDateRange()->getEndDate(),
            'occupants' => $booking->getOccupants(),
            'total_price' => $booking->getTotalPrice(),
            'status' => $booking->getBookStatus(),
        ]);
    }

    public function findById(string $id): ?EntityBooking
    {
        $booking = Booking::with(['user', 'property'])->find($id);
        if ($booking) {
            $entityBooking = new EntityBooking(
                $booking->id,
                new EntityProperty(
                    $booking->property->id,
                    $booking->property->name,
                    $booking->property->description,
                    $booking->property->max_occupants,
                    $booking->property->price_per_night
                ),
                new EntityUser(
                    $booking->user->id,
                    $booking->user->name
                ),
                new DateRange(\Carbon\Carbon::parse($booking->start_date), \Carbon\Carbon::parse($booking->end_date)),
                $booking->occupants,
            );
            $entityBooking->setTotalPrice($booking->total_price);
            $entityBooking->setBookStatus($booking->status);
            return $entityBooking;
        }
        return null;
    }
}
