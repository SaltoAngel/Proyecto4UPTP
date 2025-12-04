<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MateriaPrima extends Model
{
    public function Proveedor(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class); // de uno a muchos.
    }
}
