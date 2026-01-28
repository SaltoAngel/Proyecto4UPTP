<?php
// app/Models/MovimientoInventario.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MovimientoInventario extends Model
{
    protected $table = 'movimientos_inventario';
    
    protected $fillable = [
        'materia_prima_id',
        'proveedor_id',
        'tipo_movimiento', // 'entrada' o 'salida'
        'cantidad',
        'costo_unitario',
        'costo_total',
        'numero_factura',
        'numero_remision',
        'lote',
        'numero_documento',
        'fecha_factura',
        'fecha_recepcion',
        'fecha_vencimiento',
        'observaciones',
        'usuario_id'
    ];
    
    protected $casts = [
        'cantidad' => 'decimal:2',
        'costo_unitario' => 'decimal:2',
        'costo_total' => 'decimal:2',
        'fecha_factura' => 'date',
        'fecha_recepcion' => 'date',
        'fecha_vencimiento' => 'date'
    ];
    
    // Relaciones
    public function materia(): BelongsTo
    {
        return $this->belongsTo(MateriaPrima::class, 'materia_prima_id');
    }
    
    public function proveedor(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }
    
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
    
    // Scopes
    public function scopeEntradas($query)
    {
        return $query->where('tipo_movimiento', 'entrada');
    }
    
    public function scopeSalidas($query)
    {
        return $query->where('tipo_movimiento', 'salida');
    }
    
    public function scopePorMateria($query, $materiaId)
    {
        return $query->where('materia_prima_id', $materiaId);
    }
}