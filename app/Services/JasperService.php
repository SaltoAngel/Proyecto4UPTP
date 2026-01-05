<?php

namespace App\Services;

use PHPJasper\PHPJasper;
use Illuminate\Support\Facades\Log;

class JasperService
{
    private $jasper;
    
    public function __construct()
    {
        $this->jasper = new PHPJasper();
    }

    public function generarReporte($template, $formato = 'pdf', $params = [])
    {
        try {
            // Paths - fix the slashes
            $input = str_replace('/', DIRECTORY_SEPARATOR, app_path("Reports/templates/{$template}"));
            $output = str_replace('/', DIRECTORY_SEPARATOR, storage_path("app/reports/temp/" . pathinfo($template, PATHINFO_FILENAME)));
            
            Log::info('Jasper: Iniciando generación', [
                'input' => $input,
                'output' => $output,
                'formato' => $formato
            ]);
            
            // Correct database configuration
            $dbConfig = [
                'driver' => 'postgres',
                'username' => env('DB_USERNAME', 'postgres'),
                'password' => env('DB_PASSWORD', '1234'),
                'host' => env('DB_HOST', '127.0.0.1'),
                'database' => env('DB_DATABASE', 'ProyectoUPTP4'),
                'port' => env('DB_PORT', '5432'),
                'jdbc_driver' => 'org.postgresql.Driver',
            ];
            
            // Correct options format
            $options = [
                'format' => [$formato],
                'params' => $params,
                'db_connection' => $dbConfig,
                'locale' => 'es',
                'jdbc_dir' => base_path('vendor/geekcom/phpjasper/bin/jasperstarter/jdbc'),
            ];
            
            Log::info('Jasper: Opciones', $options);
            
            // Make sure output directory exists
            $outputDir = dirname($output);
            if (!is_dir($outputDir)) {
                mkdir($outputDir, 0777, true);
            }
            
            // Try direct command approach with explicit classpath
            $javaPath = 'java';
            $jasperJar = str_replace('/', DIRECTORY_SEPARATOR, base_path('vendor/geekcom/phpjasper/bin/jasperstarter/lib/jasperstarter.jar'));
            $jdbcDir = str_replace('/', DIRECTORY_SEPARATOR, base_path('vendor/geekcom/phpjasper/bin/jasperstarter/jdbc'));
            
            $classpath = escapeshellarg($jasperJar) . ';' . escapeshellarg($jdbcDir) . DIRECTORY_SEPARATOR . '*';
            
            $cmd = escapeshellarg($javaPath) . ' -cp ' . $classpath . ' de.cenote.jasperstarter.App';
            
            $cmd = escapeshellarg($javaPath) . ' -cp ' . $classpath . ' de.cenote.jasperstarter.App';
            $cmd .= ' --locale es_ES';
            $cmd .= ' process ' . escapeshellarg($input);
            $cmd .= ' -o ' . escapeshellarg($output);
            $cmd .= ' -f ' . $formato;
            $cmd .= ' -t postgres';
            $cmd .= ' -u ' . escapeshellarg($dbConfig['username']);
            $cmd .= ' -p ' . escapeshellarg($dbConfig['password']);
            $cmd .= ' -H ' . escapeshellarg($dbConfig['host']);
            $cmd .= ' -n ' . escapeshellarg($dbConfig['database']);
            $cmd .= ' --db-port ' . $dbConfig['port'];
            
            if (!empty($parametros)) {
                foreach ($parametros as $key => $value) {
                    $cmd .= ' -P ' . escapeshellarg($key) . '=' . escapeshellarg($value);
                }
            }
            
            $cmd .= ' 2>&1';
            
            Log::info('Jasper: Direct command', ['cmd' => $cmd]);
            
            $outputCmd = [];
            $returnVar = 0;
            exec($cmd, $outputCmd, $returnVar);
            
            Log::info('Jasper: Direct exec result', ['output' => $outputCmd, 'return' => $returnVar]);
            
            if ($returnVar !== 0) {
                throw new \Exception("Error ejecutando JasperStarter: " . implode("\n", $outputCmd));
            }
            
            // Skip the PHPJasper library call since we used direct command
            // $this->jasper->process($input, $output, $options)->execute();
            
            // Get the actual output file
            $outputFile = $output . '.' . $formato;
            
            if (!file_exists($outputFile)) {
                throw new \Exception("No se generó el archivo de salida: {$outputFile}");
            }
            
            return $outputFile;
            
        } catch (\Exception $e) {
            Log::error('Jasper: Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
    
    /**
     * Test JasperStarter installation
     */
    public function testJasper()
    {
        try {
            // Test basic command
            $version = $this->jasper->getVersion();
            
            // Test a simple process without database
            $input = storage_path('app/reports/test.jrxml');
            $output = storage_path('app/reports/test_output');
            
            if (file_exists($input)) {
                $options = [
                    'format' => ['pdf'],
                    'params' => ['TEST' => 'Hello World'],
                    // No database connection for test
                ];
                
                $this->jasper->process($input, $output, $options)->execute();
            }
            
            return [
                'success' => true,
                'version' => $version,
                'java' => exec('java -version 2>&1'),
            ];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'command' => $this->jasper->getCommand() ?? 'No command generated'
            ];
        }
    }
    
    /**
     * Alternative method with explicit command building
     */
    public function generarReporteSimple($template, $formato = 'pdf')
    {
        $input = app_path("Reports/templates/{$template}");
        $output = storage_path("app/reports/" . pathinfo($template, PATHINFO_FILENAME));
        
        // Simple options without database
        $options = [
            'format' => [$formato],
            'params' => [
                'TITULO' => 'Reporte de Prueba',
                'FECHA' => date('d/m/Y'),
            ],
        ];
        
        return $this->jasper->process($input, $output, $options)->execute();
    }
    
    private function getDatabaseConfig()
    {
        $connection = env('DB_CONNECTION', 'mysql');
        $host = env('DB_HOST', '127.0.0.1');
        $port = env('DB_PORT');
        $database = env('DB_DATABASE');
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');

        switch ($connection) {
            case 'pgsql':
                $driver = 'org.postgresql.Driver';
                $defaultPort = '5432';
                $jdbcUrl = "jdbc:postgresql://{$host}:{$port}/{$database}";
                break;
            case 'mysql':
            default:
                $driver = 'com.mysql.cj.jdbc.Driver';
                $defaultPort = '3306';
                $jdbcUrl = "jdbc:mysql://{$host}:{$port}/{$database}?useSSL=false&serverTimezone=UTC";
                break;
        }

        return [
            'driver' => $driver,
            'username' => $username,
            'password' => $password,
            'host' => $host,
            'database' => $database,
            'port' => $port ?: $defaultPort,
            'jdbc_url' => $jdbcUrl
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
    
    private function findJava()
    {
        // Buscar java en PATH
        $output = [];
        exec('where java 2>nul', $output, $return);
        if ($return === 0 && !empty($output)) {
            return trim($output[0]);
        }
        
        // Buscar en vendor o common paths
        $commonPaths = [
            'C:\Program Files\Java\jdk*\bin\java.exe',
            'C:\Program Files\Eclipse Adoptium\jdk*\bin\java.exe',
            '/usr/bin/java',
            '/usr/local/bin/java'
        ];
        
        foreach ($commonPaths as $pattern) {
            $matches = glob($pattern);
            if (!empty($matches)) {
                return $matches[0];
            }
        }
        
        return null;
    }
    
    private function findJasperJar()
    {
        $vendorDir = base_path('vendor');
        $it = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($vendorDir));
        foreach ($it as $file) {
            if ($file->isFile() && strtolower($file->getFilename()) === 'jasperstarter.jar') {
                return $file->getPathname();
            }
        }
        return null;
    }
    
    private function findJdbcDir()
    {
        $vendorDir = base_path('vendor');
        $it = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($vendorDir));
        foreach ($it as $file) {
            if ($file->isFile() && preg_match('/(mysql|postgresql)-connector.*\.jar$/', strtolower($file->getFilename()))) {
                return dirname($file->getPathname());
            }
        }
        return null;
    }
    
    private function getDbType($driver)
    {
        switch ($driver) {
            case 'org.postgresql.Driver':
                return 'postgres';
            case 'com.mysql.cj.jdbc.Driver':
            default:
                return 'mysql';
        }
    }
    
    private function findGeneratedFile($outputBase, $formato)
    {
        $formatoExt = strtolower($formato);
        $candidatos = [
            $outputBase . '.' . $formatoExt,
            $outputBase . '.' . strtoupper($formatoExt),
            $outputBase
        ];
        
        foreach ($candidatos as $candidato) {
            if (file_exists($candidato)) {
                return $candidato;
            }
        }
        
        // Buscar cualquier archivo que comience con el nombre base
        $patron = $outputBase . '.*';
        $archivos = glob($patron);
        return !empty($archivos) ? $archivos[0] : null;
    }
}