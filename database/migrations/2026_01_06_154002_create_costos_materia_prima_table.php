<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('costos_materia_prima', function (Blueprint $table) {
            $table->id();
            $table->foreignId('materia_prima_id')->constrained('materias_primas')->onDelete('cascade');
            $table->decimal('costo_unitario', 12, 4)->comment('Costo por unidad');
            $table->string('unidad_compra', 20)->default('kg')->comment('kg, ton, lb, saco');
            $table->decimal('factor_conversion', 10, 4)->default(1)->comment('Factor a unidad base (kg)');
            $table->decimal('costo_kg', 12, 4)->storedAs('costo_unitario / factor_conversion');
            $table->string('moneda', 3)->default('USD');
            $table->date('fecha_vigencia')->nullable();
            $table->date('fecha_vencimiento')->nullable();
            $table->string('proveedor', 150)->nullable();
            $table->string('lote', 50)->nullable();
            $table->text('observaciones')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            
            $table->index(['materia_prima_id', 'activo']);
            $table->index('fecha_vigencia');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('costos_materia_prima');
    }
};