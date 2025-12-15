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
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => 'required|email|unique:personas,email,' . $this->persona,
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:500',
        ];
    }
}
