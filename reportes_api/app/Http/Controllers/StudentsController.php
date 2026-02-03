<?php

namespace App\Http\Controllers;

use App\Mail\SendStudentReport;
use App\Services\StudentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

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

    public function report($carnet)
    {
        $report = $this->studentService->generateStudentReport($carnet);
        return response()->json($report);
    }

    public function emailReport(Request $request, string $carnet)
    {
        // Validar la solicitud
        $validator = Validator::make($request->all(), [
            'pdf_base64' => 'required|string',
            'filename' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            // Obtener datos del estudiante
            $report = $this->studentService->generateStudentReport($carnet);
            $student = $report['student']->resolve($request);

            // Verificar que el estudiante tenga email
            if (empty($student['email'])) {
                return response()->json([
                    'message' => 'El estudiante no tiene un correo electrónico registrado'
                ], 400);
            }

            // Validar que el base64 sea válido
            $pdfBase64 = $request->input('pdf_base64');
            $decoded = base64_decode($pdfBase64, true);
            if ($decoded === false) {
                return response()->json([
                    'message' => 'El PDF en base64 no es válido'
                ], 400);
            }

            // Validar tamaño del PDF (máximo 10MB)
            $pdfSize = strlen($decoded);
            if ($pdfSize > 10 * 1024 * 1024) {
                return response()->json([
                    'message' => 'El PDF excede el tamaño máximo permitido (10MB)'
                ], 400);
            }

            // Sanitizar el nombre del archivo
            $filename = preg_replace('/[^a-zA-Z0-9._-]/', '_', $request->input('filename'));
            if (!str_ends_with($filename, '.pdf')) {
                $filename .= '.pdf';
            }

            // Enviar el correo
            Mail::to($student['email'])->send(new SendStudentReport(
                $student,
                $pdfBase64,
                $filename
            ));

            return response()->json([
                'message' => 'Correo enviado exitosamente',
                'email' => $student['email']
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Estudiante no encontrado'
            ], 404);
        } catch (\Exception $e) {
            // Log del error para debugging
            Log::error('Error al enviar correo: ' . $e->getMessage());

            return response()->json([
                'message' => 'Error al enviar el correo. Por favor, intente nuevamente.'
            ], 500);
        }
    }
}
