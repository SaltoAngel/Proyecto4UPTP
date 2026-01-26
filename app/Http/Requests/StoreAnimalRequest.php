<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAnimalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Campos básicos
            'especie_id' => 'required|exists:especies,id',
            'nombre' => 'required|string|max:100',
            
            // Campos nuevos según tu modal
            'raza_linea' => 'nullable|string|max:100',
            'producto_final' => 'nullable|string|in:leche,carne,huevos,doble_proposito,reproduccion,trabajo,lana,miel,otro',
            'sistema_produccion' => 'nullable|string|in:intensivo,semi-intensivo,extensivo,organico,otro',
            'etapa_especifica' => 'nullable|string|max:200',
            'edad_semanas' => 'nullable|integer|min:0',
            'peso_minimo_kg' => 'nullable|numeric|min:0',
            'descripcion' => 'nullable|string',
            'activo' => 'nullable|boolean',
            
            // Consumo esperado
            'requerimiento.consumo_esperado_kg_dia' => 'nullable|numeric|min:0',
            'requerimiento.fuente' => 'nullable|string|max:150',
            'requerimiento.descripcion' => 'nullable|string|max:150',
            
            // Tolerancia a alimentos
            'tolerancia_alimentos' => 'nullable|array',
            'tolerancia_alimentos.*.porcentaje_maximo' => 'nullable|numeric|min:0|max:100',
            
            // Requerimientos nutricionales
            'requerimientos_diarios' => 'nullable|array',
            'requerimientos_diarios.weende.*' => 'nullable|numeric|min:0',
            'requerimientos_diarios.macrominerales_porcentaje.*' => 'nullable|numeric|min:0|max:100',
            'requerimientos_diarios.microminerales.*' => 'nullable|numeric|min:0',
            'requerimientos_diarios.energia.*' => 'nullable|numeric|min:0',
            
            // Nutrientes específicos
            'aminoacidos' => 'nullable|array',
            'aminoacidos.*.valor' => 'nullable|numeric|min:0|max:100',
            'minerales' => 'nullable|array',
            'minerales.*.valor' => 'nullable|numeric|min:0',
            'vitaminas' => 'nullable|array',
            'vitaminas.*.valor' => 'nullable|numeric|min:0',
        ];
    }
    
    public function messages(): array
    {
        return [
            'especie_id.required' => 'La especie es obligatoria',
            'nombre.required' => 'El nombre del tipo de animal es obligatorio',
            'producto_final.in' => 'El producto final seleccionado no es válido',
            'sistema_produccion.in' => 'El sistema de producción seleccionado no es válido',
        ];
    }
}