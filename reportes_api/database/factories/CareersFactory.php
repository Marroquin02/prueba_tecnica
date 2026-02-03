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
                'Arquitectura',
                'Ingeniería Civil',
                'Ingeniería Eléctrica',
                'Ingeniería Mecánica',
                'Ingeniería Industrial',
                'Ingeniería Informática',
                'Ingeniería Química',
                'Ingeniería Energética',
                'Ingeniería de Alimentos'
            ],
            'Ciencias Economicas y Empresariales' => [
                'Licenciatura en Administración de Empresas',
                'Licenciatura en Contaduría Pública',
                'Licenciatura en Economía',
                'Licenciatura en Finanzas',
                'Técnico en Contaduría',
                'Licenciatura en Comunicación Social',
                'Licenciatura en Mercadeo',
                'Técnico en Marketing Digital',
                'Técnico en Producción Multimedia'
            ],
            'Ciencias Sociales y Humanidades' => [
                'Licenciatura en Ciencias Sociales',
                'Licenciatura en Filosofía',
                'Licenciatura en Idioma Inglés',
                'Licenciatura en Psicología',
                'Licenciatura en Teología',
                'Licenciatura en Ciencias Jurídicas',
                'Profesorado en Teología',
                'Profesorado en Idioma Inglés para Tercer Ciclo de Educación Básica y Educación Media',
                'Profesorado en Educación Básica para Primero y Segundo Ciclos',
                'Licenciatura en Educación Básica para Primero y Segundo Ciclos'
            ],
        ];

        $allCareers = array_merge(...array_values($careersByFaculty));

        return [
            'name' => fake()->unique()->randomElement($allCareers),
            'materials' => fake()->numberBetween(35, 45),
            'faculty_id' => Faculties::factory()
        ];
    }
}
