<?php

namespace App\Exceptions\Property;

class PropertyNameEmptyException extends \Exception
{
    protected $message = 'Property name cannot be empty';
}
