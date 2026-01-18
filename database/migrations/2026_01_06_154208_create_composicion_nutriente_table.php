<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('composicion_nutriente', function (Blueprint $table) {
            $table->id();
            $table->foreignId('composicion_id')->constrained('composiciones_nutricionales')->onDelete('cascade');
            $table->string('nombre', 100);
            $table->decimal('valor', 12, 6)->nullable();
            $table->string('unidad', 20);
            $table->integer('orden')->default(0);
            $table->timestamps();
            
            $table->unique(['composicion_id', 'nombre']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('composicion_nutriente');
    }
};