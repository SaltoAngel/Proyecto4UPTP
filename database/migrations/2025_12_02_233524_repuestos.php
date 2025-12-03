<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        Schema::create('repuestos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('codigo_interno')->unique()->nullable();
            $table->text('descripcion')->nullable();
            $table->string('marca')->nullable();
            $table->string('modelo')->nullable();
            $table->string('maquina_compatible')->nullable();
            $table->integer('stock_actual')->default(0);
            $table->integer('stock_minimo')->default(0);
            $table->decimal('costo_promedio', 10, 2)->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        Schema::create('proveedor_repuesto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proveedor_id')->constrained('proveedores')->onDelete('cascade');
            $table->foreignId('repuesto_id')->constrained('repuestos')->onDelete('cascade');
            $table->string('codigo_proveedor')->nullable();
            $table->decimal('precio_unitario', 10, 2);
            $table->boolean('preferido')->default(false);
            $table->timestamps();
            $table->unique(['proveedor_id', 'repuesto_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proveedor_repuesto');
        Schema::dropIfExists('repuestos');
    }
};