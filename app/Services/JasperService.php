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
        $this->tempPath = storage_path('app/temp');
        
        if (!file_exists($this->tempPath)) {
            mkdir($this->tempPath, 0777, true);
        }
    }

    public function generarReporte($jrxml, $formato = 'pdf', $parametros = [], $conexion = null)
    {
        try {
            $input = base_path("app/Reports/templates/{$jrxml}");
            $output = $this->tempPath . '/' . pathinfo($jrxml, PATHINFO_FILENAME);
            
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
}