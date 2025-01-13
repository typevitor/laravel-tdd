<?php

namespace App\Domain\Cancelation;

interface RefundRuleInterface
{
    public function calculateRefund(int $totalPrice): int;
}
