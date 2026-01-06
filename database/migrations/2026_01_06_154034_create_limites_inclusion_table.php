<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('limites_inclusion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('materia_prima_id')->constrained('materias_primas')->onDelete('cascade');
            $table->foreignId('especie_id')->constrained('especies')->onDelete('cascade');
            $table->enum('tipo_limite', ['MAXIMO', 'MINIMO', 'RECOMENDADO', 'RESTRINGIDO']);
            $table->decimal('valor', 8, 4)->comment('Porcentaje en la dieta');
            $table->text('justificacion')->nullable();
            $table->string('fuente', 100)->nullable();
            $table->text('observaciones')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            
            $table->unique(['materia_prima_id', 'especie_id', 'tipo_limite']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('limites_inclusion');
    }
};