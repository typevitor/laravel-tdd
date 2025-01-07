<?php

namespace App\Exceptions;

class DateRangeException extends \Exception
{
    protected $message = 'End date must be after start date';
}
