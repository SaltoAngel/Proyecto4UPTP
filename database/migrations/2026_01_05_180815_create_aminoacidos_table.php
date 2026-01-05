<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aminoacidos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100)->unique();
            $table->string('abreviatura', 10)->nullable();
            $table->string('tipo', 20)->nullable()->comment('Esencial, No esencial');
            $table->text('funcion')->nullable();
            $table->boolean('esencial')->default(true);
            $table->integer('orden')->default(0);
            $table->timestamps();
        });

        Schema::create('requerimiento_aminoacido', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requerimiento_id')->constrained('requerimientos_nutricionales')->onDelete('cascade');
            $table->foreignId('aminoacido_id')->constrained('aminoacidos')->onDelete('cascade');
            $table->decimal('valor_min', 10, 6)->nullable()->comment('% de la proteína');
            $table->decimal('valor_max', 10, 6)->nullable()->comment('% de la proteína');
            $table->decimal('valor_recomendado', 10, 6)->nullable();
            $table->string('unidad', 20)->default('%');
            $table->timestamps();
            
            $table->unique(['requerimiento_id', 'aminoacido_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('requerimiento_aminoacido');
        Schema::dropIfExists('aminoacidos');
    }
};