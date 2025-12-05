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
     * Procesa una orden calculando subtotal, IVA, descuento y total.
     *
     * @param  array<int, array{price: mixed, quantity: mixed}>  $items
     * @return array{
     *     subtotal: float,
     *     tax: float,
     *     discount: float,
     *     total: float,
     *     items_count: int
     * }
     */
    public function processOrder(array $items, ?float $discount = null): array
    {
        if ($items === []) {
            throw new InvalidArgumentException('La lista de items no puede estar vacía.');
        }

        $subtotal = 0.0;
        $itemsCount = 0;

        foreach ($items as $item) {
            // Validación de tipo a runtime
            if (! is_numeric($item['price']) || ! is_numeric($item['quantity'])) {
                throw new InvalidArgumentException('price y quantity deben ser numéricos.');
            }

            // Conversión segura a float/int
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

        /**
     * Calcula el resumen de un préstamo con pagos mensuales fijos.
     *
     * @param  float  $principal   Monto del préstamo (debe ser mayor que 0).
     * @param  float  $annualRate  Tasa de interés anual en porcentaje (ej. 12.5 para 12.5%).
     * @param  int    $months      Plazo del préstamo en meses (debe ser mayor que 0).
     *
     * @return array{
     *     monthly_payment: float,
     *     total_interest: float,
     *     total_paid: float,
     *     effective_annual_rate: float
     * }
     */
    public function calculateLoanSummary(float $principal, float $annualRate, int $months): array
    {
        // Validaciones básicas
        if ($principal <= 0) {
            throw new InvalidArgumentException('El monto del préstamo debe ser mayor que 0.');
        }

        if ($annualRate < 0) {
            throw new InvalidArgumentException('La tasa anual no puede ser negativa.');
        }

        if ($months <= 0) {
            throw new InvalidArgumentException('El número de meses debe ser mayor que 0.');
        }

        // Tasa mensual (por ejemplo 12% anual -> 1% mensual)
        $monthlyRate = $annualRate / 12 / 100;

        // Si la tasa es 0, el pago mensual es simplemente principal / meses
        if ($monthlyRate === 0.0) {
            $monthlyPayment = $principal / $months;
            $totalPaid = $principal;
            $totalInterest = 0.0;
            $effectiveAnnualRate = 0.0;
        } else {
            // Fórmula de anualidad para pagos fijos
            $monthlyPayment = $principal * ($monthlyRate / (1 - pow(1 + $monthlyRate, -$months)));
            $totalPaid = $monthlyPayment * $months;
            $totalInterest = $totalPaid - $principal;

            // Tasa anual efectiva a partir de la tasa mensual
            $effectiveAnnualRate = (pow(1 + $monthlyRate, 12) - 1) * 100;
        }

        return [
            'monthly_payment'       => round($monthlyPayment, 2),
            'total_interest'        => round($totalInterest, 2),
            'total_paid'            => round($totalPaid, 2),
            'effective_annual_rate' => round($effectiveAnnualRate, 2),
        ];
    }

}
