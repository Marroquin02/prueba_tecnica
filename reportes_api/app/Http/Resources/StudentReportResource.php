<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $totalUV = 0;
        $weightedSum = 0;

        $cycles = [];
        foreach ($this->grades as $grade) {
            $cycleId = $grade->cycle_id;
            $cycleName = $grade->cycle->name ?? 'Unknown';

            if (!isset($cycles[$cycleId])) {
                $cycles[$cycleId] = [
                    'id' => $cycleId,
                    'name' => $cycleName,
                    'subjects' => [],
                ];
            }

            $uv = $grade->material->uv;
            $totalUV += $uv;
            $weightedSum += $grade->grade * $uv;

            $cycles[$cycleId]['subjects'][] = [
                'id' => $grade->material->id,
                'name' => $grade->material->name,
                'uv' => $grade->material->uv,
                'grade' => $grade->grade,
                'passed' => $grade->grade >= 6,
            ];
        }

        $cum = $totalUV > 0 ? round($weightedSum / $totalUV, 2) : 0;

        $uniqueSubjects = $this->grades->pluck('material_id')->unique()->count();


        $passedSubjectsCount = $this->grades->where('grade', '>=', 6)->pluck('material_id')->unique()->count();


        $totalMaterials = $this->career->materials ?? 0;
        $progress = $totalMaterials > 0 ? round(($passedSubjectsCount / $totalMaterials) * 100, 2) : 0;


        usort($cycles, function ($a, $b) {
            return strcmp($b['name'], $a['name']);
        });

        return [
            'carnet' => $this->carnet,
            'full_name' => $this->full_name,
            'career' => $this->career->name,
            'faculty' => $this->career->faculty->name,
            'subjects_count' => $uniqueSubjects,
            'passed_subjects_count' => $passedSubjectsCount,
            'total_career_materials' => $totalMaterials,
            'cum' => $cum,
            'status' => $this->status,
            'progress' => $progress,
            'cycles' => array_values($cycles),
        ];
    }
}
