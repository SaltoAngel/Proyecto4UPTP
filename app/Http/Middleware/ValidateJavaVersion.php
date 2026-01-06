<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ValidateJavaVersion
{
    public function handle(Request $request, Closure $next)
    {
        if (!$this->isJava8Compatible()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'JasperReports requiere Java 8. Versión actual no compatible.'
                ], 400);
            }
            
            return back()->with('error', 'JasperReports requiere Java 8. Por favor configure JAVA_PATH en .env apuntando a Java 8.');
        }

        return $next($request);
    }

    private function isJava8Compatible()
    {
        $javaPath = $this->getJavaPath();
        $versionCmd = '"' . $javaPath . '" -version 2>&1';
        $versionOutput = [];
        exec($versionCmd, $versionOutput);
        
        $versionString = implode(' ', $versionOutput);
        
        return preg_match('/1\.8\.|openjdk version "8\.|java version "1\.8/', $versionString);
    }
    
    private function getJavaPath()
    {
        $javaPath = env('JAVA_PATH');
        
        if ($javaPath && file_exists($javaPath)) {
            return $javaPath;
        }
        
        $java8Path = 'C:\\Program Files\\Eclipse Adoptium\\jdk-8.0.402.6\\bin\\java.exe';
        if (file_exists($java8Path)) {
            return $java8Path;
        }
        
        return 'java';
    }
}