<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Roles extends Model
{
    /*Declaración de la clase User */
    public function Users(): BelongsTo // de uno a muchos (Inversa).
    {
        /*Relación hacia la tabla users */
        return $this->belongsTo(User::class);
    }
}
