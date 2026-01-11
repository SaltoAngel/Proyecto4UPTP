<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    /**
     * Los atributos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'name',
        'guard_name',
    ];

    /**
     * Roles del sistema que YA TIENES y NO se pueden editar/eliminar.
     * Estos son los 5 roles que ya creó tu seeder original.
     */
    public function isProtectedRole(): bool
    {
        $protectedRoles = ['superadmin', 'supervisor', 'coordinador', 'nutricionista'];
        return in_array($this->name, $protectedRoles);
    }

    /**
     * Verificar si el rol puede ser eliminado.
     */
    public function canBeDeleted(): bool
    {
        return !$this->isProtectedRole() && $this->users()->count() === 0;
    }

    /**
     * Obtener permisos agrupados por módulo.
     */
    public function getPermissionsByModuleAttribute()
    {
        return $this->permissions->groupBy(function ($permission) {
            $parts = explode(' ', $permission->name);
            $module = count($parts) > 1 ? $parts[1] : 'general';
            
            return $this->getModuleName($module);
        });
    }

    /**
     * Traducir nombres de módulos.
     */
    private function getModuleName($module)
    {
        $moduleNames = [
            'dashboard' => 'Dashboard',
            'proveedores' => 'Proveedores',
            'personas' => 'Personas',
            'bitacora' => 'Bitácora',
            'reportes' => 'Reportes',
            'users' => 'Usuarios',
            'roles' => 'Roles',
            'settings' => 'Configuración',
        ];

        return $moduleNames[$module] ?? ucfirst(str_replace('_', ' ', $module));
    }
}