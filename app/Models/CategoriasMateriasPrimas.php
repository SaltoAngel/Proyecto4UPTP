<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CategoriasMateriasPrimas extends Model
{
    public function MateriaPrima(): BelongsTo
    {
        return $this->belongsTo(MateriaPrima::class);
    }
}
