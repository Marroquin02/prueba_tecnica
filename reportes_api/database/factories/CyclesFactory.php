<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CyclesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $year = fake()->numberBetween(2021, 2025);
        $cycle = fake()->randomElement(['I', 'II', 'III']);
        
        return [
            'name' => "Ciclo {$cycle}-{$year}"
        ];
    }
}
