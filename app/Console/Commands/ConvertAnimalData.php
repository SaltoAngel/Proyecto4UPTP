<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Especie;
use App\Models\TipoAnimal;
use App\Models\RequerimientoNutricional;
use App\Models\Aminoacido;
use App\Models\Mineral;
use App\Models\Vitamina;
use App\Models\Alimento;
use App\Models\CategoriaMateriaPrima;
use App\Models\MateriaPrima;
use App\Models\ComposicionNutricional;
use App\Models\CostoMateriaPrima;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ConvertAnimalData extends Command
{
    protected $signature = 'convert:animal-data {--chunk=500} {--skip-constraints} {--only-animal}';
    protected $description = 'Convierte datos antiguos de animales al nuevo esquema completo';
    
    // MAPEO CORREGIDO según tu esquema
    protected $especiesMap = [
        1 => 'Cerdo',
        2 => 'Bovino',
        3 => 'Ave',
        4 => 'Ratón',
        5 => 'Conejo',
        6 => 'Ovino',
        7 => 'Caprino',
        8 => 'Caballo',
        9 => 'Perro',
        10 => 'Gato',
    ];
    
    // Mapeo de tipos de producto según tu ENUM
    protected $productoFinalMap = [
        'leche' => 'leche',
        'carne' => 'carne', 
        'huevos' => 'huevos',
        'huevo' => 'huevos',
        'doble_proposito' => 'doble_proposito',
        'reproduccion' => 'reproduccion',
        'reproducción' => 'reproduccion',
        'trabajo' => 'trabajo',
        'lana' => 'lana',
        'miel' => 'miel',
        'mascota' => 'otro',
        'investigacion' => 'otro',
        'investigación' => 'otro',
        'produccion' => 'otro',
        'producción' => 'otro'
    ];
    
    // Mapeo de sistemas de producción según tu ENUM
    protected $sistemaProduccionMap = [
        'intensivo' => 'intensivo',
        'semi-intensivo' => 'semi-intensivo', 
        'extensivo' => 'extensivo',
        'organico' => 'organico',
        'orgánico' => 'organico',
        'confinamiento' => 'intensivo'
    ];
    
    // Categorías de materia prima según tu ENUM
    protected $categoriasMateriaPrima = [
        'energeticos' => 'energetico',
        'proteicos' => 'proteico',
        'forrajes_verdes' => 'forraje_verde',
        'forrajes_secos' => 'forraje_seco',
        'ensilajes' => 'ensilaje',
        'minerales' => 'mineral',
        'vitaminas' => 'vitamina',
        'aditivos' => 'aditivo'
    ];
    
    public function handle()
    {
        $this->info('📦 INICIANDO CONVERSIÓN COMPLETA DE DATOS...');
        
        // Solo eliminar restricciones si se solicita
        if ($this->option('skip-constraints')) {
            $this->removeUniqueConstraints();
        }
        
        // Crear categorías de materia prima si no existen
        $this->createCategoriasMateriaPrima();
        
        // Cargar datos antiguos
        $oldData = $this->loadOldData();
        
        $total = count($oldData);
        $this->info("📊 Total de registros a procesar: $total");
        
        // Procesar por lotes
        $chunkSize = $this->option('chunk');
        $chunks = array_chunk($oldData, $chunkSize);
        
        $successCount = 0;
        $errorCount = 0;
        $animalCount = 0;
        $materiaPrimaCount = 0;
        
        foreach ($chunks as $chunkIndex => $chunk) {
            $this->info("🔄 Procesando lote " . ($chunkIndex + 1) . " de " . count($chunks));
            
            foreach ($chunk as $index => $animal) {
                $globalIndex = ($chunkIndex * $chunkSize) + $index + 1;
                
                try {
                    // Convertir datos del animal
                    $tipoAnimal = $this->convertAnimalData($animal, $globalIndex);
                    
                    if ($tipoAnimal) {
                        $animalCount++;
                        
                        // Si no es --only-animal, procesar también materias primas
                        if (!$this->option('only-animal')) {
                            $materiasCreadas = $this->convertMateriasPrimas($tipoAnimal, $animal);
                            $materiaPrimaCount += $materiasCreadas;
                        }
                    }
                    
                    $successCount++;
                    
                    // Mostrar progreso
                    if ($globalIndex % 50 == 0) {
                        $percent = round(($globalIndex / $total) * 100, 2);
                        $this->info("📈 Progreso: $globalIndex/$total ($percent%) - Animales: $animalCount, Materias: $materiaPrimaCount");
                    }
                    
                } catch (\Exception $e) {
                    $errorCount++;
                    $this->error("❌ Error en registro $globalIndex: " . $e->getMessage());
                    Log::error("Error converting animal $globalIndex", [
                        'error' => $e->getMessage(),
                        'animal' => $animal
                    ]);
                }
            }
        }
        
        $this->info('✅ CONVERSIÓN COMPLETADA!');
        $this->info('📈 Estadísticas:');
        $this->info("- Registros procesados: $successCount/$total");
        $this->info("- Errores: $errorCount");
        $this->info('- Especies: ' . Especie::count());
        $this->info('- Tipos de animal: ' . TipoAnimal::count());
        $this->info('- Requerimientos: ' . RequerimientoNutricional::count());
        $this->info('- Categorías MP: ' . CategoriaMateriaPrima::count());
        $this->info('- Materias primas: ' . MateriaPrima::count());
        $this->info('- Composiciones: ' . ComposicionNutricional::count());
        $this->info('- Tolerancias: ' . Alimento::count());
    }
    
    private function removeUniqueConstraints()
    {
        try {
            $this->info('🔄 Eliminando restricciones únicas temporales...');
            
            DB::statement('ALTER TABLE tipos_animal DROP CONSTRAINT IF EXISTS tipos_animal_especie_id_nombre_raza_linea_etapa_especifica_uniq');
            DB::statement('ALTER TABLE requerimientos_nutricionales DROP CONSTRAINT IF EXISTS requerimientos_nutricionales_tipo_animal_id_descripcion_unique');
            
            $this->info('✅ Restricciones eliminadas temporalmente');
        } catch (\Exception $e) {
            $this->warn('⚠️ No se pudieron eliminar las restricciones: ' . $e->getMessage());
        }
    }
    
    private function createCategoriasMateriaPrima()
    {
        $categorias = [
            ['nombre' => 'Energéticos', 'codigo_nrc' => 'ENER', 'tipo' => 'energetico', 'orden' => 1],
            ['nombre' => 'Proteicos', 'codigo_nrc' => 'PROT', 'tipo' => 'proteico', 'orden' => 2],
            ['nombre' => 'Forrajes Verdes', 'codigo_nrc' => 'FRV', 'tipo' => 'forraje_verde', 'orden' => 3],
            ['nombre' => 'Forrajes Secos', 'codigo_nrc' => 'FRS', 'tipo' => 'forraje_seco', 'orden' => 4],
            ['nombre' => 'Ensilajes', 'codigo_nrc' => 'ENS', 'tipo' => 'ensilaje', 'orden' => 5],
            ['nombre' => 'Minerales', 'codigo_nrc' => 'MIN', 'tipo' => 'mineral', 'orden' => 6],
            ['nombre' => 'Vitaminas', 'codigo_nrc' => 'VIT', 'tipo' => 'vitamina', 'orden' => 7],
            ['nombre' => 'Aditivos', 'codigo_nrc' => 'ADT', 'tipo' => 'aditivo', 'orden' => 8],
            ['nombre' => 'Suplementos', 'codigo_nrc' => 'SUP', 'tipo' => 'suplemento', 'orden' => 9],
            ['nombre' => 'Otros', 'codigo_nrc' => 'OTR', 'tipo' => 'otro', 'orden' => 10],
        ];
        
        foreach ($categorias as $cat) {
            CategoriaMateriaPrima::firstOrCreate(
                ['nombre' => $cat['nombre']],
                $cat
            );
        }
        
        $this->info('✅ Categorías de materia prima creadas/verificadas');
    }
    
    private function loadOldData()
    {
        $phpFile = storage_path('app/old_animals_data.php');
        if (file_exists($phpFile)) {
            $this->info("📂 Cargando desde archivo PHP: $phpFile");
            $data = include $phpFile;
            
            // Filtrar datos válidos
            $data = array_filter($data, function($animal) {
                return !empty($animal['descripcion']) || 
                       !empty($animal['consumoesperado']) ||
                       !empty($animal['proteinacruda']);
            });
            
            $this->info("📊 Datos válidos encontrados: " . count($data));
            return $data;
        }
        
        throw new \Exception("No se encontraron datos antiguos.");
    }
    
    private function convertAnimalData($animal, $index)
    {
        DB::beginTransaction();
        
        try {
            // 1. ESPECIE
            $especieNombre = $this->detectEspecie($animal);
            $especie = Especie::firstOrCreate(
                ['nombre' => $especieNombre],
                [
                    'codigo' => strtoupper(substr($especieNombre, 0, 3)),
                    'descripcion' => 'Importado automáticamente',
                    'activo' => true
                ]
            );
            
            // 2. PARSEAR DESCRIPCIÓN
            $parsed = $this->parseDescription($animal['descripcion'] ?? '');
            
            // 3. TIPO DE ANIMAL - según tu esquema
            $tipoAnimalData = [
                'especie_id' => $especie->id,
                'nombre' => $this->generateAnimalName($especieNombre, $parsed),
                'raza_linea' => $parsed['raza'] ?? null,
                'producto_final' => $parsed['producto_final'] ?? 'otro',
                'sistema_produccion' => $parsed['sistema'] ?? 'intensivo',
                'etapa_especifica' => $parsed['etapa'] ?? 'General',
                'edad_semanas' => $parsed['edad_semanas'] ?? null,
                'peso_minimo_kg' => $parsed['peso_minimo'] ?? null,
                'descripcion' => $animal['descripcion'] ?? 'Sin descripción',
                'activo' => true
            ];
            
            // Buscar tipo similar existente
            $tipoAnimal = TipoAnimal::where('especie_id', $especie->id)
                ->where('nombre', $tipoAnimalData['nombre'])
                ->where('raza_linea', $tipoAnimalData['raza_linea'])
                ->where('etapa_especifica', $tipoAnimalData['etapa_especifica'])
                ->where('edad_semanas', $tipoAnimalData['edad_semanas'])
                ->first();
            
            if (!$tipoAnimal) {
                $tipoAnimal = TipoAnimal::create($tipoAnimalData);
            }
            
            // 4. REQUERIMIENTO NUTRICIONAL - según tu esquema
            $requerimientoData = [
                'tipo_animal_id' => $tipoAnimal->id,
                'descripcion' => $this->generateRequerimientoDesc($tipoAnimal, $animal),
                'fuente' => 'Sistema anterior',
                'comentario' => $this->generateComentario($animal),
                'consumo_esperado_kg_dia' => $this->safeDecimal($animal['consumoesperado'], 10, 4),
                'preferido' => $this->safeBoolean($animal['preferido']),
                'humedad' => $this->safeDecimal($animal['humedad'], 8, 4),
                'materia_seca' => $this->safeDecimal($animal['materiaseca'], 8, 4),
                'proteina_cruda' => $this->safeDecimal($animal['proteinacruda'], 8, 4),
                'fibra_bruta' => $this->safeDecimal($animal['fibrabruta'], 8, 4),
                'extracto_etereo' => $this->safeDecimal($animal['extractoetereo'], 8, 4),
                'eln' => $this->safeDecimal($animal['eln'], 8, 4),
                'ceniza' => $this->safeDecimal($animal['ceniza'], 8, 4),
                'energia_digestible' => $this->safeDecimal($animal['enerdig'], 10, 4),
                'energia_metabolizable' => $this->safeDecimal($animal['enermet'], 10, 4),
                'energia_neta' => $this->safeDecimal($animal['enerneta'], 10, 4),
                'energia_digestible_ap' => $this->safeDecimal($animal['enerdigap'], 10, 4),
                'energia_metabolizable_ap' => $this->safeDecimal($animal['enermetap'], 10, 4),
                'activo' => true
            ];
            
            $requerimiento = RequerimientoNutricional::updateOrCreate(
                [
                    'tipo_animal_id' => $tipoAnimal->id,
                    'descripcion' => $requerimientoData['descripcion']
                ],
                $requerimientoData
            );
            
            // 5. AMINOÁCIDOS - según tu esquema (pivote requerimiento_aminoacido)
            $this->saveAminoacidos($requerimiento, $animal);
            
            // 6. MINERALES - según tu esquema (pivote requerimiento_mineral)
            $this->saveMinerales($requerimiento, $animal);
            
            // 7. VITAMINAS - según tu esquema (pivote requerimiento_vitamina)
            $this->saveVitaminas($requerimiento, $animal);
            
            DB::commit();
            
            return $tipoAnimal;
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    
    private function convertMateriasPrimas($tipoAnimal, $animal)
    {
        $materiasCreadas = 0;
        
        // 8. CREAR MATERIAS PRIMAS (TOLERANCIAS) - según tu esquema
        $tolerancias = [
            'energeticos' => ['max' => $animal['maxenergetico'] ?? null, 'activo' => $animal['energeticos'] ?? false],
            'proteicos' => ['max' => $animal['maxproteico'] ?? null, 'activo' => $animal['proteicos'] ?? false],
            'forrajes_verdes' => ['max' => $animal['maxforrajeverde'] ?? null, 'activo' => $animal['forrajesverdes'] ?? false],
            'forrajes_secos' => ['max' => $animal['maxforrajeseco'] ?? null, 'activo' => $animal['forrajessecos'] ?? false],
            'ensilajes' => ['max' => $animal['maxensilaje'] ?? null, 'activo' => $animal['ensilajes'] ?? false],
            'minerales' => ['max' => $animal['maxmineral'] ?? null, 'activo' => $animal['minerales'] ?? false],
            'vitaminas' => ['max' => $animal['maxvitamina'] ?? null, 'activo' => $animal['vitaminas'] ?? false],
            'aditivos' => ['max' => $animal['maxaditivo'] ?? null, 'activo' => $animal['aditivos'] ?? false],
        ];
        
        foreach ($tolerancias as $tipo => $info) {
            if ($this->safeBoolean($info['activo']) && $info['max'] !== null) {
                $maxValue = $this->safeDecimal($info['max'], 5, 2); // decimal(5,2)
                
                if ($maxValue !== null) {
                    // A. Crear/actualizar alimento (tolerancia)
                    $alimento = Alimento::updateOrCreate(
                        [
                            'tipo_animal_id' => $tipoAnimal->id,
                            'tipo' => $tipo
                        ],
                        [
                            'cantidad_maxima' => $maxValue
                        ]
                    );
                    
                    // B. Crear materia prima para esta categoría
                    $categoriaNombre = ucfirst(str_replace('_', ' ', $tipo));
                    $categoria = CategoriaMateriaPrima::where('nombre', 'like', "%{$categoriaNombre}%")->first();
                    
                    if ($categoria) {
                        $materiaPrima = MateriaPrima::firstOrCreate(
                            [
                                'codigo' => "TOL_{$tipoAnimal->id}_{$tipo}"
                            ],
                            [
                                'categoria_id' => $categoria->id,
                                'descripcion' => "Tolerancia para {$tipoAnimal->nombre} - {$categoriaNombre}",
                                'nombre_comercial' => "Tolerancia {$categoriaNombre}",
                                'comentario' => "Importado automáticamente. Máximo: {$maxValue}%",
                                'preferido' => false,
                                'activo' => true,
                                'disponible' => true
                            ]
                        );
                        
                        // C. Crear composición nutricional básica
                        if (!$materiaPrima->composiciones()->exists()) {
                            $composicion = ComposicionNutricional::create([
                                'materia_prima_id' => $materiaPrima->id,
                                'version' => '1.0',
                                'fecha_analisis' => now(),
                                'laboratorio' => 'Sistema anterior',
                                'metodo_analisis' => 'Importación',
                                'humedad' => null,
                                'materia_seca' => 100.0, // Asumir base seca
                                'proteina_cruda' => ($tipo === 'proteicos') ? 30.0 : null,
                                'fibra_bruta' => ($tipo === 'forrajes_verdes' || $tipo === 'forrajes_secos') ? 25.0 : null,
                                'extracto_etereo' => ($tipo === 'energeticos') ? 4.0 : null,
                                'eln' => null,
                                'ceniza' => ($tipo === 'minerales') ? 90.0 : null,
                                'observaciones' => "Composición estimada para tolerancia",
                                'es_predeterminado' => true
                            ]);
                            
                            // D. Crear costo asociado
                            CostoMateriaPrima::create([
                                'materia_prima_id' => $materiaPrima->id,
                                'costo_unitario' => 0.0,
                                'unidad_compra' => 'kg',
                                'factor_conversion' => 1.0,
                                'moneda' => 'USD',
                                'fecha_vigencia' => now(),
                                'activo' => true
                            ]);
                        }
                        
                        $materiasCreadas++;
                    }
                }
            }
        }
        
        return $materiasCreadas;
    }
    
    private function detectEspecie($animal)
    {
        $descripcion = strtolower($animal['descripcion'] ?? '');
        
        $detectionRules = [
            'cerdo' => 'Cerdo',
            'yorkshire' => 'Cerdo',
            'lechón' => 'Cerdo',
            'marrano' => 'Cerdo',
            'porcino' => 'Cerdo',
            'pollo' => 'Ave',
            'gallina' => 'Ave',
            'cobb' => 'Ave',
            'ross' => 'Ave',
            'ratón' => 'Ratón',
            'raton' => 'Ratón',
            'mus musculus' => 'Ratón',
            'icr' => 'Ratón',
            'cd-1' => 'Ratón',
            'ovino' => 'Ovino',
            'oveja' => 'Ovino',
            'cordero' => 'Ovino',
            'bovino' => 'Bovino',
            'vaca' => 'Bovino',
            'toro' => 'Bovino',
            'ternero' => 'Bovino',
            'ganado' => 'Bovino',
            'conejo' => 'Conejo',
            'caprino' => 'Caprino',
            'cabra' => 'Caprino',
            'caballo' => 'Caballo',
            'equino' => 'Caballo',
            'perro' => 'Perro',
            'canino' => 'Perro',
            'gato' => 'Gato',
            'felino' => 'Gato',
        ];
        
        foreach ($detectionRules as $keyword => $especie) {
            if (strpos($descripcion, $keyword) !== false) {
                return $especie;
            }
        }
        
        // Usar mapeo original como fallback
        $tipoId = $animal['tipodeanimal'] ?? null;
        return $this->especiesMap[$tipoId] ?? 'Desconocida';
    }
    
    private function parseDescription($desc)
    {
        $result = [];
        
        if (empty($desc)) {
            return $result;
        }
        
        $desc = str_replace(['Â', 'Ã', 'º', '°'], ['', '', '', ''], $desc);
        
        // Raza
        $razas = ['Yorkshire', 'Holstein', 'COBB 500', 'COBB 700', 'ROSS 308', 'ROSS 708', 'ICR', 'CD-1'];
        foreach ($razas as $raza) {
            if (strpos($desc, $raza) !== false) {
                $result['raza'] = $raza;
                break;
            }
        }
        
        // Etapa
        $etapas = [
            'gestacion' => 'Gestación',
            'gestación' => 'Gestación',
            'lactancia' => 'Lactancia',
            'crecimiento' => 'Crecimiento',
            'mantenimiento' => 'Mantenimiento',
            'acabado' => 'Acabado',
            'inicio' => 'Inicio',
            'desarrollo' => 'Desarrollo',
            'finalización' => 'Finalización'
        ];
        
        foreach ($etapas as $key => $value) {
            if (stripos($desc, $key) !== false) {
                $result['etapa'] = $value;
                break;
            }
        }
        
        // Edad en semanas
        if (preg_match('/(\d+)[ºª]\s*sem/i', $desc, $match)) {
            $result['edad_semanas'] = (int) $match[1];
        } elseif (preg_match('/sem\.?\s*(\d+)/i', $desc, $match)) {
            $result['edad_semanas'] = (int) $match[1];
        } elseif (preg_match('/(\d+)\s*semanas/i', $desc, $match)) {
            $result['edad_semanas'] = (int) $match[1];
        }
        
        // Peso mínimo
        if (preg_match('/(\d+(?:\.\d+)?)\s*kg/i', $desc, $match)) {
            $result['peso_minimo'] = (float) $match[1];
        }
        
        // Producto final
        if (preg_match('/(carne|leche|huevo|huevos|reproducci[oó]n|trabajo|lana|miel|mascota|investigaci[oó]n)/i', $desc, $match)) {
            $producto = strtolower($match[1]);
            $result['producto_final'] = $this->productoFinalMap[$producto] ?? 'otro';
        }
        
        // Sistema de producción
        if (preg_match('/(intensivo|semi-intensivo|extensivo|organico|orgánico)/i', $desc, $match)) {
            $sistema = strtolower($match[1]);
            $result['sistema'] = $this->sistemaProduccionMap[$sistema] ?? 'intensivo';
        }
        
        return $result;
    }
    
    private function generateAnimalName($especie, $parsed)
    {
        $parts = [$especie];
        
        if (!empty($parsed['etapa']) && $parsed['etapa'] !== 'General') {
            $parts[] = $parsed['etapa'];
        }
        
        if (!empty($parsed['raza'])) {
            $parts[] = $parsed['raza'];
        }
        
        if (!empty($parsed['edad_semanas'])) {
            $parts[] = $parsed['edad_semanas'] . ' semanas';
        }
        
        return implode(' ', $parts);
    }
    
    private function generateRequerimientoDesc($tipoAnimal, $animal)
    {
        $desc = "Requerimiento para {$tipoAnimal->nombre}";
        
        if (!empty($animal['consumoesperado'])) {
            $desc .= " (Consumo: " . $animal['consumoesperado'] . " kg/día)";
        }
        
        return $desc;
    }
    
    private function generateComentario($animal)
    {
        $comentarios = [];
        
        if (!empty($animal['comentario'])) {
            $comentarios[] = "Comentario original: " . $animal['comentario'];
        }
        
        if (!empty($animal['descripcion'])) {
            $comentarios[] = "Descripción: " . substr($animal['descripcion'], 0, 200);
        }
        
        return !empty($comentarios) ? implode(' | ', $comentarios) : null;
    }
    
    private function safeDecimal($value, $precision = 10, $scale = 4)
    {
        if ($value === null || $value === '' || $value === false) {
            return null;
        }
        
        $num = (float) str_replace(',', '.', $value);
        
        // Redondear al número de decimales especificado
        $rounded = round($num, $scale);
        
        // Si es 0 o muy cercano a 0, devolver null
        return (abs($rounded) < pow(10, -$scale)) ? null : $rounded;
    }
    
    private function safeBoolean($value)
    {
        return !($value === null || $value === '' || $value === false || $value === 'false' || $value === 0 || $value === '0');
    }
    
    private function saveAminoacidos($requerimiento, $data)
    {
        $map = [
            'arginina' => 'Arginina',
            'glicina' => 'Glicina',
            'histidina' => 'Histidina',
            'isoleucina' => 'Isoleucina',
            'leucina' => 'Leucina',
            'lisina' => 'Lisina',
            'metionina' => 'Metionina',
            'cistina' => 'Cistina',
            'fenilalanina' => 'Fenilalanina',
            'tirosina' => 'Tirosina',
            'treonina' => 'Treonina',
            'triptofano' => 'Triptófano',
            'valina' => 'Valina',
        ];
        
        foreach ($map as $oldKey => $aminoNombre) {
            if (isset($data[$oldKey]) && ($value = $this->safeDecimal($data[$oldKey], 10, 6)) !== null) {
                $aminoacido = Aminoacido::where('nombre', $aminoNombre)->first();
                if ($aminoacido) {
                    $requerimiento->aminoacidos()->syncWithoutDetaching([
                        $aminoacido->id => [
                            'valor_recomendado' => $value,
                            'unidad' => '%'
                        ]
                    ]);
                }
            }
        }
    }
    
    private function saveMinerales($requerimiento, $data)
    {
        $map = [
            'calcio' => 'Calcio',
            'fosforo' => 'Fósforo',
            'magnesio' => 'Magnesio',
            'potasio' => 'Potasio',
            'sodio' => 'Sodio',
            'cloro' => 'Cloro',
            'cobalto' => 'Cobalto',
            'cobre' => 'Cobre',
            'iodo' => 'Yodo',
            'hierro' => 'Hierro',
            'manganeso' => 'Manganeso',
            'selenio' => 'Selenio',
            'zinc' => 'Zinc',
        ];
        
        foreach ($map as $oldKey => $mineralNombre) {
            if (isset($data[$oldKey]) && ($value = $this->safeDecimal($data[$oldKey], 10, 6)) !== null) {
                $mineral = Mineral::where('nombre', $mineralNombre)->first();
                if ($mineral) {
                    $unidad = ($value > 1) ? '%' : 'mg/kg';
                    $requerimiento->minerales()->syncWithoutDetaching([
                        $mineral->id => [
                            'valor_recomendado' => $value,
                            'unidad' => $unidad
                        ]
                    ]);
                }
            }
        }
    }
    
    private function saveVitaminas($requerimiento, $data)
    {
        $map = [
            'vitaminaa' => 'Vitamina A',
            'vitaminad' => 'Vitamina D',
            'vitaminae' => 'Vitamina E',
            'vitaminak' => 'Vitamina K',
            'biotina' => 'Biotina',
            'colina' => 'Colina',
            'folico' => 'Ácido Fólico',
            'niacina' => 'Niacina',
            'pantotenico' => 'Ácido Pantoténico',
            'riboflavina' => 'Riboflavina',
            'tiamina' => 'Tiamina',
            'vitaminab6' => 'Vitamina B6',
            'vitaminab12' => 'Vitamina B12',
        ];
        
        foreach ($map as $oldKey => $vitaminaNombre) {
            if (isset($data[$oldKey]) && ($value = $this->safeDecimal($data[$oldKey], 10, 6)) !== null) {
                $vitamina = Vitamina::where('nombre', $vitaminaNombre)->first();
                if ($vitamina) {
                    $unidad = in_array($vitaminaNombre, ['Vitamina A', 'Vitamina D', 'Vitamina E']) 
                        ? 'UI/kg' 
                        : 'mg/kg';
                    
                    $requerimiento->vitaminas()->syncWithoutDetaching([
                        $vitamina->id => [
                            'valor_recomendado' => $value,
                            'unidad' => $unidad
                        ]
                    ]);
                }
            }
        }
    }
}

/* # Para convertir solo datos de animales (más rápido)
php artisan convert:animal-data --only-animal --skip-constraints --chunk=500

# Para convertir TODO (animales + materias primas)
php artisan convert:animal-data --skip-constraints --chunk=500
*/