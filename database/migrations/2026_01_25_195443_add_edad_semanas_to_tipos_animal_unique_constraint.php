<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Primero, agregar la columna tipo_animal_id si no existe
        Schema::table('alimentos', function (Blueprint $table) {
            if (!Schema::hasColumn('alimentos', 'tipo_animal_id')) {
                $table->foreignId('tipo_animal_id')->nullable()
                      ->constrained('tipos_animal')
                      ->onDelete('cascade');
            }
        });
        
        // Segundo, modificar la restricción única de tipos_animal
        Schema::table('tipos_animal', function (Blueprint $table) {
            // Eliminar la restricción única antigua si existe
            $table->dropUnique('tipos_animal_especie_id_nombre_raza_linea_etapa_especifica_uniq');
            
            // Agregar nueva restricción única incluyendo edad_semanas
            $table->unique([
                'especie_id', 
                'nombre', 
                'raza_linea', 
                'etapa_especifica',
                'edad_semanas'
            ], 'tipos_animal_completo_uniq');
        });
    }

    public function down(): void
    {
        Schema::table('tipos_animal', function (Blueprint $table) {
            // Eliminar la nueva restricción
            $table->dropUnique('tipos_animal_completo_uniq');
            
            // Restaurar la restricción antigua
            $table->unique([
                'especie_id', 
                'nombre', 
                'raza_linea', 
                'etapa_especifica'
            ], 'tipos_animal_especie_id_nombre_raza_linea_etapa_especifica_uniq');
        });
        
        Schema::table('alimentos', function (Blueprint $table) {
            if (Schema::hasColumn('alimentos', 'tipo_animal_id')) {
                $table->dropForeign(['tipo_animal_id']);
                $table->dropColumn('tipo_animal_id');
            }
        });
    }
};