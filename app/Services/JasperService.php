<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class JasperService
{
    private function isJava8Compatible($javaPath)
    {
        $versionCmd = '"' . $javaPath . '" -version 2>&1';
        $versionOutput = [];
        exec($versionCmd, $versionOutput);
        
        $versionString = implode(' ', $versionOutput);
        
        // Check if it's Java 8 (1.8.x or openjdk version "8.x")
        return preg_match('/1\.8\.|openjdk version "8\.|java version "1\.8/', $versionString);
    }
    
    private function getJavaPath()
    {
        // Priority: env variable, then Java 8, then system java
        $javaPath = env('JAVA_PATH');
        
        Log::info('Java path from env', ['JAVA_PATH' => $javaPath]);
        
        if ($javaPath && file_exists($javaPath)) {
            Log::info('Using Java from env', ['path' => $javaPath]);
            return $javaPath;
        }
        
        // Default to Java 8
        $java8Path = 'C:\\Program Files\\Eclipse Adoptium\\jdk-8.0.402.6\\bin\\java.exe';
        if (file_exists($java8Path)) {
            Log::info('Using default Java 8', ['path' => $java8Path]);
            return $java8Path;
        }
        
        // Fallback to system java
        Log::info('Using system java');
        return 'java';
    }
    
    public function generarReporte($template, $formato = 'pdf', $params = [])
    {
        try {
            // Validate Java version FIRST, before any execution
            $javaPath = $this->getJavaPath();
            
            if (!$this->isJava8Compatible($javaPath)) {
                throw new \Exception('JasperReports requiere Java 8. Versión actual no compatible. Instale Java 8 o configure JAVA_PATH en .env apuntando a Java 8.');
            }
            
            $input = app_path("Reports/templates/{$template}");
            $output = storage_path("app/reports/temp/" . pathinfo($template, PATHINFO_FILENAME));
            
            Log::info('Jasper: Iniciando generación', [
                'input' => $input,
                'output' => $output,
                'formato' => $formato,
                'java_path' => $javaPath
            ]);
            
            $dbConfig = [
                'username' => env('DB_USERNAME', 'postgres'),
                'password' => env('DB_PASSWORD', '1234'),
                'host' => env('DB_HOST', '127.0.0.1'),
                'database' => env('DB_DATABASE', 'ProyectoUPTP4'),
                'port' => env('DB_PORT', '5432'),
            ];
            
            $outputDir = dirname($output);
            if (!is_dir($outputDir)) {
                mkdir($outputDir, 0777, true);
            }
            
            Log::info('Using compatible Java', ['path' => $javaPath]);
            
            $jasperJar = base_path('vendor/geekcom/phpjasper/bin/jasperstarter/lib/jasperstarter.jar');
            $jdbcDir = base_path('vendor/geekcom/phpjasper/bin/jasperstarter/jdbc');
            
            if (!file_exists($jasperJar)) {
                throw new \Exception("JasperStarter JAR not found: {$jasperJar}");
            }
            
            // Build classpath with proper escaping
            $classpath = '"' . $jasperJar . ';' . $jdbcDir . '\\*"';
            
            $cmd = '"' . $javaPath . '" -cp ' . $classpath . ' de.cenote.jasperstarter.App';
            $cmd .= ' --locale es_ES';
            $cmd .= ' process "' . $input . '"';
            $cmd .= ' -o "' . $output . '"';
            $cmd .= ' -f ' . $formato;
            $cmd .= ' -t postgres';
            $cmd .= ' -u "' . $dbConfig['username'] . '"';
            $cmd .= ' -p "' . $dbConfig['password'] . '"';
            $cmd .= ' -H "' . $dbConfig['host'] . '"';
            $cmd .= ' -n "' . $dbConfig['database'] . '"';
            $cmd .= ' --db-port ' . $dbConfig['port'];
            
            if (!empty($params)) {
                foreach ($params as $key => $value) {
                    if ($value !== null) {
                        $cmd .= ' -P "' . $key . '=' . $value . '"';
                    }
                }
            }
            
            $cmd .= ' 2>&1';
            
            Log::info('Jasper: Command', ['cmd' => $cmd]);
            
            $outputCmd = [];
            $returnVar = 0;
            exec($cmd, $outputCmd, $returnVar);
            
            Log::info('Jasper: Exec result', ['output' => $outputCmd, 'return' => $returnVar]);
            
            if ($returnVar !== 0) {
                throw new \Exception("Error ejecutando JasperStarter: " . implode("\n", $outputCmd));
            }
            
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
}