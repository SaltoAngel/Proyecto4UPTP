<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('composiciones_nutricionales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('materia_prima_id')->constrained('materias_primas')->onDelete('cascade');
            $table->string('version', 20)->default('1.0')->comment('Versión del análisis');
            $table->date('fecha_analisis')->nullable();
            $table->string('laboratorio', 100)->nullable();
            $table->string('metodo_analisis', 100)->nullable();
            
            // Composición básica (porcentaje en base seca)
            $table->decimal('humedad', 8, 4)->nullable()->comment('%');
            $table->decimal('materia_seca', 8, 4)->nullable()->comment('%');
            $table->decimal('proteina_cruda', 8, 4)->nullable()->comment('%');
            $table->decimal('fibra_bruta', 8, 4)->nullable()->comment('%');
            $table->decimal('extracto_etereo', 8, 4)->nullable()->comment('% (Grasa)');
            $table->decimal('eln', 8, 4)->nullable()->comment('% (Extracto libre de nitrógeno)');
            $table->decimal('ceniza', 8, 4)->nullable()->comment('%');
            
            // Notas
            $table->text('observaciones')->nullable();
            $table->boolean('es_predeterminado')->default(true);
            
            $table->timestamps();
            
            $table->unique(['materia_prima_id', 'version']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('composiciones_nutricionales');
    }
};