<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\loginController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\dashboard\PersonasController;
use App\Http\Controllers\dashboard\ReportesController;
use App\Http\Controllers\dashboard\ReportesAdminController;
use App\Http\Controllers\dashboard\ProveedoresController;
use App\Http\Controllers\dashboard\SettingsController;
use App\Http\Controllers\dashboard\DashboardController;
use App\Http\Controllers\dashboard\RecepcionesController;
use App\Http\Controllers\dashboard\OrdenesCompraController;
use App\Http\Controllers\dashboard\RoleController;
use App\Http\Controllers\DictionaryController;

//rutas del home
use App\Http\Controllers\h_homeController;
use App\Http\Controllers\h_ServiciosController;
use App\Http\Controllers\serviciosController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\NosotrosController;
use App\Http\Controllers\ContactoController;

Route::get('/', [h_homeController::class, 'index'])->name('Homepage.index');
Route::get('/servicios', [serviciosController::class, 'index'])->name('servicios');
Route::get('/productos', [ProductosController::class, 'index'])->name('productos');
Route::get('/nosotros', [NosotrosController::class, 'index'])->name('nosotros');
Route::get('/contacto', [ContactoController::class, 'index'])->name('contacto');
Route::post('/contacto', [ContactoController::class, 'enviar'])->name('contacto.enviar');

Auth::routes();

// Dashboard principal (Material)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware([\App\Http\Middleware\Authenticate::class])
    ->name('dashboard');

    Route::get('/generate-dictionary', [DictionaryController::class, 'generateWord']);


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

    // RUTAS DE ROLES Y PERMISOS
    Route::get('/test-roles', function () {
        return view('dashboard.roles.index');
    });

    //Ruta para volver a habilitar el rol
    Route::post('roles/{id}/restore', [\App\Http\Controllers\Dashboard\RoleController::class, 'restore'])
        ->name('roles.restore');
    Route::resource('roles', \App\Http\Controllers\Dashboard\RoleController::class);
    Route::get('roles/{role}/assign-permissions', [\App\Http\Controllers\Dashboard\RoleController::class, 'assignPermissions'])
        ->name('roles.assign-permissions');
    Route::post('roles/{role}/update-permissions', [\App\Http\Controllers\Dashboard\RoleController::class, 'updatePermissions'])
        ->name('roles.update-permissions');

        Route::post('proveedores/buscar', [ProveedoresController::class, 'buscar'])->name('proveedores.buscar');
        Route::post('proveedores/{id}/restore', [ProveedoresController::class, 'restore'])->name('proveedores.restore');

        // Recepciones (stubs)
        Route::get('/recepciones', [RecepcionesController::class, 'index'])->name('recepciones.index');
        Route::get('/recepciones/create', [RecepcionesController::class, 'create'])->name('recepciones.create');

        // Órdenes de compra (stubs)
        Route::get('/ordenes-compra', [OrdenesCompraController::class, 'index'])->name('ordenes-compra.index');
        Route::get('/ordenes-compra/create', [OrdenesCompraController::class, 'create'])->name('ordenes-compra.create');

        // Reportes
        Route::get('/reportes/personas/{formato?}', [ReportesController::class, 'personas'])
            ->whereIn('formato', ['pdf', 'xlsx'])
            ->name('reportes.personas');
        Route::get('/reportes/generar/{template}/{formato?}', [ReportesController::class, 'generar'])
            ->whereIn('formato', ['pdf', 'xlsx'])
            ->name('reportes.generar');

        // Administración de Reportes
        Route::resource('reportes-admin', ReportesAdminController::class)->parameters([
            'reportes-admin' => 'reporte'
        ]);
        Route::post('reportes-admin/{reporte}/toggle', [ReportesAdminController::class, 'toggle'])
            ->name('reportes-admin.toggle');

        // Test reports
        Route::get('/test-reports', [ReportesController::class, 'test'])->name('test-reports');

        // Debug status
        Route::get('/debug-status', [DashboardController::class, 'debugStatus'])->name('debug-status');
        
        // Exchange rate API
        Route::get('/api/exchange-rate', [DashboardController::class, 'exchangeRate'])->name('api.exchange-rate');

        // Configuración de usuario
        Route::get('/configuracion', [SettingsController::class, 'index'])->name('configuracion');
        Route::post('/configuracion', [SettingsController::class, 'update'])->name('configuracion.update');
    });

Route::get('/test-jasper-command', function() {
    // Validate Java 8 first
    $javaPath = env('JAVA_PATH', 'C:\\Program Files\\Eclipse Adoptium\\jdk-8.0.402.6\\bin\\java.exe');
    if (!file_exists($javaPath)) {
        $javaPath = 'java';
    }
    
    $versionCmd = '"' . $javaPath . '" -version 2>&1';
    $versionOutput = [];
    exec($versionCmd, $versionOutput);
    
    $versionString = implode(' ', $versionOutput);
    $isJava8 = preg_match('/1\.8\.|openjdk version "8\.|java version "1\.8/', $versionString);
    
    if (!$isJava8) {
        return response()->json([
            'error' => 'JasperReports requiere Java 8. Versión actual no compatible.',
            'java_version' => $versionString,
            'java_path' => $javaPath
        ], 400);
    }
    
    $jasper = new PHPJasper\PHPJasper();
    
    try {
        // Test 1: Get version
        $version = $jasper->getVersion();
        
        // Test 2: Build a simple command
        $input = storage_path('app/reports/test.jrxml');
        
        // Create a simple test .jrxml if it doesn't exist
        if (!file_exists($input)) {
            $simpleXml = '<?xml version="1.0" encoding="UTF-8"?>
<jasperReport xmlns="http://jasperreports.sourceforge.net/jasperreports" 
              xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" 
              xsi:schemaLocation="http://jasperreports.sourceforge.net/jasperreports 
              http://jasperreports.sourceforge.net/xsd/jasperreport.xsd" 
              name="test" pageWidth="595" pageHeight="842">
    <title>
        <band height="79">
            <staticText>
                <reportElement x="20" y="20" width="200" height="30"/>
                <text><![CDATA[Test Report]]></text>
            </staticText>
            <textField>
                <reportElement x="20" y="50" width="200" height="20"/>
                <textFieldExpression><![CDATA[$P{TEST_PARAM}]]></textFieldExpression>
            </textField>
        </band>
    </title>
</jasperReport>';
            
            file_put_contents($input, $simpleXml);
        }
        
        $output = storage_path('app/reports/test_output');
        $options = [
            'format' => ['pdf'],
            'params' => ['TEST_PARAM' => 'Hello from Jasper!'],
        ];
        
        // Show the command that will be executed
        $command = $jasper->process($input, $output, $options)->output();
        
        return response()->json([
            'version' => $version,
            'command' => $command,
            'input_exists' => file_exists($input),
            'java_path' => $javaPath,
            'java_compatible' => true
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }

    
});

