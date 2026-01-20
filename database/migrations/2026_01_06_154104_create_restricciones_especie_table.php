<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('restricciones_especie', function (Blueprint $table) {
            $table->id();
            $table->foreignId('materia_prima_id')->constrained('materias_primas')->onDelete('cascade');
            $table->foreignId('especie_id')->constrained('especies')->onDelete('cascade');
            $table->boolean('permitido')->default(true);
            $table->enum('restriccion_nivel', ['ALTA', 'MEDIA', 'BAJA', 'NINGUNA'])->default('NINGUNA');
            $table->text('razon_restriccion')->nullable();
            $table->text('observaciones')->nullable();
            $table->boolean('requiere_autorizacion')->default(false);
            $table->timestamps();
            
            $table->unique(['materia_prima_id', 'especie_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('restricciones_especie');
    }
};