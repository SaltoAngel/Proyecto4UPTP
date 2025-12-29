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

        return view('material.dashboard', compact(
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

        // Verificar JasperStarter
        $jasperStarterCheck = @exec('jasperstarter --version 2>&1', $output, $return);
        $status['jasperstarter'] = $return === 0 ? 'Instalado' : 'No encontrado';

        // Verificar Java
        $javaCheck = @exec('java -version 2>&1', $output, $return);
        $status['java'] = $return === 0 ? 'Instalado' : 'No encontrado';

        // Verificar archivos .jrxml
        $jrxmlPath = base_path('app/Reports/templates');
        $jrxmlFiles = glob($jrxmlPath . '/*.jrxml');
        $status['jrxml_files'] = count($jrxmlFiles) > 0 ? count($jrxmlFiles) . ' archivos encontrados' : 'No encontrados';

        return response()->json($status);
    }
}
