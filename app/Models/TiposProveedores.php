<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TiposProveedores extends Model
{
    public function Proveedor(): BelongsTo // de un proveedor hay muchos tipos.
    {
        return $this->belongsTo(Proveedor::class);
    }  
}
