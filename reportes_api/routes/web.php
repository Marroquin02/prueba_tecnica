<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::post('/login', [LoginController::class, 'login'])->middleware('web');
Route::post('/logout', [LoginController::class, 'logout'])->middleware('web');