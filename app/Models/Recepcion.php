<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Modelo: Recepcion
 * Propósito: Representa recepciones de materiales/insumos.
 * Campos sugeridos (ajustar en migración real): proveedor_id, fecha, codigo, estado, observaciones.
 */
class Recepcion extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'recepciones';

    protected $fillable = [
        'proveedor_id',
        'codigo',
        'fecha',
        'estado',
        'observaciones',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];
}
