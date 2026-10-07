<?php

use App\Http\Controllers\Api\CategoriaController;
use App\Http\Controllers\Api\IncidenciaController;
use Illuminate\Support\Facades\Route;

Route::get('/categorias', [CategoriaController::class, 'index']);
Route::apiResource('incidencias', IncidenciaController::class)->only(['index', 'store', 'show']);
