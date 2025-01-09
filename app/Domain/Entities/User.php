<?php

namespace App\Domain\Entities;

use App\Exceptions\User\UserEmptyIdException;
use App\Exceptions\User\UserEmptyNameException;

class User
{
    public function __construct(private readonly string $id, private readonly string $name)
    {
        if ($id === "") {
            throw new UserEmptyIdException();
        }

        if ($name === "") {
            throw new UserEmptyNameException();
        }
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
