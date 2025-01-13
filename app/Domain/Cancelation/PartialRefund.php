<?php

namespace App\Domain\Cancelation;

class PartialRefund implements RefundRuleInterface
{
    public function calculateRefund(int $totalPrice): int
    {
        return (int) round($totalPrice * 0.5, 0);
    }
}
