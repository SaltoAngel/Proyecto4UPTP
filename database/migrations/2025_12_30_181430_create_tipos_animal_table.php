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
            $table->string('nombre', 100);
            $table->string('codigo_etapa', 200)->nullable(); // ej: 'LACTANCIA', 'CRECIMIENTO', 'ENGORDA'
            $table->integer('edad_minima_dias')->nullable();
            $table->integer('edad_maxima_dias')->nullable();
            $table->decimal('peso_minimo_kg', 10, 2)->nullable();
            $table->decimal('peso_maximo_kg', 10, 2)->nullable();
            $table->text('descripcion')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();
            
            $table->unique(['especie_id', 'nombre']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipos_animal');
    }
};