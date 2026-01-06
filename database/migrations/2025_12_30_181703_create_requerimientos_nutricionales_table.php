<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('requerimientos_nutricionales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tipo_animal_id')->constrained('tipos_animal')->onDelete('cascade');
            $table->string('descripcion', 150);
            $table->string('fuente', 50)->nullable();
            $table->text('comentario')->nullable();
            
            // Consumo esperado
            $table->decimal('consumo_esperado_kg_dia', 10, 4)->nullable()->comment('kg/día');
            $table->boolean('preferido')->default(false);
            
            // Composición básica (%)
            $table->decimal('humedad', 8, 4)->nullable()->comment('%');
            $table->decimal('materia_seca', 8, 4)->nullable()->comment('%');
            $table->decimal('proteina_cruda', 8, 4)->nullable()->comment('%');
            $table->decimal('fibra_bruta', 8, 4)->nullable()->comment('%');
            $table->decimal('extracto_etereo', 8, 4)->nullable()->comment('%');
            $table->decimal('eln', 8, 4)->nullable()->comment('% (Extracto libre de nitrógeno)');
            $table->decimal('ceniza', 8, 4)->nullable()->comment('%');
            
            // Energía (Mcal/kg)
            $table->decimal('energia_digestible', 10, 4)->nullable()->comment('Mcal/kg');
            $table->decimal('energia_metabolizable', 10, 4)->nullable()->comment('Mcal/kg');
            $table->decimal('energia_neta', 10, 4)->nullable()->comment('Mcal/kg');
            $table->decimal('energia_digestible_ap', 10, 4)->nullable()->comment('Mcal/kg (aparente)');
            $table->decimal('energia_metabolizable_ap', 10, 4)->nullable()->comment('Mcal/kg (aparente)');
            
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();
            
            $table->unique(['tipo_animal_id', 'descripcion']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('requerimientos_nutricionales');
    }
};