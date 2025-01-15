<?php

namespace App\Exceptions\Property;

class PropertyNotFoundException extends \Exception
{
    protected $message = 'Property not found';
    protected $code = 404;
}
