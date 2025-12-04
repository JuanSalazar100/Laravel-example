<?php

namespace App\Http\Controllers;
use InvalidArgumentException;


class OperationsController extends Controller
{
    public function addition(int $a, int $b): int
    {
        return $a + $b;
    }

    /**
     * FUNCIÓN DIONICIO
     * Procesa una orden calculando subtotal, IVA, descuento y total.
     *
     * @param array $items Lista de productos con price y quantity
     * @param float|null $discount Porcentaje de descuento (0 - 100)
     * @return array
     */
    public function processOrder(array $items, ?float $discount = null): array
    {
        if (empty($items)) {
            throw new InvalidArgumentException("La lista de items no puede estar vacía.");
        }

        $subtotal = 0;
        $itemsCount = 0;

        foreach ($items as $item) {
            if (!isset($item["price"]) || !isset($item["quantity"])) {
                throw new InvalidArgumentException("Cada item debe incluir price y quantity.");
            }

            if (!is_numeric($item["price"]) || !is_numeric($item["quantity"])) {
                throw new InvalidArgumentException("price y quantity deben ser numéricos.");
            }

            if ($item["price"] < 0 || $item["quantity"] <= 0) {
                throw new InvalidArgumentException("Valores inválidos: price o quantity.");
            }

            $subtotal += $item["price"] * $item["quantity"];
            $itemsCount += $item["quantity"];
        }

        // IVA del 16%
        $tax = $subtotal * 0.16;

        // Descuento opcional
        if ($discount !== null) {
            if ($discount < 0 || $discount > 100) {
                throw new InvalidArgumentException("El descuento debe estar entre 0 y 100.");
            }
            $discountAmount = $subtotal * ($discount / 100);
        } else {
            $discountAmount = 0;
        }

        $total = $subtotal + $tax - $discountAmount;

        return [
            "subtotal" => round($subtotal, 2),
            "tax" => round($tax, 2),
            "discount" => round($discountAmount, 2),
            "total" => round($total, 2),
            "items_count" => $itemsCount
        ];
    }


}
