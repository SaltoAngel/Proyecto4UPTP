<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Role extends SpatieRole
{
    use SoftDeletes; //

    public function isProtectedRole(): bool
    {
        // Requerimiento: Solo estos dos son protegidos
        return in_array($this->name, ['superadmin', 'administrador']);
    }

    public function canBeDisabled(): bool
    {
        return !$this->isProtectedRole() && $this->users()->count() === 0;
    }

    public function getPermissionsByModule()
    {
        return $this->permissions->groupBy(function ($permission) {
            $parts = explode(' ', $permission->name);
            $module = count($parts) > 1 ? $parts[1] : 'general';
            return $this->getModuleDisplayName($module);
        });
    }

    private function getModuleDisplayName($module)
    {
        $moduleNames = [
            'dashboard' => 'Dashboard', 'proveedores' => 'Proveedores',
            'personas' => 'Personas', 'bitacora' => 'Bitácora',
            'reportes' => 'Reportes', 'users' => 'Usuarios',
            'roles' => 'Roles', 'settings' => 'Configuración',
            'materia_prima' => 'Materia Prima', 'repuestos' => 'Repuestos',
        ];
        return $moduleNames[$module] ?? ucfirst(str_replace('_', ' ', $module));
    }
}