<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('valores_energeticos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('composicion_id')->constrained('composiciones_nutricionales')->onDelete('cascade');
            $table->foreignId('especie_id')->constrained('especies')->onDelete('cascade');
            $table->string('tipo_energia', 30)->comment('DIGESTIBLE, METABOLIZABLE, NETA, etc.');
            $table->decimal('valor', 10, 4)->nullable()->comment('Mcal/kg');
            $table->string('unidad', 20)->default('Mcal/kg');
            $table->decimal('coeficiente_digestibilidad', 5, 3)->nullable()->comment('0-1');
            $table->text('metodo_calculo')->nullable();
            $table->timestamps();
            
            $table->unique(['composicion_id', 'especie_id', 'tipo_energia']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('valores_energeticos');
    }
};