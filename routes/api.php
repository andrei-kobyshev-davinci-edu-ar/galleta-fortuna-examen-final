<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FortunaController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Rutas públicas
Route::post('/register', [AuthController::class, 'registro']);
Route::post('/login', [AuthController::class, 'login']);

// Rutas protegidas
Route::middleware('auth.token')->group(function () {
    Route::get('/fortuna', [FortunaController::class, 'obtenerFortuna']);
    
    // Rutas de administrador
    Route::middleware('admin')->group(function () {
        Route::get('/admin/frases', [AdminController::class, 'listarFrases']);
        Route::post('/admin/frases', [AdminController::class, 'agregarFrase']);
        Route::delete('/admin/frases/{id}', [AdminController::class, 'eliminarFrase']);
    });
});