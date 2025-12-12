<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('persona', function (Blueprint $table) {
            $table->id();
            $table->integer('codigo')->unique();
            $table->string('nombres');
            $table->string('apellidos');
            $table->string('razon_social');
            $table->boolean('es_juridico')->default(false);
            $table->string('nombre_comercial')->nullable();
            $table->string('tipo_documento', 10);
            $table->string('documento', 20)->unique();
            $table->string('direccion');
            $table->string('estado');
            $table->string('telefono');
            $table->string('email')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('persona');
    }
};