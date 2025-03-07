<?php

namespace App\Repository;

use App\Domain\Entities\Property as EntitiesProperty;
use App\Models\Property;

class EloquentPropertyRepository implements IPropertyRepository
{
    public function __construct()
    {
    }

    public function findById(int $id): EntitiesProperty|null
    {
        $modelProperty = Property::find($id);
        if ($modelProperty) {
            return new EntitiesProperty(
                $modelProperty->id,
                $modelProperty->name,
                $modelProperty->description,
                $modelProperty->max_occupants,
                $modelProperty->price_per_night
            );
        }
        return null;
    }

    public function save(EntitiesProperty $property): void
    {
        Property::create([
            'id' => $property->getId(),
            'name' => $property->getName(),
            'description' => $property->getDescription(),
            'max_occupants' => $property->getMaxOccupants(),
            'price_per_night' => $property->getPricePerNight(),
        ]);
    }
}
