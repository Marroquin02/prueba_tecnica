<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class MaterialsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $materials = [
            'Cálculo I',
            'Cálculo II',
            'Álgebra Lineal',
            'Física I',
            'Física II',
            'Química General',
            'Programación I',
            'Programación II',
            'Base de Datos',
            'Estructuras de Datos',
            'Algoritmos',
            'Sistemas Operativos',
            'Redes de Computadoras',
            'Desarrollo Web',
            'Ingeniería de Software',
            'Contabilidad Financiera',
            'Microeconomía',
            'Macroeconomía',
            'Administración I',
            'Administración II',
            'Marketing',
            'Finanzas',
            'Estadística I',
            'Estadística II',
            'Metodología de la Investigación',
            'Ética Profesional',
            'Comunicación Oral y Escrita',
            'Inglés I',
            'Inglés II',
            'Inglés III',
            'Filosofía',
            'Historia de El Salvador',
            'Matemática Discreta',
            'Teoría de Autómatas',
            'Compiladores',
            'Inteligencia Artificial',
            'Aprendizaje Automático',
            'Seguridad Informática',
            'Auditoría de Sistemas',
            'Gestión de Proyectos'
        ];

        return [
            'name' => fake()->unique()->randomElement($materials),
            'uv' => fake()->numberBetween(3, 5)
        ];
    }
}
