<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_rol')->unique();
            $table->string('descripcion_rol');
            $table->timestamps();
        });

        Schema::create('roles_user', function (Blueprint $table) {
            $table->id();
            $table->ForeignId('user_id')->Constrained()->onDelete('cascade');
            $table->ForeignId('roles_id')->Constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
        Schema::dropIfExists('roles_user');
    }
};
