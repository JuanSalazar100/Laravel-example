<?php

namespace Tests\Unit;

use App\Http\Controllers\LoanController;
use InvalidArgumentException;
use Tests\TestCase;

class CalculateLoanSummaryTest extends TestCase
{
    public function test_calculate_loan_summary_with_valid_data(): void
    {
        $controller = new LoanController;

        $result = $controller->calculateLoanSummary(10000.0, 12.0, 12);

        // Valores esperados calculados con la misma fórmula
        $this->assertEquals(888.49, $result['monthly_payment']);
        $this->assertEquals(661.85, $result['total_interest']);
        $this->assertEquals(10661.85, $result['total_paid']);
        $this->assertEquals(12.68, $result['effective_annual_rate']);
    }

    public function test_calculate_loan_summary_fails_with_zero_principal(): void
    {
        $controller = new LoanController;

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('El monto del préstamo debe ser mayor que 0.');

        $controller->calculateLoanSummary(0.0, 10.0, 12);
    }

    public function test_calculate_loan_summary_fails_with_negative_rate(): void
    {
        $controller = new LoanController;

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('La tasa anual no puede ser negativa.');

        $controller->calculateLoanSummary(10000.0, -1.0, 12);
    }

    public function test_calculate_loan_summary_fails_with_invalid_months(): void
    {
        $controller = new LoanController;

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('El número de meses debe ser mayor que 0.');

        $controller->calculateLoanSummary(10000.0, 10.0, 0);
    }
}
