<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Proveedor extends Model
{
    /*Asignación del tipo de rol seleccionado */
    public function TiposProveedores(): BelongsToMany
    {
        return $this->belongsToMany(TiposProveedores::class); // Muchos a Muchos.
    }

    public function Repuestos(): HasMany
    {
        return $this->hasMany(Repuesto::class); // Un proveedor tiene muchos repuestos.
    }

    public function materiasPrimas(): HasMany
    {
        return $this->hasMany(MateriaPrima::class); // Un proveedor tiene muchas materias primas.
    }

    /* Declaración de existencia de tipo de proveedor */
    public function HasProveedores($hasProveedores)
    {
        return $this->tiposProveedores()->where('nombre_tipo', $hasProveedores)->exists(); // Declarar su existencia.
    }
}
