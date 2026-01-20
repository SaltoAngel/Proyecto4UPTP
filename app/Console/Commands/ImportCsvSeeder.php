<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ImportCsvSeeder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'csv:import 
                            {file : Nombre del archivo CSV}
                            {--table= : Nombre de la tabla (opcional)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importa CSV y crea seeder para Laravel';

    /**
     * Execute the console command.
     */
public function handle()
{
    $csvFile = $this->argument('file');
    $tableName = $this->option('table') ?? pathinfo($csvFile, PATHINFO_FILENAME);
    
    // Ruta completa al CSV
    $csvPath = storage_path("app/csv/{$csvFile}");
    
    if (!File::exists($csvPath)) {
        $this->error("❌ Archivo no encontrado: {$csvPath}");
        $this->line("💡 Coloca el CSV en: storage/app/csv/");
        return 1;
    }
    
    $this->info("📖 Procesando: {$csvFile}");
    
    // Leer todo el contenido del CSV
    $csvContent = File::get($csvPath);
    
    // Convertir a UTF-8 si es necesario (Paradox suele usar ANSI/Latin1)
    $csvContent = mb_convert_encoding($csvContent, 'UTF-8', 'ISO-8859-1');
    
    // Separar líneas
    $lines = explode("\n", $csvContent);
    
    // Eliminar líneas vacías
    $lines = array_filter($lines, function($line) {
        return trim($line) !== '';
    });
    
    // Obtener encabezados (primera línea)
    $firstLine = array_shift($lines);
    
    // Parsear encabezados con punto y coma y eliminar comillas
    $headers = str_getcsv($firstLine, ';', '"');
    $headers = array_map('trim', $headers);
    $headers = array_map(function($header) {
        // Eliminar comillas y espacios
        return trim($header, '\'" ');
    }, $headers);
    
    $this->info("📊 Columnas encontradas: " . count($headers));
    $this->info("📋 Primera columna: " . $headers[0]);
    $this->info("📋 Última columna: " . end($headers));
    
    $rows = [];
    $contador = 0;
    $errores = 0;
    
    foreach ($lines as $line) {
        $contador++;
        
        // Parsear línea con punto y coma
        $data = str_getcsv($line, ';', '"');
        
        // Limpiar cada valor
        $data = array_map('trim', $data);
        $data = array_map(function($value) {
            return trim($value, '\'" ');
        }, $data);
        
        // Verificar que tenga el mismo número de columnas que los encabezados
        if (count($data) === count($headers)) {
            // Combinar con encabezados
            $row = array_combine($headers, $data);
            
            // Limpiar valores
            foreach ($row as $key => $value) {
                $row[$key] = $this->limpiarValor($value);
            }
            
            $rows[] = $row;
        } else {
            $errores++;
            $this->warn("⚠️  Fila {$contador}: Tiene " . count($data) . " columnas, esperaba " . count($headers));
            
            // Mostrar primeros 3 errores
            if ($errores <= 3) {
                $this->line("   Encabezados: " . implode(", ", $headers));
                $this->line("   Datos: " . implode(", ", $data));
            }
        }
        
        // Mostrar progreso cada 50 filas
        if ($contador % 50 === 0) {
            $this->line("   📋 Procesadas {$contador} filas...");
        }
    }
    
    if ($errores > 0) {
        $this->warn("⚠️  Total errores: {$errores} filas con número incorrecto de columnas");
    }
    
    if (empty($rows)) {
        $this->error("❌ No se pudieron procesar los datos. Revisa el formato del CSV.");
        return 1;
    }
    
    $this->info("✅ {$contador} filas procesadas, {$errores} errores, " . count($rows) . " registros válidos.");
    
    // Mostrar ejemplo del primer registro
    if (!empty($rows[0])) {
        $this->info("📝 Primer registro:");
        $firstRow = $rows[0];
        $keys = array_keys($firstRow);
        for ($i = 0; $i < min(5, count($keys)); $i++) {
            $key = $keys[$i];
            $this->line("   {$key}: {$firstRow[$key]}");
        }
    }
    
    // Crear seeder
    $this->crearSeeder($tableName, $rows);
    
    $this->newLine();
    $this->info("🎉 ¡Proceso completado!");
    $this->line("📁 Seeder creado en: database/seeders/" . ucfirst($tableName) . "TableSeeder.php");
    $this->line("📝 Para ejecutar: php artisan db:seed --class=" . ucfirst($tableName) . "TableSeeder");
    
    return 0;
}
    
private function limpiarValor($valor)
{
    $valor = trim($valor);
    
    if ($valor === '' || strtolower($valor) === 'null') {
        return null;
    }
    
    // Reemplazar comas decimales por puntos (formato europeo: 12,34 → 12.34)
    $valor = str_replace(',', '.', $valor);
    
    // Booleanos
    $lower = strtolower($valor);
    if (in_array($lower, ['true', 'false', '1', '0', 'si', 'no', 'yes', 'no', 'sí'])) {
        return filter_var($valor, FILTER_VALIDATE_BOOLEAN);
    }
    
    // Números
    if (is_numeric($valor)) {
        // Determinar si es entero o decimal
        $floatVal = (float) $valor;
        $intVal = (int) $floatVal;
        
        return ($floatVal == $intVal) ? $intVal : $floatVal;
    }
    
    // Fechas - varios formatos comunes
    $dateFormats = [
        'd/m/Y', 'd-m-Y', 'd.m.Y',  // DD/MM/YYYY
        'm/d/Y', 'm-d-Y', 'm.d.Y',  // MM/DD/YYYY
        'Y/m/d', 'Y-m-d', 'Y.m.d',  // YYYY/MM/DD
    ];
    
    foreach ($dateFormats as $format) {
        $date = \DateTime::createFromFormat($format, $valor);
        if ($date) {
            return $date->format('Y-m-d');
        }
    }
    
    // Si parece una fecha pero no coincide con formatos conocidos
    if (preg_match('/\d{1,2}[\/\-\.]\d{1,2}[\/\-\.]\d{2,4}/', $valor)) {
        $this->warn("⚠️  Formato de fecha no reconocido: {$valor}");
    }
    
    return $valor;
}
    
private function crearSeeder($tableName, $data)
{
    $seederName = ucfirst($tableName) . 'TableSeeder';
    $seederPath = database_path("seeders/{$seederName}.php");
    
    // Verificar y limpiar nombres de columna
    $firstRow = $data[0] ?? [];
    $cleanedColumns = [];
    
    foreach (array_keys($firstRow) as $column) {
        // Reemplazar espacios y caracteres especiales
        $cleaned = preg_replace('/[^a-zA-Z0-9_]/', '_', $column);
        $cleaned = preg_replace('/_+/', '_', $cleaned); // Múltiples _ a uno
        $cleaned = trim($cleaned, '_');
        $cleaned = strtolower($cleaned); // Opcional: todo minúsculas
        $cleanedColumns[$column] = $cleaned;
    }
    
    $cleanedColumnCount = count($cleanedColumns); // ← AÑADE ESTA LÍNEA
    
    $this->info("📝 Nombres de columnas limpiados:");
    foreach ($cleanedColumns as $original => $cleaned) {
        if ($original !== $cleaned) {
            $this->line("   {$original} → {$cleaned}");
        }
    }
    
    // Generar código para insertar
    $codigoInsert = "\$registros = [\n";
    
    foreach ($data as $index => $fila) {
        $codigoInsert .= "    [\n";
        
        foreach ($fila as $columnaOriginal => $valor) {
            $columna = $cleanedColumns[$columnaOriginal] ?? $columnaOriginal;
            
            // Formatear para PHP
            if ($valor === null) {
                $formateado = 'null';
            } elseif (is_bool($valor)) {
                $formateado = $valor ? 'true' : 'false';
            } elseif (is_string($valor)) {
                $formateado = "'" . addslashes($valor) . "'";
            } else {
                $formateado = $valor;
            }
            
            $codigoInsert .= "        '{$columna}' => {$formateado},\n";
        }
        
        $codigoInsert .= "    ],\n";
        
        // Mostrar progreso cada 20 registros
        if (($index + 1) % 20 === 0) {
            $this->line("   ✏️  Generando seeder: {$index}/" . count($data) . " registros...");
        }
    }
    
    $codigoInsert .= "];\n\n";
    $codigoInsert .= <<<'EOD'
        // Insertar en lotes de 50 (esta tabla tiene muchas columnas)
        foreach (array_chunk($registros, 50) as $lote) {
            DB::table('__TABLE__')->insert($lote);
        }
        
        $this->command->info('Seeder ejecutado: ' . count($registros) . ' registros insertados en __TABLE__');
EOD;
    
    $codigoInsert = str_replace('__TABLE__', $tableName, $codigoInsert);
    
    // Plantilla del seeder - CORREGIDA la interpolación de variables
    $contenidoSeeder = <<<PHP
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class {$seederName} extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // NOTA: Esta tabla tiene muchas columnas ({$cleanedColumnCount})
        // Considera crear primero la migración con las columnas necesarias
        
        // Opcional: Limpiar tabla primero (CUIDADO: borra datos existentes)
        // DB::table('{$tableName}')->truncate();
        
{$codigoInsert}
    }
}
PHP;
    
    // Guardar archivo
    File::put($seederPath, $contenidoSeeder);
    
    $this->info("💾 Seeder guardado: {$seederName}");
    $this->info("📊 Columnas: {$cleanedColumnCount}, Registros: " . count($data));
}
}