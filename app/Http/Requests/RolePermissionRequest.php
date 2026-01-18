<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Role; // Importante importar el modelo

class RolePermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Obtenemos el ID de la ruta
        $roleId = $this->route('role');

        // Si el ID es 0, es un rol nuevo (no está protegido aún)
        if ($roleId == 0) {
            return auth()->check() && auth()->user()->can('edit roles');
        }

        // Si no es 0, buscamos el modelo para usar isProtectedRole()
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

    public function messages(): array
    {
        return [
            'permissions.required' => 'Seleccione al menos un permiso.',
            'permissions.min' => 'Seleccione al menos un permiso.',
        ];
    }
}