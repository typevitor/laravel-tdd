<?php

namespace App\Domain\Cancelation;

use App\Domain\Entities\Booking;

class RefundFactory
{
    static function getRefundRule(int $daysUntilCheckin): RefundRuleInterface
    {
        return match (true) {
            ($daysUntilCheckin > 7) => new FullRefund(),
            ($daysUntilCheckin <= 7 && $daysUntilCheckin > 1) => new PartialRefund(),
            default => new NoRefund()
        };
    }
}
