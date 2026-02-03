<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
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

        foreach ($this->grades as $grade) {
            $uv = $grade->material->uv;
            $totalUV += $uv;
            $weightedSum += $grade->grade * $uv;
        }

        $cum = $totalUV > 0 ? round($weightedSum / $totalUV, 2) : 0;

        return [
            'carnet' => $this->carnet,
            'full_name' => $this->full_name,
            'career' => $this->career->name,
            'faculty' => $this->career->faculty->name,
            'subjects_count' => $this->grades->pluck('material_id')->unique()->count(),
            'cum' => $cum,
            'status' => $this->status,
        ];
    }
}
