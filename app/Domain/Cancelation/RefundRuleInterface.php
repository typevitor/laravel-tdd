<?php

namespace App\Domain\Cancelation;

interface RefundRuleInterface
{
    function calculateRefund(int $totalPrice): int;
}
