<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mineral extends Model
{
    use HasFactory;

    protected $table = 'minerales';

    protected $fillable = [
        'nombre',
        'unidad',
        'simbolo',
        'funcion',
        'esencial',
        'orden',
    ];

    protected $casts = [
        'esencial' => 'boolean',
        'orden' => 'integer',
    ];
}
