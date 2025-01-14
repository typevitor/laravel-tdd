<?php

namespace App\Application;

use App\Domain\Entities\Property;
use App\Domain\Entities\User;
use App\Repository\IPropertyRepository;

class PropertyService
{
    public function __construct(private readonly IPropertyRepository $iPropertyRepository)
    {
    }

    public function findById(string $id): Property|null
    {
        return $this->iPropertyRepository->findById($id);
    }

    public function save(Property $property): void
    {
        $this->iPropertyRepository->save($property);
    }
}
