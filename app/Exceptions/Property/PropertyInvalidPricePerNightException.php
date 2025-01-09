<?php

namespace App\Exceptions\Property;

class PropertyInvalidPricePerNightException extends \Exception
{
    protected $message = 'Property price per night cannot be 0 or lower';
}
