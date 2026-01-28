<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MateriaPrima extends Model
{
    use SoftDeletes;

    protected $table = 'materias_primas';
    
    protected $fillable = [
        'categoria_id',
        'descripcion',
        'codigo',
        'nombre_comercial',
        'nombre_cientifico',
        'comentario',
        'preferido',
        'activo',
        'disponible',
        'fecha_creacion',
        'fecha_modificacion',
        'fecha_ultima_compra'
    ];
    
    protected $casts = [
        'preferido' => 'boolean',
        'activo' => 'boolean',
        'disponible' => 'boolean',
        'fecha_creacion' => 'date',
        'fecha_modificacion' => 'date',
        'fecha_ultima_compra' => 'date'
    ];
    
    // Relaciones
    public function categoria(): BelongsTo
    {
        return $this->belongsTo(CategoriasMateriasPrimas::class, 'categoria_id');
    }
    
    public function inventario(): HasOne
    {
        return $this->hasOne(InventarioMateria::class, 'materia_prima_id');
    }
    
    public function movimientos(): HasMany
    {
        return $this->hasMany(MovimientoInventario::class, 'materia_prima_id');
    }
    
    // Accessors (corregidos)
    public function getStockActualAttribute()
    {
        return $this->inventario ? $this->inventario->stock_actual : 0;
    }
    
    public function getEstadoInventarioAttribute()
    {
        if (!$this->inventario) return 'sin_inventario';
        return $this->inventario->estado;
    }
    
    // Scopes (corregidos)
    public function scopeActivas($query)
    {
        return $query->where('activo', true);
    }
    
    public function scopeDisponibles($query)
    {
        return $query->where('disponible', true);
    }
    
    public function scopeCriticas($query)
    {
        return $query->whereHas('inventario', function($q) {
            $q->where('estado', 'critico');
        });
    }
    
    public function scopeAgotadas($query)
    {
        return $query->whereHas('inventario', function($q) {
            $q->where('estado', 'agotado');
        });
    }
}