<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materias_primas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categoria_id')->nullable()->constrained('categorias_materia_prima')->nullOnDelete();
            $table->string('descripcion', 150);
            $table->string('codigo', 50)->unique()->nullable();
            $table->string('nombre_comercial', 150)->nullable();
            $table->string('nombre_cientifico', 150)->nullable();
            $table->text('comentario')->nullable();
            
            $table->boolean('preferido')->default(false);
            $table->boolean('activo')->default(true);
            $table->boolean('disponible')->default(true);
            
            $table->date('fecha_creacion')->nullable();
            $table->date('fecha_modificacion')->nullable();
            $table->date('fecha_ultima_compra')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['descripcion', 'activo']);
            $table->index('categoria_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materias_primas');
    }
};