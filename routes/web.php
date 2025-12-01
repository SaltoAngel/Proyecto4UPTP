<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\loginController;
use App\Http\Controllers\Admin\adminController;

Route::get('/', function () {
    return view('welcome');
});

/*Rutas de Inicio de Sesión, mediante controladores*/
// Route::get('/login', [loginController::class, 'showLoginForm']) -> name('login');
// Route::post('/login', [loginController::class, 'login']);
// Route::post('/logout', [loginController::class, 'logout']) -> name('logout');

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
