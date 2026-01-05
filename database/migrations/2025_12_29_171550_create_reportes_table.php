<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reportes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // Nombre del reporte
            $table->string('template'); // Archivo JRXML (sin extensión)
            $table->string('descripcion')->nullable(); // Descripción del reporte
            $table->string('categoria')->nullable(); // Categoría (Ventas, Inventario, etc.)
            $table->json('parametros')->nullable(); // Parámetros disponibles
            $table->boolean('activo')->default(true); // Si está disponible
            $table->boolean('requiere_db')->default(false); // Si necesita conexión a BD
            $table->timestamps();
            
            $table->index(['categoria', 'activo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reportes');
    }
};
