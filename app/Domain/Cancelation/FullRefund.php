<?php

namespace App\Domain\Cancelation;

class FullRefund implements RefundRuleInterface
{
    function calculateRefund(int $totalPrice): int
    {
        return 0;
    }
}
