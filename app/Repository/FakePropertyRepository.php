<?php

namespace App\Repository;

use App\Domain\Entities\Property;

class FakePropertyRepository implements IPropertyRepository
{
    private array $properties = array();

    public function __construct()
    {
        $this->properties = [
            new Property('1', 'Casa', 'casa de campo', 5, 10000),
        ];
    }

    public function findById(string $id): Property|null
    {
        foreach ($this->properties as $property) {
            if ($property->getId() === $id) return $property;
        }

        return null;
    }

    public function save(Property $property): void
    {
        $this->properties[] = $property;
    }
}
