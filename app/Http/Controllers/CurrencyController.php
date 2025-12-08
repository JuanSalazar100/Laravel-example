<?php

namespace App\Http\Controllers;

use InvalidArgumentException;

class CurrencyController extends Controller
{
    /**
     * Convierte una cantidad de una moneda a otra aplicando comisión.
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

        $fromCurrency = strtoupper($fromCurrency);
        $toCurrency = strtoupper($toCurrency);

        $rates = [
            'USD' => 1.0,
            'MXN' => 17.0,
            'EUR' => 0.92,
        ];

        if (! isset($rates[$fromCurrency], $rates[$toCurrency])) {
            throw new InvalidArgumentException('Moneda no válida.');
        }

        $amountInUSD = $amount / $rates[$fromCurrency];
        $convertedAmount = $amountInUSD * $rates[$toCurrency];
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
