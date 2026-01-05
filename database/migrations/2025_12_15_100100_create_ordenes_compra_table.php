<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ordenes_compra', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proveedor_id')
                ->nullable()
                ->constrained('proveedores')
                ->nullOnDelete();
            $table->string('codigo', 50)->unique();
            $table->date('fecha')->nullable();
            $table->string('estado', 50)->default('pendiente')->index();
            $table->decimal('total', 12, 2)->default(0);
            $table->text('observaciones')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ordenes_compra');
    }
};
