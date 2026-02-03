<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Careers extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'careers';

    protected $fillable = ['name', 'materials', 'faculty_id'];

    protected $keyType = 'string';
    public $incrementing = false;

    public function faculty()
    {
        return $this->belongsTo(Faculties::class, 'faculty_id');
    }
}
