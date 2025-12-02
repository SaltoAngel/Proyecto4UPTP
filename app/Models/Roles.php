<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    /*Declaración de la clase User */
    public function users(){
        /*Relación hacia la tabla users */
        return $this->belongsTo(User::class);
    }
}
