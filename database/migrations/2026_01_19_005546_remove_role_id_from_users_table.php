<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Verificar si la columna existe antes de intentar eliminarla
            if (Schema::hasColumn('users', 'role_id')) {
                // Eliminar la restricción de clave foránea si existe
                $table->dropForeign(['role_id']);
                // Eliminar la columna
                $table->dropColumn('role_id');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->nullable()->constrained('roles');
        });
    }
};