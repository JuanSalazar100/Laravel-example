<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Http\Controllers\OperationsController;

class ExampleTest extends TestCase
{
    public function test_that_true_is_true(): void
    {
        $this->assertTrue(true);
    }

    public function test_int_addition_result(): void
    {
        $controller = new OperationsController();

        $result = $controller->addition(5, 6);

        $this->assertIsInt($result);
        $this->assertNotNull($result);
        $this->assertGreaterThan(5, $result);
    }
}
