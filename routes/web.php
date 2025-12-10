<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\loginController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
// Autenticación
// ===== AUTENTICACIÓN DE DOBLE PASO DESHABILITADA =====
// use App\Http\Controllers\Auth\{
//     TwoFactorController,
//     LoginController
// };
// ===== FIN AUTENTICACIÓN DE DOBLE PASO =====


Route::get('/', function () {
    return Auth::check() ? redirect('/dashboard') : view('welcome');
});

/*Rutas por rol */
/*ADMINISTRADOR */
Route::middleware(['auth', 'roles:ADMINISTRADOR'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Usuario\adminController::class, 'dashboard']);
    // Mas rutas
});

/*NUTRICIONISTA */
Route::middleware(['auth', 'roles:NUTRICIONISTA'])->prefix('nutricionista')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Usuario\nutricionistaController::class, 'dashboard'])->name('nutricionista.dashboard');
    // Mas rutas
});

/*SUPERVISOR */
Route::middleware(['auth', 'roles:SUPERVISOR'])->prefix('supervisor')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Usuario\supervisorController::class, 'dashboard'])->name('supervisor.dashboard');
    // Mas rutas
});

/*COORDINADOR */
Route::middleware(['auth', 'roles:COORDINADOR'])->prefix('coordinador')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Usuario\coordinadorController::class, 'dashboard'])->name('coordinador.dashboard');
    // Mas rutas
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Dashboard simple
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

// Bitácora
Route::middleware('auth')->group(function () {
    Route::get('/bitacora', [App\Http\Controllers\BitacoraController::class, 'index'])->name('bitacora.index');
    Route::get('/bitacora/{bitacora}', [App\Http\Controllers\BitacoraController::class, 'show'])->name('bitacora.show');
});
