<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Crear sólo la tabla `proveedores` en esta migración.
        // Los datos personales/contacto se normalizan en la tabla `persona`.
        if (!Schema::hasTable('proveedores')) {
            Schema::create('proveedores', function (Blueprint $table) {
                $table->id();
                // Referencia opcional a `persona` (persona natural o juridica)
                $table->foreignId('persona_id')->nullable()->constrained('personas')->onDelete('set null');
                $table->integer('calificacion')->nullable();
                $table->enum('estado', ['activo', 'inactivo', 'bloqueado'])->default('activo');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('proveedores');
    }
};