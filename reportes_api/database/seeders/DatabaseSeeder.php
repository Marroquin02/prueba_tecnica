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

        echo "Creando facultades...\n";
        $faculties = [
            Faculties::create(['name' => 'Ingenierías y Arquitectura']),
            Faculties::create(['name' => 'Ciencias Economicas y Empresariales']),
            Faculties::create(['name' => 'Ciencias Sociales y Humanidades'])
        ];


        echo "Creando carreras...\n";
        $careers = [];
        $careersByFaculty = [
            $faculties[0]->id => [
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
            $faculties[1]->id => [
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
            $faculties[2]->id => [
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


        echo "Creando materias...\n";
        $materials = Materials::factory()->count(40)->create();



        echo "Creando ciclos académicos...\n";
        $cycles = [];
        foreach ([2021, 2022, 2023, 2024, 2025] as $year) {
            foreach (['I', 'II', 'III'] as $cycle) {
                $cycles[] = Cycles::create(['name' => "Ciclo {$cycle}-{$year}"]);
            }
        }


        echo "Creando estudiantes...\n";
        $students = [];
        $usedCarnets = [];

        foreach (range(1, 100) as $i) {
            do {
                $year = fake()->numberBetween(21, 26);
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

        echo "Creando calificaciones...\n";
        $gradeCount = 0;
        $grades = [5.0, 5.5, 6.0, 6.5, 7.0, 7.5, 8.0, 8.5, 9.0, 9.5, 10.0];
        $currentYear = 2026;

        foreach ($students as $student) {
            $yearsSinceIngress = $currentYear - $student->ingress;
            $maxGradesByYear = 0;

            for ($y = 0; $y < $yearsSinceIngress; $y++) {
                $maxGradesByYear += 9;
            }

            $careerMaterials = $student->career->materials;
            $minGrades = (int) ceil($careerMaterials / 2);

            $maxGrades = min($maxGradesByYear, $careerMaterials);

            $minGrades = min($minGrades, $maxGrades);

            $numGrades = fake()->numberBetween($minGrades, $maxGrades);


            $passedMaterials = [];

            $failedMaterials = [];

            for ($i = 0; $i < $numGrades; $i++) {

                $availableMaterials = $materials->reject(function ($material) use ($passedMaterials) {
                    return in_array($material->id, $passedMaterials);
                });

                if ($availableMaterials->isEmpty()) {
                    break;
                }

                $material = $availableMaterials->random();
                $materialId = $material->id;
                $cycleId = fake()->randomElement($cycles)->id;
                $grade = fake()->randomElement($grades);

                Grades::create([
                    'material_id' => $materialId,
                    'student_id' => $student->carnet,
                    'cycle_id' => $cycleId,
                    'grade' => $grade
                ]);


                if ($grade >= 6) {
                    $passedMaterials[] = $materialId;
                } else {

                    $failedMaterials[] = $materialId;
                }

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
