<?php
// database/migrations/xxxx_create_personas_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('personas', function (Blueprint $table) {
            $table->id();
            
            // Código
            $table->string('codigo')->unique()->nullable()->comment('Código interno: CLI-001, PROV-001, etc');
            
            // Tipo de persona: natural o jurídica
            $table->enum('tipo', ['natural', 'juridica'])->default('natural');
            
            // Campos para persona NATURAL
            $table->string('nombres')->nullable();
            $table->string('apellidos')->nullable();
            
            // Campos para persona JURÍDICA
            $table->string('razon_social')->nullable();
            $table->string('nombre_comercial')->nullable();
            
            // Documento e identificación
            $table->string('tipo_documento', 10);
            $table->string('documento', 20)->unique();
            
            // Información de contacto
            $table->string('direccion')->nullable();
            $table->string('estado')->nullable()->comment('Estado/Provincia');
            $table->string('ciudad')->nullable();
            $table->string('telefono')->nullable();
            $table->string('telefono_alternativo')->nullable();
            $table->string('email')->unique()->nullable();
            
            // Estado del registro
            $table->boolean('activo')->default(true);
            
            // Auditoría
            $table->timestamps();
            $table->softDeletes();
            
            // Índices
            $table->index('tipo');
            $table->index('tipo_documento');
            $table->index('documento');
            $table->index('email');
            $table->index('activo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personas');
    }
};