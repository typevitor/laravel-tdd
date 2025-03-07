<?php

namespace App\Exceptions\Booking;

class BookingNotFoundException extends \Exception
{
    protected $message = 'Booking not found';
}
