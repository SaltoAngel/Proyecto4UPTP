<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aminoacido extends Model
{
    use HasFactory;

    protected $table = 'aminoacidos';

    protected $fillable = [
        'nombre',
        'abreviatura',
        'tipo',
        'funcion',
        'esencial',
        'orden',
    ];

    protected $casts = [
        'esencial' => 'boolean',
        'orden' => 'integer',
    ];
}
