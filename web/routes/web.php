<?php

use App\Http\Controllers\IncidenciaWebController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/alertas');

Route::get('/alertas', [IncidenciaWebController::class, 'index'])->name('alertas.index');
Route::get('/alertas/crear', [IncidenciaWebController::class, 'create'])->name('alertas.create');
Route::post('/alertas', [IncidenciaWebController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('alertas.store');
