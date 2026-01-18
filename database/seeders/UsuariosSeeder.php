<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UsuariosSeeder extends Seeder
{
    public function run(): void
    {
        $usuarios = [
            [
                'name' => 'Daniel Perez', 
                'email' => 'danielelpro19@gmail.com', 
                'role' => 'superadmin',
                'status' => 'activo',
                'telefono' => '04121234567'
            ],
            [
                'name' => 'Mariexi Oviedo', 
                'email' => 'mariexioviedo@gmail.com', 
                'role' => 'superadmin',
                'status' => 'activo',
                'telefono' => '04121234568'
            ],
            [
                'name' => 'Elyanni Túa', 
                'email' => 'elyannitua@gmail.com', 
                'role' => 'superadmin',
                'status' => 'activo',
                'telefono' => '04121234569'
            ],
            [
                'name' => 'Gabriela Rivero', 
                'email' => 'grivero115@gmail.com', 
                'role' => 'superadmin',
                'status' => 'activo',
                'telefono' => '04121234570'
            ],
            [
                'name' => 'Maria Falcon', 
                'email' => 'mfrosendo07@gmail.com', 
                'role' => 'superadmin',
                'status' => 'activo',
                'telefono' => '04121234571'
            ],
        ];

        $codigoBase = 1000;
        
        foreach ($usuarios as $i => $u) {
            // Separar nombre en nombres/apellidos
            $parts = explode(' ', $u['name'], 2);
            $nombres = $parts[0] ?? $u['name'];
            $apellidos = $parts[1] ?? '';
            
            // Generar documento único
            $documento = (20000000 + $i);
            $documentoFormateado = number_format($documento, 0, '', '.');
            
            // Crear persona con la NUEVA estructura
            $personaData = [
                'codigo' => 'PER-' . str_pad($codigoBase + $i, 6, '0', STR_PAD_LEFT),
                'tipo' => 'natural',
                'nombres' => $nombres,
                'apellidos' => $apellidos,
                'razon_social' => null, // Para persona natural, puede ser null
                'nombre_comercial' => null,
                'tipo_documento' => 'V',
                'documento' => $documentoFormateado,
                'estado' => 'Portuguesa',
                'municipio' => 'Araure',
                'parroquia' => 'Araure',
                'tipo_via' => 'avenida',
                'nombre_via' => 'Principal',
                'numero_piso_apto' => 'Casa ' . ($i + 1),
                'urbanizacion_sector' => 'Centro Araure',
                'referencia' => 'Cerca de la plaza Bolívar',
                'direccion' => 'Dirección no especificada',
                'ciudad' => 'Araure',
                'telefono' => $u['telefono'],
                'telefono_alternativo' => null,
                'email' => $u['email'],
                'activo' => true,
                'created_at' => now()->subMonths(2),
                'updated_at' => now(),
            ];
            
            // Buscar si la persona ya existe por email o documento
            $existingPersona = DB::table('personas')
                ->where('email', $u['email'])
                ->orWhere('documento', $documento)
                ->first();
            
            if ($existingPersona) {
                $personaId = $existingPersona->id;
                // Actualizar persona existente
                DB::table('personas')
                    ->where('id', $personaId)
                    ->update($personaData);
            } else {
                $personaId = DB::table('personas')->insertGetId($personaData);
            }
            
            // Resolver role_id - Asegurar que exista el rol
            $role = Role::where('name', $u['role'])->first();
            
            if (!$role) {
                // Crear rol si no existe
                $role = Role::create([
                    'name' => $u['role'],
                    'guard_name' => 'web'
                ]);
            }
            
            // Verificar si el usuario ya existe
            $existingUser = DB::table('users')->where('email', $u['email'])->first();
            
            if ($existingUser) {
                // Actualizar usuario existente
                DB::table('users')->where('id', $existingUser->id)->update([
                    'email' => $u['email'],
                    'email_verified_at' => now(),
                    'password' => Hash::make('12345678'),
                    'persona_id' => $personaId,
                    'status' => 'activo',
                    'last_login_at' => now()->subDays(rand(1, 30)),
                    'remember_token' => Str::random(10),
                    'updated_at' => now(),
                ]);
                
                $this->command->info("Usuario actualizado: {$u['email']}");
            } else {
                // Crear nuevo usuario
                DB::table('users')->insert([
                    'email' => $u['email'],
                    'email_verified_at' => now(),
                    'password' => Hash::make('12345678'),
                    'persona_id' => $personaId,
                    'status' => 'activo',
                    'last_login_at' => now()->subDays(rand(1, 30)),
                    'remember_token' => Str::random(10),
                    'created_at' => now()->subMonths(2),
                    'updated_at' => now(),
                ]);
            
            }
            
            // Asignar rol con Spatie Permission
            $userModel = \App\Models\User::where('email', $u['email'])->first();
            if ($userModel && !$userModel->hasRole($u['role'])) {
                $userModel->assignRole($u['role']);
            }
        }
        

    }
}