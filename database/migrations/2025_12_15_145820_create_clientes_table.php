<?php
// database/migrations/xxxx_create_clientes_table.php (VERSIÓN CORREGIDA)

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            
            // Relación con persona
            $table->foreignId('persona_id')->constrained('personas')->onDelete('cascade');
            
            // Información comercial
            $table->string('codigo_cliente')->unique()->nullable();
            $table->enum('tipo_cliente', [
                'minorista', 
                'mayorista', 
                'corporativo', 
                'distribuidor',
                'especial',
                'potencial'
            ])->default('minorista');
            
            // Información de contacto comercial
            $table->string('contacto_comercial')->nullable();
            $table->string('telefono_comercial')->nullable();
            $table->string('email_comercial')->nullable()->unique();
            
            // Estado del cliente
            $table->boolean('estado')->default(true);
            
            // Información de facturación
            $table->boolean('exento_impuestos')->default(false);
            $table->string('regimen_fiscal')->nullable();
            
            // Fechas importantes (no derivadas de compras)
            $table->date('fecha_registro')->nullable();
            $table->date('fecha_ultima_actualizacion')->nullable();
            
            
            $table->timestamps();
            $table->softDeletes();
            
            // Índices
            $table->index('tipo_cliente');
            $table->index('estado');
            $table->index('codigo_cliente');
            $table->index('fecha_registro');
            $table->index('persona_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};