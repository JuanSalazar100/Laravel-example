<?php

namespace Tests\Unit;

use App\Http\Controllers\OrderController;
use InvalidArgumentException;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    public function test_process_order_funciona_correctamente(): void
    {
        $controller = new OrderController;

        $items = [
            ['price' => 100, 'quantity' => 2],
            ['price' => 50, 'quantity' => 1],
        ];

        $result = $controller->processOrder($items, 10);

        $this->assertEquals(250.00, $result['subtotal']);
        $this->assertEquals(40.00, $result['tax']);
        $this->assertEquals(25.00, $result['discount']);
        $this->assertEquals(265.00, $result['total']);
        $this->assertEquals(3, $result['items_count']);
    }

    public function test_process_order_falla_con_items_vacios(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $controller = new OrderController;
        $controller->processOrder([]);
    }

    public function test_process_order_falla_con_datos_invalidos(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $controller = new OrderController;

        $items = [
            ['price' => -100, 'quantity' => 2],
        ];

        $controller->processOrder($items);
    }
}
