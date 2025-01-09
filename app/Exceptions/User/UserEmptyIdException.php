<?php

namespace App\Exceptions\User;

class UserEmptyIdException extends \Exception
{
    protected $message = 'User Id cannot be empty';
}
