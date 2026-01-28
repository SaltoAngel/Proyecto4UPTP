<?php
// database/seeders/AlimentoAnimalSeeder.php

namespace Database\Seeders;

use App\Models\CategoriasMateriasPrimas;
use App\Models\MateriaPrima;
use App\Models\InventarioMateria;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AlimentoAnimalSeeder extends Seeder
{
    public function run()
    {
        // Limpiar tablas - VERSIÓN COMPATIBLE CON POSTGRESQL
        if (DB::connection()->getDriverName() === 'pgsql') {
            // PostgreSQL: Usar DELETE en lugar de TRUNCATE con CASCADE
            DB::statement('DELETE FROM inventario_materias');
            DB::statement('DELETE FROM materias_primas');
            DB::statement('DELETE FROM categorias_materia_prima');
            
            // Reiniciar secuencias
            DB::statement("SELECT setval('inventario_materias_id_seq', 1, false)");
            DB::statement("SELECT setval('materias_primas_id_seq', 1, false)");
            DB::statement("SELECT setval('categorias_materia_prima_id_seq', 1, false)");
        } else {
            // MySQL: Deshabilitar foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            InventarioMateria::truncate();
            MateriaPrima::truncate();
            CategoriasMateriasPrimas::truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }

        // Crear categorías específicas para alimento animal
        $categorias = [
            ['nombre' => 'Granos y Cereales', 'descripcion' => 'Granos enteros y cereales para alimentación animal'],
            ['nombre' => 'Harinas Proteicas', 'descripcion' => 'Harinas de origen animal y vegetal con alto contenido proteico'],
            ['nombre' => 'Forrajes y Heno', 'descripcion' => 'Forrajes, heno y pastos deshidratados'],
            ['nombre' => 'Vitaminas y Minerales', 'descripcion' => 'Premix vitamínicos y minerales'],
            ['nombre' => 'Grasas y Aceites', 'descripcion' => 'Aceites vegetales y grasas animales'],
            ['nombre' => 'Melaza y Endulzantes', 'descripcion' => 'Melazas y aditivos energéticos'],
            ['nombre' => 'Probióticos y Aditivos', 'descripcion' => 'Aditivos funcionales y probióticos'],
            ['nombre' => 'Embalaje Alimento', 'descripcion' => 'Sacos y embalaje especial'],
            ['nombre' => 'Ingredientes Especiales', 'descripcion' => 'Ingredientes específicos por especie'],
            ['nombre' => 'Conservantes', 'descripcion' => 'Conservantes y antioxidantes'],
        ];

        foreach ($categorias as $categoria) {
            CategoriasMateriasPrimas::create($categoria);
        }

        // Obtener IDs de categorías
        $categoriaIds = CategoriasMateriasPrimas::pluck('id', 'nombre');

        // Datos de materias primas para alimento animal
        $materiasPrimas = [
            [
                'codigo' => 'AA-001',
                'descripcion' => 'Maíz amarillo molido para alimento animal',
                'categoria_id' => $categoriaIds['Granos y Cereales'],
                'nombre_comercial' => 'Maíz Grado Alimento Premium',
                'nombre_cientifico' => 'Zea mays',
                'comentario' => 'Principal fuente de energía en raciones para aves y porcinos',
                'activo' => true,
                'disponible' => true,
                'preferido' => true,
                'inventario' => [
                    'stock_actual' => 12500.50,
                    'stock_minimo' => 5000,
                    'stock_maximo' => 25000,
                    'punto_reorden' => 8000,
                    'almacen' => 'Silo 1 - Zona Granos',
                    'estado' => 'normal'
                ]
            ],
            [
                'codigo' => 'AA-002',
                'descripcion' => 'Sorgo rojo para alimento',
                'categoria_id' => $categoriaIds['Granos y Cereales'],
                'nombre_comercial' => 'Sorgo Alimenticio',
                'nombre_cientifico' => 'Sorghum bicolor',
                'comentario' => 'Alternativa al maíz en épocas secas',
                'activo' => true,
                'disponible' => true,
                'preferido' => false,
                'inventario' => [
                    'stock_actual' => 8500,
                    'stock_minimo' => 3000,
                    'stock_maximo' => 15000,
                    'punto_reorden' => 5000,
                    'almacen' => 'Silo 2 - Zona Granos',
                    'estado' => 'normal'
                ]
            ],
            [
                'codigo' => 'AA-003',
                'descripcion' => 'Cebada cervecera para animales',
                'categoria_id' => $categoriaIds['Granos y Cereales'],
                'nombre_comercial' => 'Cebada Forrajera',
                'nombre_cientifico' => 'Hordeum vulgare',
                'comentario' => 'Para ganado bovino y porcino',
                'activo' => true,
                'disponible' => true,
                'preferido' => false,
                'inventario' => [
                    'stock_actual' => 4200,
                    'stock_minimo' => 2000,
                    'stock_maximo' => 10000,
                    'punto_reorden' => 4000,
                    'almacen' => 'Silo 3 - Zona Granos',
                    'estado' => 'normal'
                ]
            ],
            // Harinas Proteicas
            [
                'codigo' => 'AA-004',
                'descripcion' => 'Harina de soya 48% proteína',
                'categoria_id' => $categoriaIds['Harinas Proteicas'],
                'nombre_comercial' => 'Harina Soya Grado Alimento',
                'nombre_cientifico' => 'Glycine max',
                'comentario' => 'Principal fuente proteica vegetal',
                'activo' => true,
                'disponible' => true,
                'preferido' => true,
                'inventario' => [
                    'stock_actual' => 3200,
                    'stock_minimo' => 1500,
                    'stock_maximo' => 8000,
                    'punto_reorden' => 3000,
                    'almacen' => 'Almacén Proteínas - Estante 1',
                    'estado' => 'critico'
                ]
            ],
            [
                'codigo' => 'AA-005',
                'descripcion' => 'Harina de pescado 65% proteína',
                'categoria_id' => $categoriaIds['Harinas Proteicas'],
                'nombre_comercial' => 'Harina Pescado Premium',
                'nombre_cientifico' => null,
                'comentario' => 'Para alimento de aves y acuicultura',
                'activo' => true,
                'disponible' => true,
                'preferido' => true,
                'inventario' => [
                    'stock_actual' => 1800,
                    'stock_minimo' => 1000,
                    'stock_maximo' => 5000,
                    'punto_reorden' => 2000,
                    'almacen' => 'Almacén Proteínas - Frío',
                    'estado' => 'normal'
                ]
            ],
            [
                'codigo' => 'AA-006',
                'descripcion' => 'Torta de algodón desgrasada',
                'categoria_id' => $categoriaIds['Harinas Proteicas'],
                'nombre_comercial' => 'Torta Algodón 38%',
                'nombre_cientifico' => 'Gossypium hirsutum',
                'comentario' => 'Para rumiantes, bajo en gossypol',
                'activo' => true,
                'disponible' => true,
                'preferido' => false,
                'inventario' => [
                    'stock_actual' => 0,
                    'stock_minimo' => 800,
                    'stock_maximo' => 4000,
                    'punto_reorden' => 1500,
                    'almacen' => 'Almacén Proteínas - Estante 2',
                    'estado' => 'agotado'
                ]
            ],
            // Forrajes y Heno
            [
                'codigo' => 'AA-007',
                'descripcion' => 'Heno de alfalfa premium',
                'categoria_id' => $categoriaIds['Forrajes y Heno'],
                'nombre_comercial' => 'Alfalfa Deshidratada',
                'nombre_cientifico' => 'Medicago sativa',
                'comentario' => '18% proteína, para equinos y vacas lecheras',
                'activo' => true,
                'disponible' => true,
                'preferido' => true,
                'inventario' => [
                    'stock_actual' => 850,
                    'stock_minimo' => 500,
                    'stock_maximo' => 2000,
                    'punto_reorden' => 800,
                    'almacen' => 'Galpón Forrajes - Bloque 1',
                    'estado' => 'critico'
                ]
            ],
            [
                'codigo' => 'AA-008',
                'descripcion' => 'Paca de paja de trigo',
                'categoria_id' => $categoriaIds['Forrajes y Heno'],
                'nombre_comercial' => 'Paja Trigo Empaquetada',
                'nombre_cientifico' => 'Triticum aestivum',
                'comentario' => 'Fibra para cama y alimentación',
                'activo' => true,
                'disponible' => true,
                'preferido' => false,
                'inventario' => [
                    'stock_actual' => 120,
                    'stock_minimo' => 50,
                    'stock_maximo' => 300,
                    'punto_reorden' => 100,
                    'almacen' => 'Galpón Forrajes - Exterior',
                    'estado' => 'normal'
                ]
            ],
            // Vitaminas y Minerales
            [
                'codigo' => 'AA-009',
                'descripcion' => 'Premix vitamínico avícola',
                'categoria_id' => $categoriaIds['Vitaminas y Minerales'],
                'nombre_comercial' => 'Premix Pollos de Engorde',
                'nombre_cientifico' => null,
                'comentario' => 'Complejo A-D-E-K + B12',
                'activo' => true,
                'disponible' => true,
                'preferido' => true,
                'inventario' => [
                    'stock_actual' => 45.25,
                    'stock_minimo' => 25,
                    'stock_maximo' => 200,
                    'punto_reorden' => 75,
                    'almacen' => 'Almacén Aditivos - Estante 5',
                    'estado' => 'critico'
                ]
            ],
            [
                'codigo' => 'AA-010',
                'descripcion' => 'Fosfato dicálcico alimenticio',
                'categoria_id' => $categoriaIds['Vitaminas y Minerales'],
                'nombre_comercial' => 'Fosfato Dical 18% P',
                'nombre_cientifico' => null,
                'comentario' => 'Fuente de fósforo y calcio',
                'activo' => true,
                'disponible' => true,
                'preferido' => true,
                'inventario' => [
                    'stock_actual' => 1500,
                    'stock_minimo' => 800,
                    'stock_maximo' => 4000,
                    'punto_reorden' => 1500,
                    'almacen' => 'Almacén Minerales - Estante 1',
                    'estado' => 'normal'
                ]
            ],
            [
                'codigo' => 'AA-011',
                'descripcion' => 'Sal yodada para ganado',
                'categoria_id' => $categoriaIds['Vitaminas y Minerales'],
                'nombre_comercial' => 'Sal Lick Ganado',
                'nombre_cientifico' => 'Sodium chloride',
                'comentario' => 'Bloques de sal para lamer',
                'activo' => true,
                'disponible' => true,
                'preferido' => false,
                'inventario' => [
                    'stock_actual' => 0,
                    'stock_minimo' => 100,
                    'stock_maximo' => 500,
                    'punto_reorden' => 200,
                    'almacen' => 'Almacén Minerales - Estante 3',
                    'estado' => 'agotado'
                ]
            ],
            // Grasas y Aceites
            [
                'codigo' => 'AA-012',
                'descripcion' => 'Aceite de soya refinado',
                'categoria_id' => $categoriaIds['Grasas y Aceites'],
                'nombre_comercial' => 'Aceite Soya Alimento',
                'nombre_cientifico' => 'Glycine max',
                'comentario' => 'Energía densa para aves y cerdos',
                'activo' => true,
                'disponible' => true,
                'preferido' => true,
                'inventario' => [
                    'stock_actual' => 4200,
                    'stock_minimo' => 2000,
                    'stock_maximo' => 10000,
                    'punto_reorden' => 4000,
                    'almacen' => 'Tanque Aceite 1',
                    'estado' => 'normal'
                ]
            ],
            [
                'codigo' => 'AA-013',
                'descripcion' => 'Sebo bovino grado alimenticio',
                'categoria_id' => $categoriaIds['Grasas y Aceites'],
                'nombre_comercial' => 'Sebo Animal Estabilizado',
                'nombre_cientifico' => null,
                'comentario' => 'Para alimento de mascotas premium',
                'activo' => true,
                'disponible' => false,
                'preferido' => false,
                'inventario' => [
                    'stock_actual' => 150,
                    'stock_minimo' => 100,
                    'stock_maximo' => 800,
                    'punto_reorden' => 300,
                    'almacen' => 'Cámara Fría Grasas',
                    'estado' => 'agotado'
                ]
            ],
            // Melaza y Endulzantes
            [
                'codigo' => 'AA-014',
                'descripcion' => 'Melaza de caña de azúcar',
                'categoria_id' => $categoriaIds['Melaza y Endulzantes'],
                'nombre_comercial' => 'Melaza Pecuario',
                'nombre_cientifico' => 'Saccharum officinarum',
                'comentario' => 'Palatabilizante y energía líquida',
                'activo' => true,
                'disponible' => true,
                'preferido' => true,
                'inventario' => [
                    'stock_actual' => 8500,
                    'stock_minimo' => 4000,
                    'stock_maximo' => 20000,
                    'punto_reorden' => 8000,
                    'almacen' => 'Tanque Melaza 1',
                    'estado' => 'normal'
                ]
            ],
            // Probióticos y Aditivos
            [
                'codigo' => 'AA-015',
                'descripcion' => 'Probiótico aviar Lactobacillus',
                'categoria_id' => $categoriaIds['Probióticos y Aditivos'],
                'nombre_comercial' => 'Probiótico Aves Live',
                'nombre_cientifico' => 'Lactobacillus acidophilus',
                'comentario' => 'Mejora digestión y salud intestinal',
                'activo' => true,
                'disponible' => true,
                'preferido' => true,
                'inventario' => [
                    'stock_actual' => 18.5,
                    'stock_minimo' => 10,
                    'stock_maximo' => 100,
                    'punto_reorden' => 30,
                    'almacen' => 'Almacén Refrigerado - Estante 2',
                    'estado' => 'critico'
                ]
            ],
            [
                'codigo' => 'AA-016',
                'descripcion' => 'Acidificante para alimento porcino',
                'categoria_id' => $categoriaIds['Probióticos y Aditivos'],
                'nombre_comercial' => 'Acidificante Porcino',
                'nombre_cientifico' => null,
                'comentario' => 'Ácido fórmico y cítrico combinado',
                'activo' => true,
                'disponible' => true,
                'preferido' => false,
                'inventario' => [
                    'stock_actual' => 225,
                    'stock_minimo' => 100,
                    'stock_maximo' => 800,
                    'punto_reorden' => 300,
                    'almacen' => 'Almacén Químicos - Estante 4',
                    'estado' => 'normal'
                ]
            ],
            // Embalaje Alimento
            [
                'codigo' => 'AA-017',
                'descripcion' => 'Sacos de polipropileno 50 kg',
                'categoria_id' => $categoriaIds['Embalaje Alimento'],
                'nombre_comercial' => 'Sacos Alimento Blanco',
                'nombre_cientifico' => null,
                'comentario' => 'Con válvula para llenado rápido',
                'activo' => true,
                'disponible' => true,
                'preferido' => true,
                'inventario' => [
                    'stock_actual' => 4200,
                    'stock_minimo' => 2000,
                    'stock_maximo' => 10000,
                    'punto_reorden' => 4000,
                    'almacen' => 'Galpón Embalaje - Pallet 1',
                    'estado' => 'normal'
                ]
            ],
            // Ingredientes Especiales
            [
                'codigo' => 'AA-018',
                'descripcion' => 'Conchilla molida para aves',
                'categoria_id' => $categoriaIds['Ingredientes Especiales'],
                'nombre_comercial' => 'Conchilla Calcio Aves',
                'nombre_cientifico' => null,
                'comentario' => 'Fuente de calcio para cáscara huevo',
                'activo' => true,
                'disponible' => true,
                'preferido' => false,
                'inventario' => [
                    'stock_actual' => 1800,
                    'stock_minimo' => 800,
                    'stock_maximo' => 4000,
                    'punto_reorden' => 1500,
                    'almacen' => 'Almacén Minerales - Exterior',
                    'estado' => 'normal'
                ]
            ],
            [
                'codigo' => 'AA-019',
                'descripcion' => 'Harina de sangre bovina',
                'categoria_id' => $categoriaIds['Ingredientes Especiales'],
                'nombre_comercial' => 'Harina Sangre 80%',
                'nombre_cientifico' => null,
                'comentario' => 'Alto contenido proteico, para acuicultura',
                'activo' => true,
                'disponible' => true,
                'preferido' => false,
                'inventario' => [
                    'stock_actual' => 0,
                    'stock_minimo' => 500,
                    'stock_maximo' => 2500,
                    'punto_reorden' => 1000,
                    'almacen' => 'Almacén Proteínas - Estante 4',
                    'estado' => 'agotado'
                ]
            ],
            // Conservantes
            [
                'codigo' => 'AA-020',
                'descripcion' => 'Ácido propiónico conservante',
                'categoria_id' => $categoriaIds['Conservantes'],
                'nombre_comercial' => 'Propionato Alimento',
                'nombre_cientifico' => null,
                'comentario' => 'Antimicótico para ensilaje y alimento',
                'activo' => true,
                'disponible' => true,
                'preferido' => true,
                'inventario' => [
                    'stock_actual' => 850,
                    'stock_minimo' => 400,
                    'stock_maximo' => 2000,
                    'punto_reorden' => 800,
                    'almacen' => 'Almacén Químicos - Estante 1',
                    'estado' => 'normal'
                ]
            ],
        ];

        // Crear materias primas e inventario
        foreach ($materiasPrimas as $materiaData) {
            // Extraer datos del inventario
            $inventarioData = $materiaData['inventario'];
            unset($materiaData['inventario']);

            // Añadir fechas
            $materiaData['fecha_creacion'] = now();
            $materiaData['fecha_modificacion'] = now();

            // Crear materia prima
            $materiaPrima = MateriaPrima::create($materiaData);

            // Crear inventario
            InventarioMateria::create([
                'materia_prima_id' => $materiaPrima->id,
                'stock_actual' => $inventarioData['stock_actual'],
                'stock_minimo' => $inventarioData['stock_minimo'],
                'stock_maximo' => $inventarioData['stock_maximo'],
                'punto_reorden' => $inventarioData['punto_reorden'],
                'almacen' => $inventarioData['almacen'],
                'estado' => $inventarioData['estado'],
                'fecha_ultima_verificacion' => now()
            ]);
        }

        $this->command->info('✓ 10 categorías de alimento animal creadas');
        $this->command->info('✓ 20 materias primas agropecuarias creadas con inventario');
        $this->command->info('✓ Estados de inventario: Normal, Crítico y Agotado configurados');
    }
}