<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
        return [
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'tipo_documento' => 'required|string|max:2',
            'documento' => 'required|string|max:20|unique:personas,documento',
            'email' => 'required|email|unique:personas,email',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:500',
        ];
    }
}
