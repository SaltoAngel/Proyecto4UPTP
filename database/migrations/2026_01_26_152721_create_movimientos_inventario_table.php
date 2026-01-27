<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movimientos_inventario', function (Blueprint $table) {
            $table->id();
            
            // Relaciones
            $table->foreignId('materia_prima_id')
                  ->constrained('materias_primas')
                  ->onDelete('cascade');
            $table->foreignId('proveedor_id')
                  ->nullable()
                  ->constrained('proveedores')
                  ->nullOnDelete();
            $table->foreignId('usuario_id')
                  ->constrained('users')
                  ->onDelete('cascade');
            
            // Información del movimiento
            $table->enum('tipo_movimiento', [
                'entrada',
                'salida', 
                'ajuste',
                'transferencia',
                'consumo'
            ]);
            
            $table->enum('origen_movimiento', [
                'compra',
                'venta',
                'produccion',
                'ajuste_inventario',
                'merma',
                'transferencia'
            ]);
            
            // Documentos relacionados
            $table->string('numero_documento', 50)->nullable();
            $table->string('referencia', 100)->nullable();
            
            // Cantidades
            $table->decimal('cantidad', 12, 3);
            $table->decimal('cantidad_anterior', 12, 3)->default(0);
            $table->decimal('cantidad_nueva', 12, 3)->default(0);
            
            // Costos
            $table->decimal('costo_unitario', 12, 3)->nullable();
            $table->decimal('costo_total', 12, 3)->nullable();
            
            // Lotes
            $table->string('lote', 50)->nullable();
            $table->date('fecha_vencimiento')->nullable();
            $table->date('fecha_fabricacion')->nullable();
            
            // Control de calidad
            $table->decimal('humedad', 5, 2)->nullable();
            $table->decimal('impurezas', 5, 2)->nullable();
            $table->text('observaciones_calidad')->nullable();
            
            // Auditoría
            $table->text('observaciones')->nullable();
            $table->timestamp('fecha_movimiento')->useCurrent();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Índices
            $table->index('tipo_movimiento');
            $table->index('fecha_movimiento');
            $table->index(['materia_prima_id', 'tipo_movimiento']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimientos_inventario');
    }
};