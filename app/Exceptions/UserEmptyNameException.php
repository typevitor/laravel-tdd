<?php

namespace App\Exceptions;

class UserEmptyNameException extends \Exception
{
    protected $message = 'User name cannot be empty';
}
