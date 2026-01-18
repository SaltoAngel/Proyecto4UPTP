<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('composicion_aminoacido', function (Blueprint $table) {
            $table->id();
            $table->foreignId('composicion_id')->constrained('composiciones_nutricionales')->onDelete('cascade');
            $table->foreignId('aminoacido_id')->constrained('aminoacidos')->onDelete('cascade');
            $table->decimal('valor', 10, 4)->nullable()->comment('% de la proteína');
            $table->string('unidad', 20)->default('%');
            $table->decimal('valor_min', 10, 4)->nullable();
            $table->decimal('valor_max', 10, 4)->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
            
            $table->unique(['composicion_id', 'aminoacido_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('composicion_aminoacido');
    }
};