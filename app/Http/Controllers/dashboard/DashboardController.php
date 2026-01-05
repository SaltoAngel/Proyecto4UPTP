<?php
/**
 * Controlador: DashboardController
 * Propósito: Renderizar el panel principal (Material Dashboard)
 * Datos que provee a la vista:
 *  - personasCount, proveedoresCount, bitacoraCount: métricas de conteo rápido
 *  - topClientes: listado demo (últimas personas) como placeholder
 * Notas:
 *  - La vista usa skeleton loading y gráficos (Chart.js) + mapa (chartjs-chart-geo)
 *  - Conectar topClientes a datos reales cuando se defina el criterio
 */

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Personas;
use App\Models\Proveedor;
use App\Models\Bitacora;

class DashboardController extends Controller
{
    public function index()
    {
        $personasCount = Personas::count();
        $proveedoresCount = Proveedor::count();
        $bitacoraCount = class_exists(Bitacora::class) ? Bitacora::count() : null;

        // Demo de Top 5 clientes: usando personas más recientes como placeholder
        $topClientes = Personas::orderByDesc('id')
            ->take(5)
            ->get()
            ->map(function ($p) {
                return [
                    'nombre' => $p->nombre_completo ?? '—',
                    'total' => 0,
                ];
            });

        return view('dashboard.dashboard', compact(
            'personasCount',
            'proveedoresCount',
            'bitacoraCount',
            'topClientes'
        ));
    }

    /**
     * Devuelve el estado de debug para Jasper y dependencias.
     */
    public function debugStatus()
    {
        $status = [];

        // Verificar PHP Jasper
        $status['php_jasper'] = class_exists('PHPJasper\PHPJasper') ? 'Cargado' : 'No encontrado';

        // Verificar JasperStarter (global o incluido en vendor)
        $jasperstarterStatus = 'No encontrado';

        // 1) Intento global (en PATH)
        @exec('jasperstarter --version 2>&1', $outJasperGlobal, $retJasperGlobal);
        if (isset($retJasperGlobal) && $retJasperGlobal === 0) {
            $jasperstarterStatus = 'Instalado (global)';
        } else {
            // 2) Buscar en vendor (recursivamente) por binario jasperstarter o jasperstarter.exe
            $vendorDir = base_path('vendor');
            $foundPath = null;
            if (is_dir($vendorDir)) {
            $it = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($vendorDir));
            foreach ($it as $file) {
                if (!$file->isFile()) {
                continue;
                }
                $fname = strtolower($file->getFilename());
                if ($fname === 'jasperstarter' || $fname === 'jasperstarter.exe') {
                $foundPath = $file->getPathname();
                break;
                }
            }
            }

            if ($foundPath) {
            $cmd = escapeshellarg($foundPath) . ' --version 2>&1';
            @exec($cmd, $outJasperVendor, $retJasperVendor);
            if (isset($retJasperVendor) && $retJasperVendor === 0) {
                $jasperstarterStatus = 'Instalado (vendor: ' . $foundPath . ')';
            } else {
                // si existe pero no ejecutable, reportar su presencia
                $jasperstarterStatus = 'Encontrado en vendor (' . $foundPath . '), no ejecutable';
            }
            }
        }

        $status['jasperstarter'] = $jasperstarterStatus;

        // Verificar Java
        $javaStatus = 'No encontrado';
        $javaVersion = null;
        @exec('java -version 2>&1', $javaOutput, $javaReturn);
        if (isset($javaReturn) && $javaReturn === 0 && !empty($javaOutput)) {
            $javaVersion = trim($javaOutput[0]);
            $javaStatus = 'Instalado: ' . $javaVersion;
        } else {
            // Intentar localizar binario (where en Windows, which en *nix)
            $finderCmd = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? 'where java 2>&1' : 'which java 2>&1';
            @exec($finderCmd, $finderOut, $finderRet);
            if (isset($finderRet) && $finderRet === 0 && !empty($finderOut)) {
            $javaStatus = 'Instalado (en ' . $finderOut[0] . ')';
            }
        }
        $status['java'] = $javaStatus;

        // Verificar archivos .jrxml
        $jrxmlPath = base_path('app/Reports/templates');
        $jrxmlFiles = glob($jrxmlPath . '/*.jrxml');
        $status['jrxml_files'] = count($jrxmlFiles) > 0 ? count($jrxmlFiles) . ' archivos encontrados' : 'No encontrados';

        // Verificar Java y Jasper JAR
        $javaPath = exec('where java 2>nul');
        $status['java_path'] = $javaPath ? 'Encontrado: ' . trim($javaPath) : 'No encontrado en PATH';
        
        $jasperJar = null;
        $vendorDir = base_path('vendor');
        if (is_dir($vendorDir)) {
            $it = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($vendorDir));
            foreach ($it as $file) {
                if ($file->isFile() && strtolower($file->getFilename()) === 'jasperstarter.jar') {
                    $jasperJar = $file->getPathname();
                    break;
                }
            }
        }
        $status['jasper_jar'] = $jasperJar ? 'Encontrado: ' . $jasperJar : 'No encontrado';

        // Verificar JDBC dir
        $jdbcDir = null;
        if (is_dir($vendorDir)) {
            $it = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($vendorDir));
            foreach ($it as $file) {
                if ($file->isFile() && preg_match('/(mysql|postgresql)-connector.*\.jar$/', strtolower($file->getFilename()))) {
                    $jdbcDir = dirname($file->getPathname());
                    break;
                }
            }
        }
        $status['jdbc_dir'] = $jdbcDir ? 'Encontrado: ' . $jdbcDir : 'No encontrado';

        // Últimos logs de error de Laravel
        $logFile = storage_path('logs/laravel.log');
        $status['recent_logs'] = [];
        if (file_exists($logFile)) {
            $lines = file($logFile);
            $recentLines = array_slice($lines, -10); // Últimas 10 líneas
            $status['recent_logs'] = array_map('trim', $recentLines);
        }

        return response()->json($status);
    }
}
