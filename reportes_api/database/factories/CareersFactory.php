<?php

namespace Database\Factories;

use App\Models\Faculties;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CareersFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $careersByFaculty = [
            'Ingeniería y Arquitectura' => [
                'Ingeniería Civil',
                'Ingeniería Eléctrica',
                'Ingeniería Mecánica',
                'Ingeniería Industrial',
                'Ingeniería de Sistemas',
                'Arquitectura'
            ],
            'Ciencias Económicas' => [
                'Licenciatura en Contaduría Pública',
                'Licenciatura en Administración de Empresas',
                'Licenciatura en Economía',
                'Licenciatura en Mercadeo Internacional'
            ],
            'Ciencias y Humanidades' => [
                'Licenciatura en Letras',
                'Licenciatura en Idioma Inglés',
                'Licenciatura en Psicología',
                'Licenciatura en Periodismo'
            ]
        ];

        $allCareers = array_merge(...array_values($careersByFaculty));

        return [
            'name' => fake()->unique()->randomElement($allCareers),
            'materials' => fake()->numberBetween(35, 45),
            'faculty_id' => Faculties::factory()
        ];
    }
}
