<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faculties extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'faculties';
    protected $fillable = ['name'];


    protected $keyType = 'string';
    public $incrementing = false;
}
