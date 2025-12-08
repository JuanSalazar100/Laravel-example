<?php

namespace Tests\Unit;

use App\Http\Controllers\CurrencyController;
use InvalidArgumentException;
use Tests\TestCase;

class ConvertCurrencyTest extends TestCase
{
    public function test_convert_currency_usd_to_mxn_con_comision(): void
    {
        $controller = new CurrencyController;

        // 100 USD a MXN con 5% de comisión
        $result = $controller->convertCurrency(100, 'USD', 'MXN', 5);

        $this->assertEquals(100.00, $result['original_amount']);
        $this->assertEquals(1700.00, $result['converted_amount']);
        $this->assertEquals(85.00, $result['commission_amount']);
        $this->assertEquals(1615.00, $result['final_amount']);
        $this->assertEquals(17.0000, $result['rate_used']);
    }

    public function test_convert_currency_mxn_to_usd_sin_comision(): void
    {
        $controller = new CurrencyController;

        $result = $controller->convertCurrency(1700, 'MXN', 'USD', 0);

        $this->assertEquals(100.00, $result['converted_amount']);
        $this->assertEquals(0.00, $result['commission_amount']);
        $this->assertEquals(100.00, $result['final_amount']);
    }

    public function test_convert_currency_eur_to_usd(): void
    {
        $controller = new CurrencyController;

        $result = $controller->convertCurrency(92, 'EUR', 'USD');

        $this->assertEquals(100.00, $result['converted_amount']);
        $this->assertEquals(100.00, $result['final_amount']);
    }

    public function test_convert_currency_monto_invalido(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $controller = new CurrencyController;
        $controller->convertCurrency(0, 'USD', 'MXN');
    }

    public function test_convert_currency_comision_invalida(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $controller = new CurrencyController;
        $controller->convertCurrency(100, 'USD', 'MXN', 20);
    }

    public function test_convert_currency_moneda_invalida(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $controller = new CurrencyController;
        $controller->convertCurrency(100, 'USD', 'JPY');
    }
}
