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
            'activo' => $request->boolean('solo_activos', false) ? 1 : null,
        ];

        try {
            $outputFile = $jasper->generarReporte('personas.jrxml', $formato, $params);
            
            $nombre = 'reporte_personas_' . date('Ymd_His') . '.' . $formato;
            $contentType = $formato === 'pdf'
                ? 'application/pdf'
                : 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';

            return response()->download($outputFile, $nombre, ['Content-Type' => $contentType]);
            
        } catch (\Exception $e) {
            Log::error('Error al generar reporte de personas', ['error' => $e->getMessage()]);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Genera un reporte genérico basado en el template.
     */
    public function generar(Request $request, $plantilla, $formato = 'pdf', JasperService $jasperService)
    {
        try {
            // Map format names if needed
            $formatMap = [
                'pdf' => 'pdf',
                'xlsx' => 'xlsx',
                'excel' => 'xlsx',
                'xls' => 'xls',
                'html' => 'html',
                'docx' => 'docx',
                'odt' => 'odt',
                'csv' => 'csv',
            ];
            
            $jasperFormat = $formatMap[$formato] ?? 'pdf';
            
            // Get parameters from request
            $params = $request->all();
            
            // Generate report
            $outputFile = $jasperService->generarReporte(
                $plantilla . '.jrxml', // Make sure to include .jrxml extension
                $jasperFormat,
                $params
            );
            
            // Return the file
            $filename = "reporte_{$plantilla}_" . date('Ymd_His') . ".{$jasperFormat}";
            
            return response()->download($outputFile, $filename)
                ->deleteFileAfterSend(true);
            
        } catch (\Exception $e) {
            Log::error('Error al generar reporte', [
                'template' => $plantilla,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error al generar el reporte: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Página de prueba para reportes.
     */
    public function test()
    {
        $templatesPath = base_path('app/Reports/templates');
        $templates = glob($templatesPath . '/*.jrxml');
        $templateNames = array_map(function($path) {
            return basename($path, '.jrxml');
        }, $templates);

        return view('test', compact('templateNames'));
    }
}
