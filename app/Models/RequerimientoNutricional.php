<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Mineral;
use App\Models\Vitamina;

class RequerimientoNutricional extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'requerimientos_nutricionales';

    protected $fillable = [
        'tipo_animal_id',
        'descripcion',
        'fuente',
        'comentario',
        'consumo_esperado_kg_dia',
        'preferido',
        'humedad',
        'materia_seca',
        'proteina_cruda',
        'fibra_bruta',
        'extracto_etereo',
        'eln',
        'ceniza',
        'energia_digestible',
        'energia_metabolizable',
        'energia_neta',
        'energia_digestible_ap',
        'energia_metabolizable_ap',
        'activo',
    ];

    protected $casts = [
        'preferido' => 'boolean',
        'activo' => 'boolean',
    ];

    public function tipoAnimal()
    {
        return $this->belongsTo(TipoAnimal::class, 'tipo_animal_id');
    }

    public function aminoacidos()
    {
        return $this->belongsToMany(Aminoacido::class, 'requerimiento_aminoacido', 'requerimiento_id', 'aminoacido_id')
            ->withPivot(['valor_min', 'valor_max', 'valor_recomendado', 'unidad'])
            ->withTimestamps();
    }

    public function minerales()
    {
        return $this->belongsToMany(Mineral::class, 'requerimiento_mineral', 'requerimiento_id', 'mineral_id')
            ->withPivot(['valor_min', 'valor_max', 'valor_recomendado', 'unidad'])
            ->withTimestamps();
    }

    public function vitaminas()
    {
        return $this->belongsToMany(Vitamina::class, 'requerimiento_vitamina', 'requerimiento_id', 'vitamina_id')
            ->withPivot(['valor_min', 'valor_max', 'valor_recomendado', 'unidad'])
            ->withTimestamps();
    }
}
