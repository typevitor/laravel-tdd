<?php

namespace App\Domain\Cancelation;

class NoRefund implements RefundRuleInterface
{
    function calculateRefund(int $totalPrice): int
    {
        return $totalPrice;
    }
}
