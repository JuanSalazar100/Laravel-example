<?php

namespace Tests\Unit;

use App\Http\Controllers\OperationsController;
use Tests\TestCase;

class OperationsControllerTest extends TestCase
{
    public function test_int_addition(): void
    {
        $controller = new OperationsController;

        $result = $controller->addition(5, 6);

        $this->assertIsInt($result);
        $this->assertNotNull($result);
        $this->assertGreaterThan(5, $result);
        $this->assertEquals(11, $result);
    }
}
