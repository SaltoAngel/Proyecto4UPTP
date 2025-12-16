<?php
namespace App\Services;

use PHPJasper\PHPJasper;

class JasperService
{
    protected $jasper;
    protected $tempPath;

    public function __construct()
    {
        $this->jasper = new PHPJasper();
        $this->tempPath = storage_path('app/reports/temp');
        
        if (!file_exists($this->tempPath)) {
            mkdir($this->tempPath, 0777, true);
        }
    }

    /**
     * Genera un reporte a partir de un archivo .jasper (ya compilado) o .jrxml.
     * @param string $reporte Nombre del archivo (con o sin extensión).
     * @param string $formato pdf|xlsx
     * @param array $parametros Parámetros para el reporte
     * @param array|null $conexion Configuración DB opcional
     */
    public function generarReporte($reporte, $formato = 'pdf', $parametros = [], $conexion = null)
    {
        try {
            $reporte = $this->normalizarNombre($reporte);
            $input = base_path("app/Reports/templates/{$reporte}");
            $output = $this->tempPath . '/' . pathinfo($reporte, PATHINFO_FILENAME);
            
            $options = [
                'format' => [$formato],
                'params' => $parametros,
                'db_connection' => $conexion ?? $this->getDatabaseConfig(),
                'locale' => 'es'
            ];
            
            // Generar reporte
            $this->jasper->process(
                $input,
                $output,
                $options
            )->execute();
            
            $archivoGenerado = $output . '.' . $formato;
            
            if (!file_exists($archivoGenerado)) {
                throw new \Exception("No se pudo generar el reporte");
            }
            
            return [
                'success' => true,
                'path' => $archivoGenerado,
                'filename' => basename($archivoGenerado),
                'size' => filesize($archivoGenerado)
            ];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    private function getDatabaseConfig()
    {
        return [
            'driver' => 'mysql',
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'host' => env('DB_HOST'),
            'database' => env('DB_DATABASE'),
            'port' => env('DB_PORT', '3306')
        ];
    }

    /**
     * Compilar JRXML a JASPER
     */
    public function compilarReporte($jrxmlFile)
    {
        $input = base_path("app/Reports/templates/{$jrxmlFile}");
        $output = base_path("app/Reports/compiled/");
        
        if (!file_exists($output)) {
            mkdir($output, 0777, true);
        }
        
        return $this->jasper->compile($input, $output)->execute();
    }

    /**
     * Asegura extensión .jasper si no se proporcionó.
     */
    private function normalizarNombre(string $nombre): string
    {
        if (!str_ends_with(strtolower($nombre), '.jasper') && !str_ends_with(strtolower($nombre), '.jrxml')) {
            return $nombre . '.jasper';
        }
        return $nombre;
    }
}