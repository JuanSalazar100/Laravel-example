<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Http\Controllers\OperationsController;

class TempConvTest extends TestCase
{
    public function test_convert_celsius_to_fahrenheit()
    {
        $controller = new OperationsController();

        $result = $controller->convertTemperature(0, 'c_to_f');

        $this->assertEquals(32, $result);
    }

    public function test_convert_fahrenheit_to_celsius()
    {
        $controller = new OperationsController();

        $result = $controller->convertTemperature(32, 'f_to_c');

        $this->assertEquals(0, $result);
    }

    public function test_convert_throws_exception_with_invalid_scale()
    {
        $this->expectException(\InvalidArgumentException::class);

        $controller = new OperationsController();

        $controller->convertTemperature(100, 'xyz');
    }
}
