<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\CursoController;

Route::apiResource('usuarios', UsuarioController::class);
Route::apiResource('cursos', CursoController::class);