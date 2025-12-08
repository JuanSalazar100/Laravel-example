<?php

namespace App\Http\Controllers;

use InvalidArgumentException;

class LoanController extends Controller
{
    /**
     * Calcula el resumen financiero de un préstamo.
     *
     * @param  float  $principal  Monto del préstamo.
     * @param  float  $annualRate  Tasa anual en porcentaje.
     * @param  int    $months  Plazo en meses.
     * @return array{
     *   monthly_payment: float,
     *   total_interest: float,
     *   total_paid: float,
     *   effective_annual_rate: float
     * }
     */
    public function calculateLoanSummary(
        float $principal,
        float $annualRate,
        int $months
    ): array {
        if ($principal <= 0) {
            throw new InvalidArgumentException('El monto del préstamo debe ser mayor que 0.');
        }

        if ($annualRate < 0) {
            throw new InvalidArgumentException('La tasa anual no puede ser negativa.');
        }

        if ($months <= 0) {
            throw new InvalidArgumentException('El número de meses debe ser mayor que 0.');
        }

        $monthlyRate = $annualRate / 12 / 100;

        if ($monthlyRate === 0.0) {
            $monthlyPayment = $principal / $months;
            $totalPaid = $principal;
            $totalInterest = 0.0;
            $effectiveAnnualRate = 0.0;
        } else {
            $monthlyPayment = $principal * ($monthlyRate / (1 - pow(1 + $monthlyRate, -$months)));
            $totalPaid = $monthlyPayment * $months;
            $totalInterest = $totalPaid - $principal;
            $effectiveAnnualRate = (pow(1 + $monthlyRate, 12) - 1) * 100;
        }

        return [
            'monthly_payment' => round($monthlyPayment, 2),
            'total_interest' => round($totalInterest, 2),
            'total_paid' => round($totalPaid, 2),
            'effective_annual_rate' => round($effectiveAnnualRate, 2),
        ];
    }
}
