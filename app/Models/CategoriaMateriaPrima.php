<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriaMateriaPrima extends Model
{
    use HasFactory;

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
        'orden' => 'integer'
    ];

    // Relación con materias primas
    public function materiasPrimas()
    {
        return $this->hasMany(MateriaPrima::class, 'categoria_id');
    }
}