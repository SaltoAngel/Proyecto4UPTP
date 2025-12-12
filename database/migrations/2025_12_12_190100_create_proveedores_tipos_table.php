<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('proveedores_tipos')) {
            Schema::create('proveedores_tipos', function (Blueprint $table) {
                $table->id();
                $table->foreignId('proveedor_id')->constrained('proveedores')->onDelete('cascade');
                $table->foreignId('tipo_proveedor_id')->constrained('tipos_proveedores')->onDelete('cascade');
                $table->timestamps();
                $table->unique(['proveedor_id', 'tipo_proveedor_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('proveedores_tipos');
    }
};
