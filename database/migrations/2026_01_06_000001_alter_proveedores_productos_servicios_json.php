<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Cambia la columna a tipo JSON (MySQL). Si ya hay texto, intenta convertirlo.
        DB::statement("ALTER TABLE proveedores MODIFY productos_servicios JSON NULL");
    }

    public function down(): void
    {
        // Revertir a TEXT si se requiere rollback.
        DB::statement("ALTER TABLE proveedores MODIFY productos_servicios TEXT NULL");
    }
};
