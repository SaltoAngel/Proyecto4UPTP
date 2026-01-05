<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\UsuariosSeeder;
use Database\Seeders\Tipo_Proveedores;
use Database\Seeders\Categorias_Materias;
use Database\Seeders\SpatieRolesPermissionsSeeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            SpatieRolesPermissionsSeeder::class,
            UsuariosSeeder::class,
            Tipo_Proveedores::class,
            PersonasClientesProveedoresSeeder::class,
            ReportesSeeder::class,
        ]);
    }
}
