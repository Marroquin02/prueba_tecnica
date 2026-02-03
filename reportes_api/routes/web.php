<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentsController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/students/minimal', [StudentsController::class, 'minimal']);
Route::get('/students/{carnet}/report', [StudentsController::class, 'report']);