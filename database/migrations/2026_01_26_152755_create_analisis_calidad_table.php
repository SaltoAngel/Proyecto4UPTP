<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('analisis_calidad', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('materia_prima_id')
                  ->constrained('materias_primas')
                  ->onDelete('cascade');
            $table->foreignId('lote_id')->nullable();
            $table->foreignId('proveedor_id')->nullable()
                  ->constrained('proveedores')
                  ->nullOnDelete();
            $table->foreignId('usuario_id')
                  ->constrained('users')
                  ->onDelete('cascade');
            
            // Información del análisis
            $table->string('numero_analisis', 50)->unique();
            $table->date('fecha_muestreo');
            $table->date('fecha_analisis');
            $table->enum('estado_analisis', [
                'pendiente',
                'en_proceso',
                'completado',
                'rechazado'
            ])->default('pendiente');
            
            // Resultados físicos
            $table->decimal('humedad', 5, 2)->nullable();
            $table->decimal('cenizas', 5, 2)->nullable();
            $table->decimal('fibra_cruda', 5, 2)->nullable();
            $table->decimal('grasa', 5, 2)->nullable();
            $table->decimal('proteina_cruda', 5, 2)->nullable();
            
            // Contaminantes
            $table->decimal('aflatoxinas', 6, 2)->nullable();
            $table->decimal('micotoxinas', 6, 2)->nullable();
            $table->decimal('metales_pesados', 6, 2)->nullable();
            
            // Observaciones
            $table->text('observaciones')->nullable();
            $table->enum('decision', [
                'aceptado',
                'rechazado',
                'aceptado_condicional'
            ])->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('numero_analisis');
            $table->index('estado_analisis');
            $table->index('fecha_analisis');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('analisis_calidad');
    }
};