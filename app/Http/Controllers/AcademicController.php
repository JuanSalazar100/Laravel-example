<?php

namespace App\Http\Controllers;

use InvalidArgumentException;

class AcademicController extends Controller
{
    /**
     * @param  array<int, array{grade: mixed, credits: mixed}>  $courses
     * @return array{weighted_average: float, total_credits: int, total_courses: int, status: string, performance_percentage: float}
     */
    public function calculateWeightedGrade(array $courses, float $maxGrade = 100.0): array
    {
        if ($courses === []) {
            throw new InvalidArgumentException('La lista de materias no puede estar vacía.');
        }

        if ($maxGrade <= 0) {
            throw new InvalidArgumentException('La calificación máxima debe ser mayor a 0.');
        }

        $totalWeightedGrade = 0.0;
        $totalCredits = 0;

        foreach ($courses as $course) {
            if (! isset($course['grade']) || ! isset($course['credits'])) {
                throw new InvalidArgumentException('Cada materia debe tener "grade" y "credits".');
            }

            if (! is_numeric($course['grade']) || ! is_numeric($course['credits'])) {
                throw new InvalidArgumentException('La calificación y los créditos deben ser numéricos.');
            }

            $grade = (float) $course['grade'];
            $credits = (int) $course['credits'];

            if ($grade < 0 || $grade > $maxGrade) {
                throw new InvalidArgumentException("La calificación debe estar entre 0 y {$maxGrade}.");
            }

            if ($credits <= 0) {
                throw new InvalidArgumentException('Los créditos deben ser mayores a 0.');
            }

            $totalWeightedGrade += $grade * $credits;
            $totalCredits += $credits;
        }

        $weightedAverage = $totalWeightedGrade / $totalCredits;
        $performancePercentage = ($weightedAverage / $maxGrade) * 100;

        $status = match (true) {
            $performancePercentage >= 90 => 'Excelente',
            $performancePercentage >= 80 => 'Muy bueno',
            $performancePercentage >= 70 => 'Bueno',
            $performancePercentage >= 60 => 'Aprobado',
            default => 'Reprobado'
        };

        return [
            'weighted_average' => round($weightedAverage, 2),
            'total_credits' => $totalCredits,
            'total_courses' => count($courses),
            'status' => $status,
            'performance_percentage' => round($performancePercentage, 2),
        ];
    }
}
