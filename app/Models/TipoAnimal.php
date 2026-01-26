<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoAnimal extends Model
{
    use SoftDeletes;
    
    protected $table = 'tipos_animal';
    
    protected $fillable = [
        'especie_id',
        'nombre',
        'raza_linea',
        'producto_final',
        'sistema_produccion',
        'etapa_especifica',
        'edad_semanas',
        'peso_minimo_kg',
        'descripcion',
        'activo'
    ];
    
    protected $casts = [
        'activo' => 'boolean',
        'edad_semanas' => 'integer',
        'peso_minimo_kg' => 'decimal:2',
    ];
    
    // Relaciones
    public function especie()
    {
        return $this->belongsTo(Especie::class);
    }
    
    public function requerimientos()
    {
        return $this->hasMany(RequerimientoNutricional::class, 'tipo_animal_id');
    }
}