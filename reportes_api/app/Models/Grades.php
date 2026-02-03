<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grades extends Model
{
    use HasFactory, HasUuids;
    
    protected $table = 'grades';

    protected $fillable = ['material_id', 'student_id', 'cycle_id', 'grade'];

    protected $casts = [
        'grade' => 'float'
    ];


    protected $keyType = 'string';
    public $incrementing = false;

    public function material()
    {
        return $this->belongsTo(Materials::class, 'material_id');
    }

    public function student()
    {
        return $this->belongsTo(Students::class, 'student_id', 'carnet');
    }

    public function cycle()
    {
        return $this->belongsTo(Cycles::class, 'cycle_id');
    }
}
