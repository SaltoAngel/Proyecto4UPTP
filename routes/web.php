<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\loginController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\dashboard\PersonasController;

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

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Dashboard simple
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

// Panel Administrativo
Route::middleware('auth')->group(function () {
    //Bitacora
    Route::get('/bitacora', [App\Http\Controllers\BitacoraController::class, 'index'])->name('bitacora.index');
    Route::get('/bitacora/{bitacora}', [App\Http\Controllers\BitacoraController::class, 'show'])->name('bitacora.show');
    //Personas
    Route::get('/personas', [App\Http\Controllers\dashboard\PersonasController::class, 'index'])->name('personas.index');
});

route::get('/logout', [loginController::class, 'logout'])->name('logout');