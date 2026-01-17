<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Role;

class RolePermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        $roleId = $this->route('role');

        // Si es 0 (nuevo), se permite si tiene permiso de editar roles
        if ($roleId == 0) {
            return auth()->check() && auth()->user()->can('edit roles');
        }

        // Si existe, buscamos el modelo para verificar si está protegido
        $role = Role::withTrashed()->find($roleId);
        
        return auth()->check() && 
               auth()->user()->can('edit roles') && 
               ($role && !$role->isProtectedRole());
    }

    public function rules(): array
    {
        return [
            'permissions' => 'required|array|min:1',
            'permissions.*' => 'required|integer|exists:permissions,id'
        ];
    }
}