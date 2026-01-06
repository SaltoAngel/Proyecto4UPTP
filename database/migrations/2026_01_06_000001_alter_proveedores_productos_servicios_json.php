<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // En PostgreSQL, convierte cadenas sueltas a un array JSON para evitar fallos
        DB::statement(<<<'SQL'
ALTER TABLE proveedores
    ALTER COLUMN productos_servicios TYPE jsonb USING (
        CASE
            WHEN productos_servicios IS NULL OR trim(productos_servicios) = '' THEN '[]'::jsonb
            WHEN productos_servicios LIKE '[%' OR productos_servicios LIKE '{%' THEN productos_servicios::jsonb
            ELSE jsonb_build_array(productos_servicios)
        END
    );
SQL);
        DB::statement('ALTER TABLE proveedores ALTER COLUMN productos_servicios DROP NOT NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE proveedores ALTER COLUMN productos_servicios TYPE TEXT USING productos_servicios::text');
        DB::statement('ALTER TABLE proveedores ALTER COLUMN productos_servicios SET NOT NULL');
    }
};