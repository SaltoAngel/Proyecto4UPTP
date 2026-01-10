<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\h-ServiciosController;
use App\Http\Controllers\serviciosController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\NosotrosController;
use App\Http\Controllers\ContactoController;
use App\Http\Controllers\Auth\LoginController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/servicios', [serviciosController::class, 'index'])->name('servicios');
Route::get('/productos', [ProductosController::class, 'index'])->name('productos');
Route::get('/nosotros', [NosotrosController::class, 'index'])->name('nosotros');
Route::get('/contacto', [ContactoController::class, 'index'])->name('contacto');
Route::post('/contacto', [ContactoController::class, 'enviar'])->name('contacto.enviar');

// Rutas de autenticación
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


// Ruta para servicios
Route::get('/servicios', [serviciosController::class, 'index'])->name('servicios');

// Ruta para productos
Route::get('/productos', [ProductosController::class, 'index'])->name('productos');

// Ruta para nosotros
Route::get('/nosotros', [NosotrosController::class, 'index'])->name('nosotros');

// Ruta para contacto
Route::get('/contacto', [ContactoController::class, 'index'])->name('contacto');
Route::post('/contacto/enviar', [ContactoController::class, 'enviar'])->name('contacto.enviar');