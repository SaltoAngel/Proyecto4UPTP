<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Services\JasperService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ReportesController extends Controller
{
    /**
     * Genera reporte de personas en PDF o Excel usando un .jasper existente.
     */
    public function personas(Request $request, string $formato = 'pdf', JasperService $jasper)
    {
        $formato = in_array($formato, ['pdf', 'xlsx']) ? $formato : 'pdf';

        $params = [
            // Ejemplo de filtro opcional: estado activo
            'activo' => $request->boolean('solo_activos', false) ? 1 : null,
        ];

        $resultado = $jasper->generarReporte('personas', $formato, $params);

        if (!$resultado['success']) {
            Log::error('Error al generar reporte de personas', ['error' => $resultado['error'] ?? 'desconocido']);
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $resultado['error'] ?? 'No se pudo generar el reporte'], 500);
            }
            return back()->with('error', $resultado['error'] ?? 'No se pudo generar el reporte');
        }

        $path = $resultado['path'];
        $nombre = $resultado['filename'] ?? ('reporte_personas.' . $formato);
        $contentType = $formato === 'pdf'
            ? 'application/pdf'
            : 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';

        return response()->download($path, $nombre, ['Content-Type' => $contentType]);
    }
}
