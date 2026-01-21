<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Role;

class RolePermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        $roleId = $this->route('role');
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
            'permissions.min' => 'Debe marcar al menos una casilla de permiso.',
        ];
    }
}