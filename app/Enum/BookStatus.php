<?php

namespace App\Enum;

enum BookStatus: string
{
    case CONFIRMED = 'CONFIRMED';
    case CANCELLED = 'CANCELLED';
}
