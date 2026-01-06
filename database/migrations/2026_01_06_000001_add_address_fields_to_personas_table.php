<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('personas', function (Blueprint $table) {
            $table->string('municipio')->nullable()->after('estado');
            $table->string('parroquia')->nullable()->after('municipio');
            $table->string('tipo_via', 50)->nullable()->after('parroquia');
            $table->string('nombre_via', 120)->nullable()->after('tipo_via');
            $table->string('numero_piso_apto', 40)->nullable()->after('nombre_via');
            $table->string('urbanizacion_sector', 120)->nullable()->after('numero_piso_apto');
            $table->string('referencia', 200)->nullable()->after('urbanizacion_sector');
        });
    }

    public function down(): void
    {
        Schema::table('personas', function (Blueprint $table) {
            $table->dropColumn([
                'municipio',
                'parroquia',
                'tipo_via',
                'nombre_via',
                'numero_piso_apto',
                'urbanizacion_sector',
                'referencia',
            ]);
        });
    }
};
