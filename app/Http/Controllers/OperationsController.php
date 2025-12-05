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
     * @param  float  $principal  Monto del préstamo (debe ser mayor que 0).
     * @param  float  $annualRate  Tasa de interés anual en porcentaje (ej. 12.5 para 12.5%).
     * @param  int  $months  Plazo del préstamo en meses (debe ser mayor que 0).
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
            'monthly_payment' => round($monthlyPayment, 2),
            'total_interest' => round($totalInterest, 2),
            'total_paid' => round($totalPaid, 2),
            'effective_annual_rate' => round($effectiveAnnualRate, 2),
        ];
    }

    /**
     * Convierte una cantidad de una moneda a otra aplicando comisión.
     *
     * @param  float  $amount  Cantidad a convertir (debe ser mayor a 0).
     * @param  string  $fromCurrency  Moneda origen (USD, MXN, EUR).
     * @param  string  $toCurrency  Moneda destino (USD, MXN, EUR).
     * @param  float  $commission  Comisión en porcentaje (0 a 10).
     * @return array{
     *   original_amount: float,
     *   converted_amount: float,
     *   commission_amount: float,
     *   final_amount: float,
     *   rate_used: float
     * }
     */
    public function convertCurrency(
        float $amount,
        string $fromCurrency,
        string $toCurrency,
        float $commission = 0
    ): array {
        if ($amount <= 0) {
            throw new InvalidArgumentException('La cantidad debe ser mayor a 0.');
        }

        if ($commission < 0 || $commission > 10) {
            throw new InvalidArgumentException('La comisión debe estar entre 0% y 10%.');
        }

        $rates = [
            'USD' => 1.0,
            'MXN' => 17.0,
            'EUR' => 0.92,
        ];

        if (! isset($rates[$fromCurrency], $rates[$toCurrency])) {
            throw new InvalidArgumentException('Moneda no válida.');
        }

        // Conversión a USD como base
        $amountInUSD = $amount / $rates[$fromCurrency];

        // Conversión a moneda destino
        $convertedAmount = $amountInUSD * $rates[$toCurrency];

        // Comisión
        $commissionAmount = $convertedAmount * ($commission / 100);

        $finalAmount = $convertedAmount - $commissionAmount;

        return [
            'original_amount' => round($amount, 2),
            'converted_amount' => round($convertedAmount, 2),
            'commission_amount' => round($commissionAmount, 2),
            'final_amount' => round($finalAmount, 2),
            'rate_used' => round($rates[$toCurrency], 4),
        ];
    }
}
