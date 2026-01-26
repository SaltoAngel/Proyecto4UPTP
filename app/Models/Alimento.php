<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alimento extends Model
{
    protected $table = 'alimentos';
    
    protected $fillable = [
        'tipo_animal_id',
        'tipo',
        'cantidad_maxima'
    ];
    
    protected $casts = [
        'cantidad_maxima' => 'decimal:2'
    ];
    
    public function tipoAnimal()
    {
        return $this->belongsTo(TipoAnimal::class, 'tipo_animal_id');
    }
}