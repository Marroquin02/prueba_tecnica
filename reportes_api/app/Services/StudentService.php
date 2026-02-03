<?php

namespace App\Services;

use App\Http\Resources\StudentResource;
use App\Models\Students;

class StudentService
{
    /**
     * Get minimal student data which pagination is required.
     *
     * @return array
     */
    public function getMinimalStudentData(
        int $perPage = 15,
        int $page = 1,
        ?string $searchTerm
    ) {
        $query = Students::with(['career.faculty', 'grades.material']);

        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('carnet', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('first_name', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('last_name', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('email', 'LIKE', "%{$searchTerm}%");
            });
        }

        $students = $query->paginate($perPage, ['*'], 'page', $page);

        return [
            'data' => StudentResource::collection($students),
            'meta' => [
                'current_page' => $students->currentPage(),
                'last_page' => $students->lastPage(),
                'per_page' => $students->perPage(),
                'total' => $students->total(),
                'from' => $students->firstItem(),
                'to' => $students->lastItem(),
            ],
        ];
    }
}
