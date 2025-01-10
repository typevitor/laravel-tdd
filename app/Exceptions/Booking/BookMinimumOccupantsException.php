<?php

namespace App\Exceptions\Booking;

class BookMinimumOccupantsException extends \Exception
{
    protected $message = 'Number of occupants should be higher than zero.';
}
