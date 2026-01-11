<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RolePermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        $role = $this->route('role');
        return auth()->check() && auth()->user()->can('edit roles') && !$role->isProtectedRole();
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