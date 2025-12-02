<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\loginController;
use App\Http\Controllers\Admin\adminController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

/*Rutas por rol */
/*ADMINISTRADOR */
Route::middleware(['auth', 'roles:ADMINISTRADOR'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\adminController::class, 'dashboard']);
    // Mas rutas
});

/*NUTRICIONISTA */
Route::middleware(['auth', 'roles:NUTRICIONISTA'])->prefix('nutricionista')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Nutricionista\nutricionistaController::class, 'dashboard'])->name('nutricionista.dashboard');
    // Mas rutas
});

/*SUPERVISOR */
Route::middleware(['auth', 'roles:SUPERVISOR'])->prefix('supervisor')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Supervisor\supervisorController::class, 'dashboard'])->name('supervisor.dashboard');
    // Mas rutas
});

/*COORDINADOR */
Route::middleware(['auth', 'roles:COORDINADOR'])->prefix('coordinador')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Coordinador\coordinadorController::class, 'dashboard'])->name('coordinador.dashboard');
    // Mas rutas
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
