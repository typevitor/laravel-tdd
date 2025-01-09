<?php

namespace App\Exceptions\User;

class UserEmptyNameException extends \Exception
{
    protected $message = 'User name cannot be empty';
}
