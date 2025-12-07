<?php

namespace App\Http\Controllers;

class OperationsController extends Controller
{
    public function addition(int $a, int $b): int
    {
        return $a + $b;
    }

public function convertTemperature(float $value, string $scale): float
{
    $scale = strtolower($scale);

    return match ($scale) {
        'c_to_f' => ($value * 9/5) + 32,
        'f_to_c' => ($value - 32) * 5/9,
        default  => throw new \InvalidArgumentException("Escala inválida. Usa 'c_to_f' o 'f_to_c'."),
    };
}
}

