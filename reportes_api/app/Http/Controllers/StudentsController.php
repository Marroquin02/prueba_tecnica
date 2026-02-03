<?php

namespace App\Http\Controllers;

use App\Services\StudentService;
use Illuminate\Http\Request;

class StudentsController extends Controller
{
    protected $studentService;

    public function __construct(StudentService $studentService)
    {
        $this->studentService = $studentService;
    }

    public function minimal(
        Request $request,
    ) {
        $students = $this->studentService->getMinimalStudentData(
            $request->input('perPage', 15),
            $request->input('page', 1),
            $request->input('searchTerm')
        );
        return response()->json($students);
    }
}
