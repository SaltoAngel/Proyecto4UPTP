<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vitamina extends Model
{
    use HasFactory;

    protected $table = 'vitaminas';

    protected $fillable = [
        'nombre',
        'tipo',
        'unidad',
        'funcion',
        'esencial',
        'orden',
    ];

    protected $casts = [
        'esencial' => 'boolean',
        'orden' => 'integer',
    ];
}
