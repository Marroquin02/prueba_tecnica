<?php

namespace Database\Factories;

use App\Models\Careers;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class StudentsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $firstNames = [
            'José',
            'María',
            'Carlos',
            'Ana',
            'Luis',
            'Carmen',
            'Miguel',
            'Rosa',
            'Francisco',
            'Isabel',
            'Antonio',
            'Claudia',
            'Manuel',
            'Patricia',
            'Roberto',
            'Laura',
            'Jorge',
            'Gabriela',
            'Ricardo',
            'Mónica',
            'Fernando',
            'Silvia',
            'Diego',
            'Beatriz',
            'Alejandro',
            'Valentina',
            'Sergio',
            'Andrea',
            'Rafael',
            'Carolina',
            'Oscar',
            'Daniela',
            'Alberto',
            'Cristina',
            'Héctor',
            'Mariana',
            'Eduardo',
            'Sofía'
        ];

        $lastNames = [
            'García',
            'Rodríguez',
            'Martínez',
            'Hernández',
            'López',
            'González',
            'Pérez',
            'Sánchez',
            'Ramírez',
            'Torres',
            'Flores',
            'Rivera',
            'Gómez',
            'Díaz',
            'Cruz',
            'Morales',
            'Reyes',
            'Gutiérrez',
            'Ortiz',
            'Chávez',
            'Castillo',
            'Vázquez',
            'Mendoza',
            'Romero'
        ];

        $year = fake()->numberBetween(18, 26); // 2018-2026
        $solicitud = fake()->numberBetween(1, 2000);
        $carnet = sprintf('%06d%02d', $solicitud, $year);

        return [
            'carnet' => $carnet,
            'email' => "{$carnet}@uca.edu.sv",
            'first_name' => fake()->randomElement($firstNames),
            'last_name' => fake()->randomElement($lastNames),
            'career_id' => Careers::factory(),
            'ingress' => 2000 + $year,
            'status' => fake()->randomElement(['activo', 'activo', 'activo', 'activo', 'inactivo'])
        ];
    }
}
