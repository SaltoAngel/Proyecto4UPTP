<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Especie extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'especies';

    protected $fillable = [
        'nombre',
        'nombre_cientifico',
        'codigo',
        'descripcion',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function tiposAnimal()
    {
        return $this->hasMany(TipoAnimal::class, 'especie_id');
    }
}
