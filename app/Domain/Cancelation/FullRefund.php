<?php

namespace App\Domain\Cancelation;

class FullRefund implements RefundRuleInterface
{
    public function calculateRefund(int $totalPrice): int
    {
        return 0;
    }
}
