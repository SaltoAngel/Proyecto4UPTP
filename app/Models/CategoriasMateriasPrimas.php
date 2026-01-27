<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CategoriasMateriasPrimas extends Model
{
    use HasFactory;
    
    // TABLA CORRECTA: categorias_materia_prima
    protected $table = 'categorias_materia_prima';
    
    protected $fillable = [
        'nombre',
        'codigo_nrc',
        'descripcion',
        'tipo',
        'activo',
        'orden'
    ];
    
    protected $casts = [
        'activo' => 'boolean',
    ];
    
    // Relación correcta
    public function materiasPrimas(): HasMany
    {
        return $this->hasMany(MateriaPrima::class, 'categoria_id');
    }
    
    // Scope para activos
    public function scopeActivo($query)
    {
        return $query->where('activo', true);
    }
    
    // Scope para ordenar
    public function scopeOrdenado($query)
    {
        return $query->orderBy('orden')->orderBy('nombre');
    }
}