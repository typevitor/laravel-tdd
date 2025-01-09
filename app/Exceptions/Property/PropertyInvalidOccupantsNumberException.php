<?php

namespace App\Exceptions\Property;

class PropertyInvalidOccupantsNumberException extends \Exception
{
    protected $message = 'Property occupants quantity cannot be 0 or lower';
}
