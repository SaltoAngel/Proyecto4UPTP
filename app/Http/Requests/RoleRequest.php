<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            // NUEVA REGLA: Obliga a que lleguen permisos en la misma petición
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
            // Mensajes para la restricción de seguridad
            'permissions.required' => 'Debe asignar al menos un permiso para poder crear el rol.',
            'permissions.min' => 'Seleccione al menos una casilla de permiso.',
        ];
    }
}