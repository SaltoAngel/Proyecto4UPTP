<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAnimalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'especie_id' => ['required', 'integer', 'exists:especies,id'],
            'nombre' => [
                'required', 'string', 'max:100',
                Rule::unique('tipos_animal', 'nombre')->where(fn($q) => $q->where('especie_id', $this->input('especie_id')))
            ],
            'codigo_etapa' => ['nullable', 'string', 'max:20'],
            'edad_minima_dias' => ['nullable', 'integer', 'min:0'],
            'edad_maxima_dias' => ['nullable', 'integer', 'min:0'],
            'peso_minimo_kg' => ['nullable', 'numeric', 'min:0'],
            'peso_maximo_kg' => ['nullable', 'numeric', 'min:0'],
            'descripcion' => ['nullable', 'string'],
            'activo' => ['sometimes', 'boolean'],

            'requerimiento.descripcion' => ['nullable', 'string', 'max:150'],
            'requerimiento.fuente' => ['nullable', 'string', 'max:50'],
            'requerimiento.comentario' => ['nullable', 'string'],
            'requerimiento.consumo_esperado_kg_dia' => ['nullable', 'numeric', 'min:0'],
            'requerimiento.preferido' => ['sometimes', 'boolean'],

            'requerimiento.humedad' => ['nullable', 'numeric', 'between:0,100'],
            'requerimiento.materia_seca' => ['nullable', 'numeric', 'between:0,100'],
            'requerimiento.proteina_cruda' => ['nullable', 'numeric', 'between:0,100'],
            'requerimiento.fibra_bruta' => ['nullable', 'numeric', 'between:0,100'],
            'requerimiento.extracto_etereo' => ['nullable', 'numeric', 'between:0,100'],
            'requerimiento.eln' => ['nullable', 'numeric', 'between:0,100'],
            'requerimiento.ceniza' => ['nullable', 'numeric', 'between:0,100'],

            'requerimiento.energia_digestible' => ['nullable', 'numeric', 'min:0'],
            'requerimiento.energia_metabolizable' => ['nullable', 'numeric', 'min:0'],
            'requerimiento.energia_neta' => ['nullable', 'numeric', 'min:0'],
            'requerimiento.energia_digestible_ap' => ['nullable', 'numeric', 'min:0'],
            'requerimiento.energia_metabolizable_ap' => ['nullable', 'numeric', 'min:0'],
            'requerimiento.activo' => ['sometimes', 'boolean'],

            'aminoacidos' => ['nullable', 'array'],
            'aminoacidos.*.valor_min' => ['nullable', 'numeric', 'between:0,100'],
            'aminoacidos.*.valor_max' => ['nullable', 'numeric', 'between:0,100'],
            'aminoacidos.*.valor_recomendado' => ['nullable', 'numeric', 'between:0,100'],
            'aminoacidos.*.unidad' => ['nullable', 'string', 'max:20'],

            'minerales' => ['nullable', 'array'],
            'minerales.*.valor_min' => ['nullable', 'numeric', 'min:0'],
            'minerales.*.valor_max' => ['nullable', 'numeric', 'min:0'],
            'minerales.*.valor_recomendado' => ['nullable', 'numeric', 'min:0'],
            'minerales.*.unidad' => ['nullable', 'string', 'max:20'],

            'vitaminas' => ['nullable', 'array'],
            'vitaminas.*.valor_min' => ['nullable', 'numeric', 'min:0'],
            'vitaminas.*.valor_max' => ['nullable', 'numeric', 'min:0'],
            'vitaminas.*.valor_recomendado' => ['nullable', 'numeric', 'min:0'],
            'vitaminas.*.unidad' => ['nullable', 'string', 'max:20'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($v) {
            $minEdad = $this->input('edad_minima_dias');
            $maxEdad = $this->input('edad_maxima_dias');
            if ($minEdad !== null && $maxEdad !== null && $minEdad > $maxEdad) {
                $v->errors()->add('edad_maxima_dias', 'La edad máxima debe ser mayor o igual a la mínima.');
            }
            $minPeso = $this->input('peso_minimo_kg');
            $maxPeso = $this->input('peso_maximo_kg');
            if ($minPeso !== null && $maxPeso !== null && $minPeso > $maxPeso) {
                $v->errors()->add('peso_maximo_kg', 'El peso máximo debe ser mayor o igual al mínimo.');
            }
        });
    }
}
