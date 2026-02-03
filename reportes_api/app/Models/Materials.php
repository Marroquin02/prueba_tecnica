<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materials extends Model
{
    use HasFactory, HasUuids;
    
    protected $table = 'materials';

    protected $fillable = ['name', 'uv'];

    protected $casts = [
        'uv' => 'integer'
    ];


    protected $keyType = 'string';
    public $incrementing = false;

    public function grades()
    {
        return $this->hasMany(Grades::class);
    }
}
