<?php

namespace App\Http\Controllers;

class OperationsController extends Controller
{
    public function addition(int $a, int $b): int
    {
        return $a + $b;
    }
}




