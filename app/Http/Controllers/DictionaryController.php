<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Style\Table as TableStyle;
use PhpOffice\PhpWord\Style\Cell as CellStyle;
use PhpOffice\PhpWord\Style\Paragraph;

class DictionaryController extends Controller
{
    public function generateWord()
    {
        // Obtener datos de la base de datos
        $tables = DB::select("
            SELECT 
                t.table_name,
                c.column_name,
                c.data_type,
                c.character_maximum_length,
                c.numeric_precision,
                c.numeric_scale,
                c.is_nullable,
                c.column_default,
                CASE 
                    WHEN EXISTS (
                        SELECT 1 FROM information_schema.key_column_usage k 
                        WHERE k.table_name = t.table_name 
                        AND k.column_name = c.column_name
                        AND EXISTS (
                            SELECT 1 FROM information_schema.table_constraints tc 
                            WHERE tc.constraint_name = k.constraint_name 
                            AND tc.constraint_type = 'PRIMARY KEY'
                        )
                    ) THEN 'PK'
                    WHEN EXISTS (
                        SELECT 1 FROM information_schema.key_column_usage k 
                        WHERE k.table_name = t.table_name 
                        AND k.column_name = c.column_name
                        AND EXISTS (
                            SELECT 1 FROM information_schema.table_constraints tc 
                            WHERE tc.constraint_name = k.constraint_name 
                            AND tc.constraint_type = 'FOREIGN KEY'
                        )
                    ) THEN 'FK'
                    ELSE ''
                END as key_type,
                pgd.description
            FROM information_schema.tables t
            JOIN information_schema.columns c ON t.table_name = c.table_name
            LEFT JOIN pg_catalog.pg_statio_all_tables st ON t.table_name = st.relname
            LEFT JOIN pg_catalog.pg_description pgd ON pgd.objoid = st.relid 
                AND pgd.objsubid = c.ordinal_position
            WHERE t.table_schema = 'public' 
            AND t.table_type = 'BASE TABLE'
            ORDER BY t.table_name, c.ordinal_position
        ");

        // Agrupar por tabla
        $groupedData = [];
        foreach ($tables as $row) {
            $groupedData[$row->table_name][] = $row;
        }

        // ========== CREAR DOCUMENTO WORD CON ESTILOS ==========
        $phpWord = new PhpWord();
        
        // ========== ESTILOS PERSONALIZADOS ==========
        
        // Estilo para títulos principales
        $phpWord->addTitleStyle(1, [
            'bold' => true, 
            'size' => 20, 
            'color' => '2C3E50',
            'allCaps' => true,
            'alignment' => 'center',
            'spaceAfter' => 200
        ]);
        
        // Estilo para subtítulos
        $phpWord->addTitleStyle(2, [
            'bold' => true, 
            'size' => 16, 
            'color' => '3498DB',
            'underline' => 'single',
            'spaceBefore' => 300,
            'spaceAfter' => 150
        ]);
        
        // Estilo para encabezados de tabla
        $headerStyle = [
            'bold' => true, 
            'color' => '000000',
            'size' => 11,
            'name' => 'Arial'
        ];
        
        // Estilo para texto normal
        $normalStyle = [
            'size' => 10,
            'name' => 'Arial'
        ];
        
        // Estilo para PK
        $pkStyle = [
            'bold' => true,
            'color' => '006600',
            'bgColor' => 'c1f0c8',
            'size' => 10
        ];
        
        // Estilo para FK
        $fkStyle = [
            'bold' => true,
            'color' => '0000CC',
            'bgColor' => 'c1f0c8',
            'size' => 10
        ];
        
        // Estilo para tabla
        $tableStyle = [
            'borderSize' => 6,
            'borderColor' => '47d45a',  // Cambia 'CCCCCC' a '47d45a'
            'cellMargin' => 50,
            'alignment' => 'center'
        ];
        
        // Estilo para celdas
        $cellStyle = [
            'valign' => 'center'
        ];
        
        // ========== PORTADA ==========
        $section = $phpWord->addSection([
            'marginLeft' => 1134,  // 2 cm
            'marginRight' => 1134,
            'marginTop' => 1418,   // 2.5 cm
            'marginBottom' => 1134
        ]);
        
        // Logo o imagen (opcional)
        // $section->addImage('path/to/logo.png', ['width' => 100, 'height' => 100, 'alignment' => 'center']);
        
        // Título principal
        $section->addTitle('DICCIONARIO DE DATOS', 1);
        
        // Información del proyecto
        $section->addTextBreak(2);
        
        $infoTable = $section->addTable([
            'borderSize' => 0,
            'cellMargin' => 80,
            'width' => 100 * 50
        ]);
        
        $infoTable->addRow();
        $infoTable->addCell(3000)->addText('Proyecto:', ['bold' => true, 'size' => 12]);
        $infoTable->addCell(5000)->addText('ProyectoUPTP4', ['size' => 12]);
        
        $infoTable->addRow();
        $infoTable->addCell(3000)->addText('Base de Datos:', ['bold' => true, 'size' => 12]);
        $infoTable->addCell(5000)->addText(env('DB_DATABASE'), ['size' => 12]);
        
        $infoTable->addRow();
        $infoTable->addCell(3000)->addText('Fecha de Generación:', ['bold' => true, 'size' => 12]);
        $infoTable->addCell(5000)->addText(date('d/m/Y H:i:s'), ['size' => 12]);
        
        $infoTable->addRow();
        $infoTable->addCell(3000)->addText('Total de Tablas:', ['bold' => true, 'size' => 12]);
        $infoTable->addCell(5000)->addText(count($groupedData), ['size' => 12]);
        
        // Firma o pie de página
        $section->addTextBreak(4);
        $section->addText('_________________________________', ['alignment' => 'center']);
        $section->addText('Documento generado automáticamente', ['italic' => true, 'alignment' => 'center', 'size' => 10]);
        
        // ========== ÍNDICE ==========
        $section->addPageBreak();
        $section->addTitle('ÍNDICE DE TABLAS', 2);
        
        $indexTable = $section->addTable([
            'borderSize' => 1,
            'borderColor' => '999999',
            'cellMargin' => 80
        ]);
        
        $indexTable->addRow();
        $indexTable->addCell(2000, $cellStyle)->addText('N°', $headerStyle);
        $indexTable->addCell(6000, $cellStyle)->addText('Nombre de Tabla', $headerStyle);
        $indexTable->addCell(3000, $cellStyle)->addText('N° Columnas', $headerStyle);
        
        $counter = 1;
        foreach ($groupedData as $tableName => $columns) {
            $indexTable->addRow();
            $indexTable->addCell(2000, $cellStyle)->addText($counter, $normalStyle);
            $indexTable->addCell(6000, $cellStyle)->addText($tableName, $normalStyle);
            $indexTable->addCell(3000, $cellStyle)->addText(count($columns), $normalStyle);
            $counter++;
        }
        
        // ========== TABLAS DETALLADAS ==========
        $counter = 1;
        foreach ($groupedData as $tableName => $columns) {
            $section->addPageBreak();
            
            // Encabezado de tabla con numeración
            $section->addTitle("$counter. Tabla: $tableName", 2);
            
            // Estadísticas de la tabla
            $stats = $this->getTableStats($columns);
            
            $statsText = "Columnas: {$stats['total']} | ";
            $statsText .= "PK: {$stats['pk']} | ";
            $statsText .= "FK: {$stats['fk']} | ";
            $statsText .= "Nulables: {$stats['nullable']}";
            
            $section->addText($statsText, ['italic' => true, 'size' => 10, 'color' => '666666']);
            $section->addTextBreak(1);
            
            // Crear tabla detallada
            $table = $section->addTable($tableStyle);
            
            // Encabezados con estilo
            $table->addRow(400); // Altura de fila
            
            $headers = ['Columna', 'Tipo de Dato', 'Nulable', 'Valor por Defecto', 'Clave', 'Descripción'];
            foreach ($headers as $header) {
                $cell = $table->addCell(2000, array_merge($cellStyle, ['bgColor' => 'c1f0c8']));
                $cell->addText($header, $headerStyle);
            }
            
            // Filas de datos con estilo alternado
            $rowNum = 0;
            foreach ($columns as $col) {
                $rowStyle = ($rowNum % 2 == 0) 
                    ? ['bgColor' => 'FFFFFF'] 
                    : ['bgColor' => 'F8F9FA'];
                
                $table->addRow();
                
                // Columna
                $table->addCell(2000, array_merge($cellStyle, $rowStyle))
                      ->addText($col->column_name, $normalStyle);
                
                // Tipo de dato
                $type = $col->data_type;
                if ($col->character_maximum_length) {
                    $type .= "({$col->character_maximum_length})";
                } elseif ($col->numeric_precision) {
                    $type .= "({$col->numeric_precision},{$col->numeric_scale})";
                }
                $table->addCell(2000, array_merge($cellStyle, $rowStyle))
                      ->addText($type, array_merge($normalStyle, ['name' => 'Consolas']));
                
                // Nulable
                $nullableText = $col->is_nullable === 'YES' ? 'Sí' : 'No';
                $nullableStyle = $col->is_nullable === 'YES' 
                    ? array_merge($normalStyle, ['color' => 'E74C3C']) 
                    : array_merge($normalStyle, ['color' => '27AE60']);
                
                $table->addCell(1500, array_merge($cellStyle, $rowStyle))
                      ->addText($nullableText, $nullableStyle);
                
                // Valor por defecto
                $defaultText = $col->column_default ?? '--';
                $table->addCell(2500, array_merge($cellStyle, $rowStyle))
                      ->addText($defaultText, array_merge($normalStyle, ['name' => 'Consolas']));
                
                // Clave (PK/FK)
                $keyCell = $table->addCell(1500, array_merge($cellStyle, $rowStyle));
                if ($col->key_type === 'PK') {
                    $keyCell->addText('PRIMARY KEY', $pkStyle);
                } elseif ($col->key_type === 'FK') {
                    $keyCell->addText('FOREIGN KEY', $fkStyle);
                } else {
                    $keyCell->addText('--', $normalStyle);
                }
                
                // Descripción
                $descText = $col->description ?? 'Sin descripción';
                $table->addCell(2500, array_merge($cellStyle, $rowStyle))
                      ->addText($descText, array_merge($normalStyle, ['italic' => true, 'color' => '7F8C8D']));
                
                $rowNum++;
            }
            
            // Leyenda de colores
            $section->addTextBreak(1);
            $legend = $section->addTable(['borderSize' => 0, 'cellMargin' => 50]);
            $legend->addRow();
            
            $legend->addCell(2000)->addText('Leyenda:', ['bold' => true, 'size' => 9]);
            $legend->addCell(1500)->addText('PRIMARY KEY', array_merge($pkStyle, ['size' => 9]));
            $legend->addCell(1500)->addText('FOREIGN KEY', array_merge($fkStyle, ['size' => 9]));
            $legend->addCell(1500)->addText('Nulable: Sí', ['color' => 'E74C3C', 'size' => 9]);
            $legend->addCell(1500)->addText('Nulable: No', ['color' => '27AE60', 'size' => 9]);
            
            $counter++;
            $section->addTextBreak(2);
        }
        
        // ========== RESUMEN FINAL ==========
        $section->addPageBreak();
        $section->addTitle('RESUMEN GENERAL', 2);
        
        $summaryStats = $this->getSummaryStats($groupedData);
        
        $summaryTable = $section->addTable([
            'borderSize' => 1,
            'borderColor' => '3498DB',
            'cellMargin' => 100,
            'width' => 100 * 50
        ]);
        
        $summaryRows = [
            ['Total de Tablas en el Sistema', $summaryStats['total_tables']],
            ['Total de Columnas Analizadas', $summaryStats['total_columns']],
            ['Claves Primarias (PK)', $summaryStats['total_pk']],
            ['Claves Foráneas (FK)', $summaryStats['total_fk']],
            ['Columnas que permiten NULL', $summaryStats['total_nullable']],
            ['Columnas con Valor por Defecto', $summaryStats['total_with_default']]
        ];
        
        foreach ($summaryRows as $row) {
            $summaryTable->addRow();
            $summaryTable->addCell(6000, $cellStyle)->addText($row[0], ['bold' => true]);
            $summaryTable->addCell(2000, $cellStyle)->addText($row[1], ['alignment' => 'center', 'bold' => true]);
        }
        
        // Pie de página final
        $section->addTextBreak(3);
        $section->addText('Fin del Documento', ['alignment' => 'center', 'italic' => true, 'size' => 10]);
        $section->addText('© ' . date('Y') . ' - ProyectoUPTP4', ['alignment' => 'center', 'size' => 9, 'color' => '95A5A6']);

        // ========== GUARDAR Y DESCARGAR ==========
        $fileName = 'Diccionario_Datos_' . env('DB_DATABASE') . '_' . date('Ymd_His') . '.docx';
        $filePath = storage_path('app/public/' . $fileName);
        
        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($filePath);
        
        return response()->download($filePath, $fileName)->deleteFileAfterSend(true);
    }
    
    /**
     * Obtiene estadísticas de una tabla
     */
    private function getTableStats($columns)
    {
        $stats = [
            'total' => count($columns),
            'pk' => 0,
            'fk' => 0,
            'nullable' => 0
        ];
        
        foreach ($columns as $col) {
            if ($col->key_type === 'PK') $stats['pk']++;
            if ($col->key_type === 'FK') $stats['fk']++;
            if ($col->is_nullable === 'YES') $stats['nullable']++;
        }
        
        return $stats;
    }
    
    /**
     * Obtiene estadísticas generales
     */
    private function getSummaryStats($groupedData)
    {
        $stats = [
            'total_tables' => count($groupedData),
            'total_columns' => 0,
            'total_pk' => 0,
            'total_fk' => 0,
            'total_nullable' => 0,
            'total_with_default' => 0
        ];
        
        foreach ($groupedData as $columns) {
            $stats['total_columns'] += count($columns);
            
            foreach ($columns as $col) {
                if ($col->key_type === 'PK') $stats['total_pk']++;
                if ($col->key_type === 'FK') $stats['total_fk']++;
                if ($col->is_nullable === 'YES') $stats['total_nullable']++;
                if (!empty($col->column_default)) $stats['total_with_default']++;
            }
        }
        
        return $stats;
    }
}