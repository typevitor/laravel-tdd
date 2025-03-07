<?php

namespace App\Http\Controllers\Api;

use App\Application\BookingService;
use App\Application\DTO\CreateBookingDTO;
use App\Http\Controllers\Controller;

class ApiBookingsController extends Controller
{
    public function __construct(private readonly BookingService $bookingService)
    {
    }

    public function store()
    {
        try {
            $startDate = \Carbon\Carbon::parse(request('start_date'));
            $endDate = \Carbon\Carbon::parse(request('end_date'));
            if ($startDate->gte($endDate)) {
                return response()->json(['message' => 'Invalid date range'], 422);
            }
            $bookingDTO = new CreateBookingDTO(
                propertyId: request('property_id'),
                userId: request('user_id'),
                startDate: $startDate,
                endDate: $endDate,
                occupants: request('occupants')
            );
            $booking = $this->bookingService->save($bookingDTO);
            return response()->json([
                'message' => 'Booking created successfully',
                'data' => [
                    'id' => $booking->getId(),
                    'property_id' => $booking->getProperty()->getId(),
                    'user_id' => $booking->getUser()->getId(),
                    'start_date' => $booking->getDateRange()->getStartDate()->format('Y-m-d'),
                    'end_date' => $booking->getDateRange()->getEndDate()->format('Y-m-d'),
                    'occupants' => $booking->getOccupants(),
                    'total_price' => $booking->getTotalPrice()
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function cancel($bookingId)
    {
        try {
            $this->bookingService->cancel($bookingId);
            return response()->json(['message' => 'Booking canceled successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
