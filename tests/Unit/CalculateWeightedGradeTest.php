<?php

namespace Tests\Unit;

use App\Http\Controllers\AcademicController;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class CalculateWeightedGradeTest extends TestCase
{
    public function test_calculate_weighted_grade_con_datos_validos(): void
    {
        $controller = new AcademicController;

        $courses = [
            ['grade' => 90, 'credits' => 5],
            ['grade' => 85, 'credits' => 4],
            ['grade' => 95, 'credits' => 3],
            ['grade' => 95, 'credits' => 2],
        ];

        $result = $controller->calculateWeightedGrade($courses, 100);

        $this->assertEquals(89.58, $result['weighted_average']);
        // $this->assertEquals(12, $result['total_credits']);
        $this->assertEquals(3, $result['total_courses']);
        $this->assertEquals(14, $result['total_credits']);
        $this->assertEquals('Muy bueno', $result['status']);
    }

    public function test_calculate_weighted_grade_falla_con_array_vacio(): void
    {
        $controller = new AcademicController;

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('La lista de materias no puede estar vacía.');

        $controller->calculateWeightedGrade([]);
    }

    public function test_calculate_weighted_grade_falla_con_datos_invalidos(): void
    {
        $controller = new AcademicController;

        $courses = [
            ['grade' => 'A+', 'credits' => 5],
        ];

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('La calificación y los créditos deben ser numéricos.');

        $controller->calculateWeightedGrade($courses);
    }

    public function test_calculate_weighted_grade_falla_con_calificacion_fuera_de_rango(): void
    {
        $controller = new AcademicController;

        $courses = [
            ['grade' => 110, 'credits' => 5],
        ];

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('La calificación debe estar entre 0 y 100.');

        $controller->calculateWeightedGrade($courses);
    }
}
