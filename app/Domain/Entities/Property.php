<?php

namespace App\Domain\Entities;

class Property
{
    public function __construct(
        private readonly string $id,
        private readonly string $name,
        private readonly string $description,
        private readonly int $maxOccupants,
        private readonly int $pricePerNight,
    )
    {

    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getMaxOccupants(): int
    {
        return $this->maxOccupants;
    }

    public function getPricePerNight(): int
    {
        return $this->pricePerNight;
    }
}
