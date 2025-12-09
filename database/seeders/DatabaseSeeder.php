<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\UsuariosSeedeer;
use Database\Seeders\RolesSeeder;
use Database\Seeders\Tipo_Proveedores;
use Database\Seeders\Categorias_Materias;
use Database\Seeders\SpatieRolesPermissionsSeeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UsuariosSeedeer::Class,
            RolesSeeder::class,
            Tipo_Proveedores::class,
            Categorias_Materias::class,
            SpatieRolesPermissionsSeeder::class,
        ]);
    }
}
