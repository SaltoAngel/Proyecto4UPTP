<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PersonasRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        $personaId = $this->route('persona')?->id ?? null;

        return [
            'tipo' => ['required', Rule::in(['natural', 'juridica'])],
            'nombres' => ['required_if:tipo,natural', 'nullable', 'string', 'max:255'],
            'apellidos' => ['required_if:tipo,natural', 'nullable', 'string', 'max:255'],
            'razon_social' => ['required_if:tipo,juridica', 'nullable', 'string', 'max:255'],
            'nombre_comercial' => ['nullable', 'string', 'max:255'],
            'tipo_documento' => ['required', 'string', 'max:10'],
            'documento' => [
                'required',
                'string',
                'max:20',
                Rule::unique('personas', 'documento')->ignore($personaId),
            ],
            'direccion' => ['nullable', 'string', 'max:500'],
            'estado' => ['nullable', 'string', 'max:255'],
            'ciudad' => ['nullable', 'string', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'telefono_alternativo' => ['nullable', 'string', 'max:20'],
            'email' => [
                'nullable',
                'email',
                Rule::unique('personas', 'email')->ignore($personaId),
            ],
            'activo' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * Mensajes de validación en español.
     */
    public function messages(): array
    {
        return [
            'tipo.required' => 'El tipo de persona es obligatorio.',
            'tipo.in' => 'El tipo de persona debe ser natural o jurídica.',

            'nombres.required_if' => 'Los nombres son obligatorios para personas naturales.',
            'apellidos.required_if' => 'Los apellidos son obligatorios para personas naturales.',
            'razon_social.required_if' => 'La razón social es obligatoria para personas jurídicas.',

            'tipo_documento.required' => 'El tipo de documento es obligatorio.',
            'tipo_documento.max' => 'El tipo de documento no debe exceder 10 caracteres.',

            'documento.required' => 'El número de documento es obligatorio.',
            'documento.max' => 'El número de documento no debe exceder 20 caracteres.',
            'documento.unique' => 'El número de documento ya está registrado.',

            'direccion.max' => 'La dirección no debe exceder 500 caracteres.',
            'estado.max' => 'El estado no debe exceder 255 caracteres.',
            'ciudad.max' => 'La ciudad no debe exceder 255 caracteres.',

            'telefono.max' => 'El teléfono no debe exceder 20 caracteres.',
            'telefono_alternativo.max' => 'El teléfono alternativo no debe exceder 20 caracteres.',

            'email.email' => 'El correo electrónico debe tener un formato válido.',
            'email.unique' => 'El correo electrónico ya está registrado.',

            'activo.boolean' => 'El campo activo debe ser verdadero o falso.',
        ];
    }
}
