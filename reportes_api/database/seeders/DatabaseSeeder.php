<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Faculties;
use App\Models\Careers;
use App\Models\Materials;
use App\Models\Cycles;
use App\Models\Students;
use App\Models\Grades;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear Facultades
        echo "Creando facultades...\n";
        $faculties = [
            Faculties::create(['name' => 'Ingeniería y Arquitectura']),
            Faculties::create(['name' => 'Ciencias Económicas']),
            Faculties::create(['name' => 'Ciencias y Humanidades'])
        ];

        // Crear Carreras
        echo "Creando carreras...\n";
        $careers = [];
        $careersByFaculty = [
            $faculties[0]->id => [
                'Ingeniería Civil',
                'Ingeniería Eléctrica',
                'Ingeniería Mecánica',
                'Ingeniería Industrial',
                'Ingeniería de Sistemas',
                'Arquitectura'
            ],
            $faculties[1]->id => [
                'Licenciatura en Contaduría Pública',
                'Licenciatura en Administración de Empresas',
                'Licenciatura en Economía',
                'Licenciatura en Mercadeo Internacional'
            ],
            $faculties[2]->id => [
                'Licenciatura en Letras',
                'Licenciatura en Idioma Inglés',
                'Licenciatura en Psicología',
                'Licenciatura en Periodismo'
            ]
        ];
        foreach ($careersByFaculty as $facultyId => $careerNames) {
            foreach ($careerNames as $name) {
                $careers[] = Careers::create([
                    'name' => $name,
                    'materials' => fake()->numberBetween(35, 45),
                    'faculty_id' => $facultyId
                ]);
            }
        }

        // Crear Materias
        echo "Creando materias...\n";
        $materials = Materials::factory()->count(40)->create();


        // Crear Ciclos Académicos
        echo "Creando ciclos académicos...\n";
        $cycles = [];
        foreach ([2021, 2022, 2023, 2024, 2025] as $year) {
            foreach (['I', 'II', 'III'] as $cycle) {
                $cycles[] = Cycles::create(['name' => "Ciclo {$cycle}-{$year}"]);
            }
        }

        // Crear Estudiantes
        echo "Creando estudiantes...\n";
        $students = [];
        $usedCarnets = [];

        foreach (range(1, 100) as $i) {
            do {
                $year = fake()->numberBetween(18, 26);
                $solicitud = fake()->numberBetween(1, 2000);
                $carnet = sprintf('%06d%02d', $solicitud, $year);
            } while (in_array($carnet, $usedCarnets));

            $usedCarnets[] = $carnet;

            $students[] = Students::create([
                'carnet' => $carnet,
                'email' => "{$carnet}@uca.edu.sv",
                'first_name' => fake()->randomElement([
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
                    'Valentina'
                ]),
                'last_name' => fake()->randomElement([
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
                    'Flores'
                ]),
                'career_id' => fake()->randomElement($careers)->id,
                'ingress' => 2000 + $year,
                'status' => fake()->randomElement(['activo', 'activo', 'activo', 'activo', 'inactivo'])
            ]);
        }
        // Crear Calificaciones
        echo "Creando calificaciones...\n";
        $gradeCount = 0;
        $grades = [5.0, 5.5, 6.0, 6.5, 7.0, 7.5, 8.0, 8.5, 9.0, 9.5, 10.0];

        foreach ($students as $student) {
            $numGrades = fake()->numberBetween(5, 15);
            $usedCombinations = [];

            for ($i = 0; $i < $numGrades; $i++) {
                do {
                    $materialId = fake()->randomElement($materials)->id;
                    $cycleId = fake()->randomElement($cycles)->id;
                    $combination = "{$materialId}-{$cycleId}";
                } while (in_array($combination, $usedCombinations));

                $usedCombinations[] = $combination;

                Grades::create([
                    'material_id' => $materialId,
                    'student_id' => $student->carnet,
                    'cycle_id' => $cycleId,
                    'grade' => fake()->randomElement($grades)
                ]);
                $gradeCount++;
            }
        }

        echo "\n✅ Seeder completado:\n";
        echo "   - Facultades: " . count($faculties) . "\n";
        echo "   - Carreras: " . count($careers) . "\n";
        echo "   - Materias: " . count($materials) . "\n";
        echo "   - Ciclos: " . count($cycles) . "\n";
        echo "   - Estudiantes: " . count($students) . "\n";
        echo "   - Calificaciones: $gradeCount\n";
    }
}
