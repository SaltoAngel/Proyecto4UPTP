<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categorias_materia_prima', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100)->unique();
            $table->string('codigo_nrc', 20)->nullable();
            $table->text('descripcion')->nullable();
            $table->enum('tipo', [
                'ENERGETICO',
                'PROTEICO',
                'FORRAJE_VERDE',
                'FORRAJE_SECO',
                'ENSILAJE',
                'MINERAL',
                'VITAMINA',
                'ADITIVO',
                'SUPLEMENTO',
                'OTRO'
            ])->default('ENERGETICO');
            $table->boolean('activo')->default(true);
            $table->integer('orden')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categorias_materia_prima');
    }
};