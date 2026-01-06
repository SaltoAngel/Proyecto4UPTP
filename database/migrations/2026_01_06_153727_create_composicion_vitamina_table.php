<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('composicion_vitamina', function (Blueprint $table) {
            $table->id();
            $table->foreignId('composicion_id')->constrained('composiciones_nutricionales')->onDelete('cascade');
            $table->foreignId('vitamina_id')->constrained('vitaminas')->onDelete('cascade');
            $table->decimal('valor', 12, 6)->nullable();
            $table->string('unidad', 20);
            $table->decimal('valor_min', 12, 6)->nullable();
            $table->decimal('valor_max', 12, 6)->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
            
            $table->unique(['composicion_id', 'vitamina_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('composicion_vitamina');
    }
};