<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;


class UsuariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $usuarios = [
            ['name' => 'Daniel Perez', 'email' => 'danielelpro19@gmail.com', 'role' => 'superadmin'],
            ['name' => 'Mariexi Oviedo', 'email' => 'mariexioviedo@gmail.com', 'role' => 'superadmin'],
            ['name' => 'Elyanni Túa', 'email' => 'elyannitua@gmail.com', 'role' => 'superadmin'],
            ['name' => 'Gabriela Rivero', 'email' => 'grivero115@gmail.com', 'role' => 'superadmin'],
            ['name' => 'Maria Falcon', 'email' => 'mfrosendo07@gmail.com', 'role' => 'superadmin'],
        ];

        $codigoBase = 1000;
        foreach ($usuarios as $i => $u) {
            // Separar nombre en nombres/apellidos
            $parts = explode(' ', $u['name'], 2);
            $nombres = $parts[0] ?? $u['name'];
            $apellidos = $parts[1] ?? '';

            // Crear persona mínima requerida (si no existe)
            $existingPersona = DB::table('persona')->where('email', $u['email'])->first();
            if ($existingPersona) {
                $personaId = $existingPersona->id;
            } else {
                $personaId = DB::table('persona')->insertGetId([
                    'codigo' => $codigoBase + $i,
                    'nombres' => $nombres,
                    'apellidos' => $apellidos,
                    'razon_social' => $u['name'],
                    'es_juridico' => false,
                    'nombre_comercial' => null,
                    'tipo_documento' => 'CI',
                    'documento' => (string) (20000000 + $i),
                    'direccion' => 'N/A',
                    'estado' => 'activo',
                    'telefono' => '000000000',
                    'email' => $u['email'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Resolver role_id
            $role = Role::where('name', $u['role'])->first();
            $roleId = $role ? $role->id : null;

            // Insertar o actualizar usuario (con email copiado desde persona)
            $existingUser = DB::table('users')->where('email', $u['email'])->first();
            if ($existingUser) {
                DB::table('users')->where('id', $existingUser->id)->update([
                    'role_id' => $roleId,
                    'status' => 'activo',
                    'last_login_at' => now()->subDays(1),
                    'remember_token' => Str::random(10),
                    'updated_at' => now(),
                    'persona_id' => $personaId,
                ]);
            } else {
                DB::table('users')->insert([
                    'email' => $u['email'],
                    'email_verified_at' => now(),
                    'password' => Hash::make('12345678'),
                    'role_id' => $roleId,
                    'status' => 'activo',
                    'last_login_at' => now()->subDays(1),
                    'remember_token' => Str::random(10),
                    'created_at' => now()->subMonths(2),
                    'updated_at' => now(),
                    'persona_id' => $personaId,
                ]);
            }
        }
    }
}
