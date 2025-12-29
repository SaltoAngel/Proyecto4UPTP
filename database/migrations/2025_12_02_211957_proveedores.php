<?php
// database/migrations/xxxx_create_proveedores_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proveedores', function (Blueprint $table) {
            $table->id();
            
            // Relación obligatoria con persona
            $table->foreignId('persona_id')->constrained('personas')->onDelete('cascade');
            
            // Información comercial
            $table->string('codigo_proveedor')->unique()->nullable()->comment('Código interno del proveedor');
            $table->string('categoria')->nullable()->comment('Materia prima, servicios, etc.');
            $table->text('productos_servicios')->nullable();
            $table->text('especializacion')->nullable();
            
            // Contacto adicional específico para proveedor
            $table->string('contacto_comercial')->nullable();
            $table->string('telefono_comercial')->nullable();
            $table->string('email_comercial')->nullable()->unique();
            
            
            // Calificación y evaluación
            $table->integer('calificacion')->nullable()->default(5)->comment('1-5 estrellas');
            $table->text('observaciones_calificacion')->nullable();
            $table->date('fecha_ultima_evaluacion')->nullable();
            
            // Estado operativo
            $table->enum('estado', [
                'activo', 
                'inactivo', 
                'suspendido', 
                'en_revision',
                'bloqueado'
            ])->default('activo');
            
            $table->date('fecha_registro')->nullable();
            $table->date('fecha_ultima_compra')->nullable();
            $table->decimal('monto_total_compras', 15, 2)->nullable()->default(0);
            
            // Información bancaria
            $table->string('banco')->nullable();
            $table->string('tipo_cuenta')->nullable();
            $table->string('numero_cuenta')->nullable();
            
            // Auditoría
            $table->timestamps();
            $table->softDeletes();
            
            // Índices
            $table->index('categoria');
            $table->index('estado');
            $table->index('calificacion');
            $table->index('fecha_registro');
            $table->index('codigo_proveedor');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proveedores');
    }
};