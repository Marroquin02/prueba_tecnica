<?php

namespace Database\Factories;

use App\Models\Cycles;
use App\Models\Materials;
use App\Models\Students;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class GradesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Distribución realista de notas
        $grades = [5.0, 5.5, 6.0, 6.5, 7.0, 7.5, 8.0, 8.5, 9.0, 9.5, 10.0];
        $weights = [1, 2, 4, 6, 8, 8, 7, 5, 3, 2, 1];

        $totalWeight = array_sum($weights);
        $random = fake()->numberBetween(0, $totalWeight - 1);

        $grade = 7.0;
        $cumulative = 0;
        foreach ($grades as $index => $gradeValue) {
            $cumulative += $weights[$index];
            if ($random < $cumulative) {
                $grade = $gradeValue;
                break;
            }
        }

        return [
            'material_id' => Materials::factory(),
            'student_id' => Students::factory(),
            'cycle_id' => Cycles::factory(),
            'grade' => $grade
        ];
    }
}
