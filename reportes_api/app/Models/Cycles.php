<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cycles extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'cycles';

    protected $fillable = ['name'];


    protected $keyType = 'string';
    public $incrementing = false;
}
