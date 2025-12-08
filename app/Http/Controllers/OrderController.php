<?php

namespace App\Http\Controllers;

use InvalidArgumentException;

class OrderController extends Controller
{
    /**
     * Procesa una orden calculando subtotal, IVA, descuento y total.
     */
    public function processOrder(array $items, ?float $discount = null): array
    {
        if ($items === []) {
            throw new InvalidArgumentException('La lista de items no puede estar vacía.');
        }

        $subtotal = 0.0;
        $itemsCount = 0;

        foreach ($items as $item) {
            if (! is_numeric($item['price']) || ! is_numeric($item['quantity'])) {
                throw new InvalidArgumentException('price y quantity deben ser numéricos.');
            }

            $price = (float) $item['price'];
            $quantity = (float) $item['quantity'];

            if ($price < 0 || $quantity <= 0) {
                throw new InvalidArgumentException('Valores inválidos: price o quantity.');
            }

            $subtotal += $price * $quantity;
            $itemsCount += (int) $quantity;
        }

        $tax = $subtotal * 0.16;

        $discountAmount = 0.0;
        if ($discount !== null) {
            if ($discount < 0 || $discount > 100) {
                throw new InvalidArgumentException('El descuento debe estar entre 0 y 100.');
            }
            $discountAmount = $subtotal * ($discount / 100);
        }

        $total = $subtotal + $tax - $discountAmount;

        return [
            'subtotal' => round($subtotal, 2),
            'tax' => round($tax, 2),
            'discount' => round($discountAmount, 2),
            'total' => round($total, 2),
            'items_count' => $itemsCount,
        ];
    }
}
