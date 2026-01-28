<?php
// app/Models/InventarioMateria.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventarioMateria extends Model
{
    protected $table = 'inventario_materias';
    
    protected $fillable = [
        'materia_prima_id',
        'stock_actual',
        'stock_minimo',
        'stock_maximo',
        'punto_reorden',
        'almacen',
        'estado',
        'fecha_ultima_verificacion',
        'ubicacion_almacen'
    ];
    
    protected $casts = [
        'stock_actual' => 'decimal:2',
        'stock_minimo' => 'decimal:2',
        'stock_maximo' => 'decimal:2',
        'punto_reorden' => 'decimal:2',
        'fecha_ultima_verificacion' => 'datetime'
    ];
    
    // Estados posibles
    const ESTADO_NORMAL = 'normal';
    const ESTADO_CRITICO = 'critico';
    const ESTADO_AGOTADO = 'agotado';
    
    // Relación con materia prima
    public function materia(): BelongsTo
    {
        return $this->belongsTo(MateriaPrima::class, 'materia_prima_id');
    }
    
    // Método para actualizar estado
    public function actualizarEstado()
    {
        if ($this->stock_actual <= 0) {
            $this->estado = self::ESTADO_AGOTADO;
        } elseif ($this->stock_actual <= $this->punto_reorden) {
            $this->estado = self::ESTADO_CRITICO;
        } else {
            $this->estado = self::ESTADO_NORMAL;
        }
        
        $this->fecha_ultima_verificacion = now();
        $this->save();
        
        return $this;
    }
    
    // Scope para estado
    public function scopePorEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }
}