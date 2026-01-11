<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use il

class Role extends SpatieRole
{
    /**
     * Roles del sistema que NO se pueden editar/eliminar.
     * superadmin, supervisor, coordinador, nutricionista
     */
    public function isProtectedRole(): bool
    {
        $protectedRoles = ['superadmin', 'administrador', 'supervisor', 'coordinador', 'nutricionista'];
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
     * Obtener permisos agrupados por módulo (para la modal).
     */
    public function getPermissionsByModule()
    {
        return $this->permissions->groupBy(function ($permission) {
            $parts = explode(' ', $permission->name);
            $module = count($parts) > 1 ? $parts[1] : 'general';
            
            return $this->getModuleDisplayName($module);
        });
    }

    /**
     * Traducir nombres de módulos para mostrar.
     */
    private function getModuleDisplayName($module)
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
            'materia_prima' => 'Materia Prima',
            'repuestos' => 'Repuestos',
        ];

        return $moduleNames[$module] ?? ucfirst(str_replace('_', ' ', $module));
    }
}