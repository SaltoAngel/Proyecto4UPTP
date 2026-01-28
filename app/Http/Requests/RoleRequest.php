<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Spatie\Permission\Models\Permission;

class RoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && (auth()->user()->can('create roles') || auth()->user()->can('edit roles'));
    }

    public function rules(): array
    {
        $role = $this->route('role');
        $id = is_object($role) ? $role->id : $role;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                $id ? "unique:roles,name,{$id}" : "unique:roles,name"
            ],
            'permissions' => [
                'required', 
                'array', 
                'min:1'
            ],
            'permissions.*' => [
                'integer',
                'exists:permissions,id'
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del rol es obligatorio.',
            'name.unique' => 'Este nombre de rol ya está registrado.',
            'permissions.required' => 'Debe asignar al menos un permiso para poder crear el rol.',
            'permissions.min' => 'Seleccione al menos una casilla de permiso.',
        ];
    }

    /**
     * Validación adicional para prevenir asignación de permisos de gestión de roles
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $permissions = $this->input('permissions', []);
            
            if (!empty($permissions)) {
                // Obtener los IDs de los permisos de gestión de roles
                $roleManagementPermissions = Permission::where('name', 'like', '%roles%')
                    ->pluck('id')
                    ->toArray();
                
                // Verificar si algún permiso de gestión de roles está en la lista
                $intersection = array_intersect($permissions, $roleManagementPermissions);
                
                if (!empty($intersection)) {
                    $validator->errors()->add(
                        'permissions', 
                        'No se pueden asignar permisos de gestión de roles. Estos permisos son exclusivos del administrador.'
                    );
                }
            }
        });
    }
}