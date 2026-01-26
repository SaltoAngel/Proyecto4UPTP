<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tipos_animal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('especie_id')->constrained('especies')->onDelete('cascade');
            
            // Campos que tienes en tu modal
            $table->string('nombre', 100);
            $table->string('raza_linea', 100)->nullable();
            $table->enum('producto_final', [
                'leche', 'carne', 'huevos', 'doble_proposito', 
                'reproduccion', 'trabajo', 'lana', 'miel', 'otro'
            ])->nullable();
            $table->enum('sistema_produccion', [
                'intensivo', 'semi-intensivo', 'extensivo', 'organico', 'otro'
            ])->default('intensivo');
            $table->string('etapa_especifica', 200)->nullable();
            $table->integer('edad_semanas')->nullable();
            $table->decimal('peso_minimo_kg', 10, 2)->nullable(); // Peso vivo inicial
            $table->text('descripcion')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();
            
            // Único por combinación
            $table->unique(['especie_id', 'nombre', 'raza_linea', 'etapa_especifica']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipos_animal');
    }
};