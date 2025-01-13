<?php

namespace App\Domain\Cancelation;

class PartialRefund implements RefundRuleInterface
{
    function calculateRefund(int $totalPrice): int
    {
        return (int) round($totalPrice * 0.5, 0);
    }
}
