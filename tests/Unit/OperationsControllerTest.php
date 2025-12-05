<?php

namespace Tests\Unit;

use App\Http\Controllers\OperationsController;
use InvalidArgumentException;
use Tests\TestCase;

class OperationsControllerTest extends TestCase
{
    public function test_process_order_funciona_correctamente()
    {
        $controller = new OperationsController;

        $items = [
            ['price' => 100, 'quantity' => 2],
            ['price' => 50, 'quantity' => 1],
        ];

        $result = $controller->processOrder($items, 10);

        $this->assertEquals(250, $result['subtotal']);
        $this->assertEquals(40, $result['tax']);
        $this->assertEquals(25, $result['discount']);
        $this->assertEquals(265, $result['total']);
        $this->assertEquals(3, $result['items_count']);
    }

    public function test_process_order_falla_con_items_vacios()
    {
        $controller = new OperationsController;
        $this->expectException(InvalidArgumentException::class);
        $controller->processOrder([]);
    }

    public function test_process_order_falla_con_datos_invalidos()
    {
        $controller = new OperationsController;

        $items = [
            ['price' => 'abc', 'quantity' => 1],
        ];

        $this->expectException(InvalidArgumentException::class);
        $controller->processOrder($items);
    }
}
