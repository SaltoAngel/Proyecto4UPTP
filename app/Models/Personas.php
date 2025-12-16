<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personas extends Model
{
    use HasFactory;

    // Tabla personalizada (migración crea 'persona')
    protected $table = 'personas';

    /**
     * Atributos asignables.
     */
    protected $fillable = [
        'codigo',
        'nombres',
        'apellidos',
        'razon_social',
        'es_juridico',
        'nombre_comercial',
        'tipo_documento',
        'documento',
        'direccion',
        'estado',
        'telefono',
        'email',
    ];

    /**
     * Casts.
     */
    protected $casts = [
        'es_juridico' => 'boolean',
    ];

    /**
     * Relación: una persona puede tener un usuario (o ninguno).
     */
    public function user()
    {
        return $this->hasOne(User::class, 'persona_id');
    }

        public function getNombreCompletoAttribute()
    {
        if ($this->tipo === 'juridica') {
            // Para persona jurídica
            if (!empty($this->razon_social)) {
                return $this->razon_social;
            }
            if (!empty($this->nombre_comercial)) {
                return $this->nombre_comercial;
            }
            return 'Empresa sin nombre';
        }
        
        // Para persona natural
        $nombreCompleto = trim($this->nombres . ' ' . $this->apellidos);
        
        return !empty($nombreCompleto) ? $nombreCompleto : 'Persona sin nombre';
    }
}
