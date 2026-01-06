<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vitaminas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100)->unique();
            $table->string('tipo', 20)->nullable()->comment('Liposoluble, Hidrosoluble');
            $table->string('unidad', 20)->default('UI/kg');
            $table->text('funcion')->nullable();
            $table->boolean('esencial')->default(true);
            $table->integer('orden')->default(0);
            $table->timestamps();
        });

        Schema::create('requerimiento_vitamina', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requerimiento_id')->constrained('requerimientos_nutricionales')->onDelete('cascade');
            $table->foreignId('vitamina_id')->constrained('vitaminas')->onDelete('cascade');
            $table->decimal('valor_min', 10, 6)->nullable();
            $table->decimal('valor_max', 10, 6)->nullable();
            $table->decimal('valor_recomendado', 10, 6)->nullable();
            $table->string('unidad', 20)->default('UI/kg');
            $table->timestamps();
            
            $table->unique(['requerimiento_id', 'vitamina_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('requerimiento_vitamina');
        Schema::dropIfExists('vitaminas');
    }
};