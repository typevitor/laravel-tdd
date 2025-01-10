<?php

namespace App\Exceptions\Booking;

class BookMaximumOccupantsException extends \Exception
{
    protected int $maxOccupants;

    public function __construct(int $maxOccupants)
    {
        $this->maxOccupants = $maxOccupants;
        parent::__construct('Number of occupants should be lower than: ' . $maxOccupants);
    }
}
