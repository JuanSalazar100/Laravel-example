<?php

namespace Tests\Unit;

use App\Http\Controllers\OperationsController;
use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    public function test_that_true_is_true(): void
    {
        $this->assertTrue(true);
    }

    public function test_int_addition_result(): void
    {
        $controller = new OperationsController;

        $result = $controller->addition(5, 6);

        $this->assertIsInt($result);
        $this->assertNotNull($result);
        $this->assertGreaterThan(5, $result);
    }

    public function test_int_negative_adittion(): void
    {
        $controller = new OperationsController;

        $result = $controller->addition(5, -6);

        $this->assertIsInt($result);
        $this->assertNotNull($result);
        $this->assertEquals(-1, $result);
    }
}
