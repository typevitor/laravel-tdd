<?php

namespace App\Exceptions\Booking;

class BookingAlreadyCancelledException extends \Exception
{
    protected $message = 'Bookins is already cancelled.';
}
