<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Reporte;

class ReportesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reportes = [
            [
                'nombre' => 'Reporte de Prueba',
                'template' => 'Prueba',
                'descripcion' => 'Reporte básico de prueba sin conexión a base de datos',
                'categoria' => 'Pruebas',
                'parametros' => null,
                'activo' => true,
                'requiere_db' => false
            ],
            [
                'nombre' => 'Reporte de Personas',
                'template' => 'Personas',
                'descripcion' => 'Listado completo de personas registradas en el sistema',
                'categoria' => 'Administración',
                'parametros' => [
                    'solo_activos' => 'boolean'
                ],
                'activo' => true,
                'requiere_db' => true
            ],
            // Puedes añadir más reportes aquí
            [
                'nombre' => 'Proveedores',
                'template' => 'Proveedores',
                'descripcion' => 'Listado de proveedores registrados',
                'categoria' => 'Compras',
                'parametros' => [
                    'estado' => 'string',
                    'tipo' => 'string'
                ],
                'activo' => false, // Desactivado hasta crear el JRXML
                'requiere_db' => true
            ],
            [
                'nombre' => 'Materias Primas',
                'template' => 'MateriasPrimas',
                'descripcion' => 'Inventario de materias primas',
                'categoria' => 'Inventario',
                'parametros' => [
                    'categoria' => 'string',
                    'stock_bajo' => 'boolean'
                ],
                'activo' => false, // Desactivado hasta crear el JRXML
                'requiere_db' => true
            ],
            // NUEVO REPORTE - EJEMPLO
            [
                'nombre' => 'Ventas por Mes',
                'template' => 'VentasPorMes',
                'descripcion' => 'Reporte de ventas agrupadas por mes',
                'categoria' => 'Ventas',
                'parametros' => [
                    'anio' => 'integer',
                    'mes_inicio' => 'integer',
                    'mes_fin' => 'integer'
                ],
                'activo' => true,
                'requiere_db' => true
            ]
        ];

        foreach ($reportes as $reporte) {
            Reporte::create($reporte);
        }
    }
}
