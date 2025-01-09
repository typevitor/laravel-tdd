<?php

namespace App\Exceptions\Property;

class PropertyMaxOccupantsException extends \Exception
{
    protected int $maxOccupants;

    public function __construct(int $maxOccupants)
    {
        $this->maxOccupants = $maxOccupants;
        parent::__construct('Property occupants exceed. Max allowed is: '. $maxOccupants);
    }
}
