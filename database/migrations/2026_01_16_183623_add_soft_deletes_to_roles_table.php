<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Migración para insertar la columna delete_ad a la tabla roles.
     */
    public function up()
    {
        Schema::table('roles', function (Blueprint $table) {
            // Añade la columna necesaria para el borrado lógico
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::table('roles', function (Blueprint $table) {
            // Por si necesitas revertir el cambio
            $table->dropSoftDeletes();
        });
    }
};
