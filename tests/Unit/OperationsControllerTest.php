<?php

namespace Tests\Unit;

use App\Http\Controllers\OperationsController;
use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_that_true_is_true(): void
    {
        $this->assertTrue(true);
    }

    public function test_int_addition(): void
    {
        $controller = new OperationsController;

        $this->assertIsInt($controller->addition(5, 6));
        $this->assertNotNull($controller->addition(5, 6));
        $this->assertGreaterThan(5, $controller->addition(5, 6));
    }
}