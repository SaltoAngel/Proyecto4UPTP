<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Repuesto extends Model
{
    public function Proveedor(): BelongsTo // de uno a muchos
    {
        return $this->belongsTo(Proveedor::class);
    }
}
