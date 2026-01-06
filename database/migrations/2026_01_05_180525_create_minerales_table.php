<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('minerales', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100)->unique();
            $table->string('unidad', 20)->default('mg/kg');
            $table->string('simbolo', 10)->nullable();
            $table->text('funcion')->nullable();
            $table->boolean('esencial')->default(true);
            $table->integer('orden')->default(0);
            $table->timestamps();
        });

        Schema::create('requerimiento_mineral', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requerimiento_id')->constrained('requerimientos_nutricionales')->onDelete('cascade');
            $table->foreignId('mineral_id')->constrained('minerales')->onDelete('cascade');
            $table->decimal('valor_min', 10, 6)->nullable()->comment('Valor mínimo en % o mg/kg');
            $table->decimal('valor_max', 10, 6)->nullable()->comment('Valor máximo en % o mg/kg');
            $table->decimal('valor_recomendado', 10, 6)->nullable()->comment('Valor recomendado');
            $table->string('unidad', 20)->default('mg/kg');
            $table->timestamps();
            
            $table->unique(['requerimiento_id', 'mineral_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('requerimiento_mineral');
        Schema::dropIfExists('minerales');
    }
};