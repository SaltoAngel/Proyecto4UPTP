<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\loginController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\dashboard\PersonasController;
use App\Http\Controllers\dashboard\ReportesController;
use App\Http\Controllers\dashboard\ProveedoresController;
use App\Http\Controllers\dashboard\SettingsController;
use App\Http\Controllers\dashboard\DashboardController;

Route::get('/', function () {
    return Auth::check() ? redirect('/dashboard') : view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Dashboard principal (Material)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware([\App\Http\Middleware\Authenticate::class])
    ->name('dashboard');



// GeoJSON público para el mapa de Venezuela
Route::get('/geo/ve.json', function () {
    $path = resource_path('js/ve.json');
    abort_unless(file_exists($path), 404);
    return response()->file($path, [
        'Content-Type' => 'application/json',
        'Cache-Control' => 'public, max-age=604800', // 7 días
    ]);
})->name('geo.ve');

// Panel Administrativo
Route::middleware([\App\Http\Middleware\Authenticate::class])
    ->prefix('dashboard')
    ->name('dashboard.')
    ->group(function () {
        // Bitácora
        Route::get('/bitacora', [App\Http\Controllers\BitacoraController::class, 'index'])->name('bitacora.index');
        Route::get('/bitacora/{bitacora}', [App\Http\Controllers\BitacoraController::class, 'show'])->name('bitacora.show');

        // Personas
        Route::resource('personas', PersonasController::class);
        Route::post('personas/buscar', [PersonasController::class, 'buscar'])->name('personas.buscar');
        Route::post('personas/{id}/restore', [PersonasController::class, 'restore'])->name('personas.restore');

        // Proveedores
        Route::resource('proveedores', ProveedoresController::class)->parameters([
            'proveedores' => 'proveedor'
        ]);
        Route::post('proveedores/buscar', [ProveedoresController::class, 'buscar'])->name('proveedores.buscar');
        Route::post('proveedores/{id}/restore', [ProveedoresController::class, 'restore'])->name('proveedores.restore');

        // Reportes
        Route::get('/reportes/personas/{formato?}', [ReportesController::class, 'personas'])
            ->whereIn('formato', ['pdf', 'xlsx'])
            ->name('reportes.personas');

        // Test reports
        Route::get('/test-reports', [ReportesController::class, 'test'])->name('test-reports');

        // Debug status
        Route::get('/debug-status', [DashboardController::class, 'debugStatus'])->name('debug-status');

        // Configuración de usuario
        Route::get('/configuracion', [SettingsController::class, 'index'])->name('configuracion');
        Route::post('/configuracion', [SettingsController::class, 'update'])->name('configuracion.update');
    });



