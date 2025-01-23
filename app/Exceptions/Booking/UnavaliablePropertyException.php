<?php

namespace App\Exceptions\Booking;

class UnavaliablePropertyException extends \Exception
{
    protected $message = 'Property is unavaliable for given date range.';
    protected $code = 409;
}
