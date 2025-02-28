<?php

namespace App\Repository;

use App\Domain\Entities\Property;

interface IPropertyRepository
{
    public function findById(int $id): Property|null;
    public function save(Property $property): void;
}
