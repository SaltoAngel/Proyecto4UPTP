<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TiposProveedores;

class Tipo_Proveedores extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TiposProveedores::create([
            'nombre_tipo' => 'Materia Prima',
            'descripcion' => 'Proveedores que suministran ingredientes y materias primas para la formulación de alimentos balanceados para animales',
        ]);
        
        TiposProveedores::create([
            'nombre_tipo' => 'Repuestos',
            'descripcion' => 'Proveedores de repuestos, piezas y componentes para el mantenimiento de maquinaria y equipos de producción',
        ]);
        
        TiposProveedores::create([
            'nombre_tipo' => 'Servicios',
            'descripcion' => 'Proveedores de servicios técnicos, mantenimiento, transporte, análisis de laboratorio y otros servicios especializados',
        ]);
    }
}
