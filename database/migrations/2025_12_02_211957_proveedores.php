<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proveedores', function (Blueprint $table) {
            $table->id();
            $table->string('razon_social');
            $table->string('rif')->unique();
            $table->string('nombre');
            $table->string('apellido');
            $table->string('telefono', 20);
            $table->string('correo_proveedor')->unique();
            $table->string('dias_entrega');
            $table->text('direccion_proveedor');
            $table->enum('estado', ['activo', 'inactivo', 'bloqueado'])->default('activo');
            $table->timestamps();
        });

        // Para consulta 
        Schema::create('tipos_proveedores', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_tipo');   // 'Materia Prima', 'Repuestos', 'Servicios', etc.
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });

        Schema::create('proveedores_tipos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proveedor_id')->constrained('proveedores')->onDelete('cascade');
            $table->foreignId('tipo_proveedor_id')->constrained('tipos_proveedores')->onDelete('cascade'); // ← CORREGIDO: era 'contrained'
            $table->timestamps();
            $table->unique(['proveedor_id', 'tipo_proveedor_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proveedores_tipos');
        Schema::dropIfExists('tipos_proveedores');
        Schema::dropIfExists('proveedores');
    }
};