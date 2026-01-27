<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventario_materias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('materia_prima_id')
                  ->constrained('materias_primas')
                  ->onDelete('cascade');
            
            // Campos de stock
            $table->decimal('stock_actual', 12, 3)->default(0);
            $table->decimal('stock_minimo', 12, 3)->default(0);
            $table->decimal('stock_maximo', 12, 3)->default(0);
            $table->decimal('punto_reorden', 12, 3)->default(0);
            
            // Estado del inventario
            $table->enum('estado', [
                'normal',
                'critico', 
                'agotado'
            ])->default('normal');
            
            // Almacenamiento
            $table->string('almacen', 100)->nullable();
            $table->string('estante', 50)->nullable();
            $table->string('posicion', 50)->nullable();
            $table->string('lote_actual', 50)->nullable();
            
            // Control de calidad
            $table->decimal('humedad_actual', 5, 2)->nullable();
            $table->date('fecha_ultima_verificacion')->nullable();
            $table->date('fecha_ultimo_movimiento')->nullable();
            
            // Costos
            $table->decimal('costo_promedio', 10, 2)->default(0);
            $table->decimal('ultimo_costo', 10, 2)->default(0);
            
            $table->timestamps();
            $table->softDeletes();
            
            // Índices
            $table->index('materia_prima_id');
            $table->index('estado');
            $table->index(['stock_actual', 'estado']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventario_materias');
    }
};