<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Roles;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*Insertar datos a la tabla roles */
        Roles::create([
            'nombre_rol' => 'ADMINISTRADOR',
            'descripcion_rol' => 'Encargado de administrar el sistema y gestionar usuarios.']);
        Roles::create([
            'nombre_rol' => 'NUTRICIONISTA',
            'descripcion_rol' => 'Encargado de la formulación de mezclas y gestión de invetario de alimentos.']);
        Roles::create([
            'nombre_rol' => 'SUPERVISOR',
            'descripcion_rol' => 'Encargado de supervisar y validar cada uno de los procedimientos del sistema.']);
        Roles::create([
            'nombre_rol' => 'COORDINADOR',
            'descripcion_rol' => 'Encargado de coordinar operacion y toma de decisiones estratégicas.']);
    }
}
