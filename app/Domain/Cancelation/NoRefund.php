<?php

namespace App\Domain\Cancelation;

class NoRefund implements RefundRuleInterface
{
    public function calculateRefund(int $totalPrice): int
    {
        return $totalPrice;
    }
}
