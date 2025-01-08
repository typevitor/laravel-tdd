<?php

namespace App\Exceptions;

class UserEmptyIdException extends \Exception
{
    protected $message = 'User Id cannot be empty';
}
