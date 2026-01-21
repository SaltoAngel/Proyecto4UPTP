<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoAnimal extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tipos_animal';

    protected $fillable = [
        'especie_id',
        'nombre',
        'codigo_etapa',
        'edad_minima_dias',
        'edad_maxima_dias',
        'peso_minimo_kg',
        'peso_maximo_kg',
        'descripcion',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'peso_minimo_kg' => 'decimal:2',
        'peso_maximo_kg' => 'decimal:2',
    ];

    public function especie()
    {
        return $this->belongsTo(Especie::class, 'especie_id');
    }

    public function requerimientos()
    {
        return $this->hasMany(RequerimientoNutricional::class, 'tipo_animal_id');
    }
}
