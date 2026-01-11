<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class SpatieRolesPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ========== CREAR PERMISOS ==========
        $permissions = [
            // Dashboard
            'view dashboard',
            
            // Proveedores
            'view proveedores',
            'create proveedores',
            'edit proveedores',
            'delete proveedores',
            
            // Materia Prima
            'view materia_prima',
            'create materia_prima',
            'edit materia_prima',
            'delete materia_prima',
            
            // Repuestos
            'view repuestos',
            'create repuestos',
            'edit repuestos',
            'delete repuestos',
            
            // Usuarios
            'view users',
            'create users',
            'edit users',
            'delete users',
            'assign roles',
            
            // Reportes
            'view reports',
            'generate reports',

            // Permisos para gestión de roles
            'view roles',
            'create roles',
            'edit roles',
            'delete roles',

            // Configuración
            'manage settings',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        // ========== CREAR ROLES ==========
        
        // 1. SUPERADMIN - Acceso total al sistema
        $superAdmin = Role::firstOrCreate([
            'name' => 'superadmin',
            'guard_name' => 'web'
        ]);
        $superAdmin->givePermissionTo(Permission::all());

        // 1. ADMIN - Acceso total al sistema
        $admin = Role::firstOrCreate([
            'name' => 'administrador',
            'guard_name' => 'web'
        ]);
        $admin->givePermissionTo(Permission::all());

        // 2. SUPERVISOR - Gestión completa de operaciones
        $supervisor = Role::firstOrCreate([
            'name' => 'supervisor',
            'guard_name' => 'web'
        ]);
        $supervisor->givePermissionTo([
            'view dashboard',
            'view proveedores', 'create proveedores', 'edit proveedores', 'delete proveedores',
            'view materia_prima', 'create materia_prima', 'edit materia_prima', 'delete materia_prima',
            'view repuestos', 'create repuestos', 'edit repuestos', 'delete repuestos',
            'view users', 'edit users',
            'view reports', 'generate reports',
        ]);

        // 3. COORDINADOR
        $coordinador = Role::firstOrCreate([
            'name' => 'coordinador',
            'guard_name' => 'web'
        ]);
        $coordinador->givePermissionTo([
            'view dashboard',
            'view proveedores', 'edit proveedores',
            'view materia_prima', 'create materia_prima', 'edit materia_prima',
            'view repuestos', 'create repuestos', 'edit repuestos',
            'view reports',
        ]);

        // 4. NUTRICIONISTA - Gestión de materia prima
        $nutricionista = Role::firstOrCreate([
            'name' => 'nutricionista',
            'guard_name' => 'web'
        ]);
        $nutricionista->givePermissionTo([
            'view dashboard',
            'view materia_prima', 'create materia_prima', 'edit materia_prima',
            'view proveedores',
        ]);
        // ========== ASIGNAR ROLES A USUARIOS ==========
        
        // Asignar superadmin al primer usuario
        $adminUser = User::where('email', 'admin@uptp.com')->first();
        if (!$adminUser) {
            $adminUser = User::first();
        }
        
        if ($adminUser) {
            $adminUser->assignRole('superadmin');
            $this->command->info("✅ Rol superadmin asignado a: {$adminUser->email}");
        }

        // Asignar administrador
        $administrador = User::whereIn('email', ['administrador@uptp.com'])->get();
        foreach ($administrador as $administradorUser) {
            $administradorUser->assignRole('administrador');
            $this->command->info("✅ Rol administrador asignado a: {$administradorUser->email}");
        }

        // Asignar supervisores
        $supervisores = User::whereIn('email', ['supervisor@uptp.com'])->get();
        foreach ($supervisores as $supervisorUser) {
            $supervisorUser->assignRole('supervisor');
            $this->command->info("✅ Rol supervisor asignado a: {$supervisorUser->email}");
        }
        
        // Asignar coordinadores
        $coordinadores = User::whereIn('email', ['coordinador@uptp.com'])->get();
        foreach ($coordinadores as $coordinadorUser) {
            $coordinadorUser->assignRole('coordinador');
            $this->command->info("✅ Rol coordinador asignado a: {$coordinadorUser->email}");
        }
        
        // Asignar nutricionistas
        $nutricionistas = User::whereIn('email', ['nutricionista@uptp.com'])->get();
        foreach ($nutricionistas as $nutricionistaUser) {
            $nutricionistaUser->assignRole('nutricionista');
            $this->command->info("✅ Rol nutricionista asignado a: {$nutricionistaUser->email}");
        }

        $this->command->info('Roles y permisos de Spatie creados correctamente.');
        $this->command->info('Resumen:');
        $this->command->info('   - ' . Role::count() . ' roles creados');
        $this->command->info('   - ' . Permission::count() . ' permisos creados');
    }
}