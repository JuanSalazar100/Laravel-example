<?php

namespace App\Http\Controllers;

use InvalidArgumentException;

class OperationsController extends Controller
{
    public function addition(int $a, int $b): int
    {
        return $a + $b;
    }
}
