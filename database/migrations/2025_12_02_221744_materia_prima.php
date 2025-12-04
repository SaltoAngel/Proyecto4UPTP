<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categorias_materias_primas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_categoria');   // 'Cereales', 'Proteínas', 'Minerales', 'Vitaminas', etc.
            $table->text('descripcion')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        Schema::create('materias_primas', function (Blueprint $table) { // ← PLURAL
            $table->id();
            $table->foreignId('categoria_id')->constrained('categorias_materias_primas')->onDelete('restrict');
            $table->string('nombre_materia');
            $table->string('codigo_interno')->unique()->nullable();
            $table->enum('unidad_medida', ['kg', 'ton', 'lb', 'unidad', 'litro'])->default('kg');
            $table->decimal('stock_actual', 10, 2)->default(0);
            $table->decimal('stock_minimo', 10, 2)->default(0);
            $table->decimal('costo_promedio', 10, 2)->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        Schema::create('proveedores_materias_primas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proveedor_id')->constrained('proveedores')->onDelete('cascade');
            $table->foreignId('materia_prima_id')->constrained('materias_primas')->onDelete('cascade'); // ← PLURAL
            $table->string('codigo_proveedor')->nullable();
            $table->decimal('precio_unitario', 10, 2);
            $table->boolean('preferido')->default(false);
            $table->timestamps();
            $table->unique(['proveedor_id', 'materia_prima_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proveedores_materias_primas');
        Schema::dropIfExists('materias_primas');
        Schema::dropIfExists('categorias_materias_primas');
    }
};