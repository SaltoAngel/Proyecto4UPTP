<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProveedoresRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $proveedorId = $this->route('proveedore')?->id ?? $this->route('proveedor')?->id ?? null;

        return [
            // Requeridos por el formulario actual
            'persona_id' => ['required', 'exists:personas,id'],
            'productos_servicios' => ['nullable', 'array'],
            'productos_servicios.*' => ['string', 'max:255'],
            'tipos_proveedores' => ['nullable', 'array'],
            'tipos_proveedores.*' => ['integer', 'exists:tipos_proveedores,id'],

            // Opcionales (no presentes en el modal actual, pero soportados por el modelo)
            'codigo_proveedor' => ['nullable', 'string', 'max:255', Rule::unique('proveedores', 'codigo_proveedor')->ignore($proveedorId)],
            'categoria' => ['nullable', 'string', 'max:255'],
            'especializacion' => ['nullable', 'string'],
            'contacto_comercial' => ['nullable', 'string', 'max:255'],
            'telefono_comercial' => ['nullable', 'string', 'max:20'],
            'email_comercial' => ['nullable', 'email', Rule::unique('proveedores', 'email_comercial')->ignore($proveedorId)],
            'calificacion' => ['nullable', 'integer', 'min:1', 'max:5'],
            'observaciones_calificacion' => ['nullable', 'string'],
            'fecha_ultima_evaluacion' => ['nullable', 'date'],
            'estado' => ['nullable', Rule::in(['activo','inactivo','suspendido','en_revision','bloqueado'])],
            'fecha_registro' => ['nullable', 'date'],
            'fecha_ultima_compra' => ['nullable', 'date'],
            'monto_total_compras' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'persona_id.required' => 'Debe seleccionar la persona asociada.',
            'persona_id.exists' => 'La persona seleccionada no existe.',
            'codigo_proveedor.unique' => 'El código de proveedor ya está registrado.',
            'email_comercial.email' => 'El correo comercial debe ser válido.',
            'email_comercial.unique' => 'El correo comercial ya está registrado.',
            'estado.in' => 'Estado inválido.',
            'tipos_proveedores.*.exists' => 'Alguna categoría seleccionada no existe.',
        ];
    }
}
