<?php

namespace Tests\Unit;

use App\Services\CurrencyService;
use PHPUnit\Framework\TestCase;

class CurrencyTest extends TestCase
{
    public function test_convert_usd_to_eur_successful()
    {
        $result = (new CurrencyService())->convert(100, 'usd', 'eur');
        $this->assertEquals(98, $result);
    }
}
