<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Students extends Model
{
    use HasFactory;

    protected $table = 'students';
    protected $primaryKey = 'carnet';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['carnet', 'email', 'first_name', 'last_name', 'career_id', 'ingress', 'status'];

    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn() => "{$this->first_name} {$this->last_name}"
        );
    }

    protected function cumulativeAverage(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->grades->isEmpty()) {
                    return 0;
                }

                $totalUV = 0;
                $weightedSum = 0;

                foreach ($this->grades as $grade) {
                    $uv = $grade->material->uv;
                    $totalUV += $uv;
                    $weightedSum += $grade->grade * $uv;
                }

                return $totalUV > 0 ? round($weightedSum / $totalUV, 2) : 0;
            }
        );
    }

    public function career()
    {
        return $this->belongsTo(Careers::class, 'career_id');
    }

    public function grades()
    {
        return $this->hasMany(Grades::class, 'student_id', 'carnet');
    }
}
