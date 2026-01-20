<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Verificar y agregar solo las columnas que no existen
            if (!Schema::hasColumn('users', 'verification_code')) {
                $table->string('verification_code')->nullable()->after('password');
            }
            
            if (!Schema::hasColumn('users', 'verification_code_sent_at')) {
                $table->timestamp('verification_code_sent_at')->nullable()->after('verification_code');
            }
            
            if (!Schema::hasColumn('users', 'status')) {
                // Verifica si ya existe la columna status con diferentes valores
                try {
                    // Si la columna existe pero con diferentes valores, la recreamos
                    if (Schema::hasColumn('users', 'status')) {
                        // Primero eliminamos el check constraint existente (PostgreSQL)
                        DB::statement('ALTER TABLE users DROP CONSTRAINT IF EXISTS users_status_check');
                        // Modificamos la columna existente
                        DB::statement("ALTER TABLE users ALTER COLUMN status TYPE VARCHAR(255)");
                        DB::statement("UPDATE users SET status = 'pendiente' WHERE status IS NULL OR status = ''");
                        DB::statement("ALTER TABLE users ADD CONSTRAINT users_status_check CHECK (status IN ('pendiente', 'activo', 'inactivo', 'suspendido'))");
                        DB::statement("ALTER TABLE users ALTER COLUMN status SET DEFAULT 'pendiente'");
                    } else {
                        // Si no existe, la creamos
                        $table->enum('status', ['pendiente', 'activo', 'inactivo', 'suspendido'])->default('pendiente')->after('verification_code_sent_at');
                    }
                } catch (\Exception $e) {
                    // Fallback: crear columna simple
                    $table->string('status')->default('pendiente')->after('verification_code_sent_at');
                }
            } else {
                // Si la columna ya existe, asegurarnos de que tiene el valor por defecto correcto
                DB::statement("ALTER TABLE users ALTER COLUMN status SET DEFAULT 'pendiente'");
            }
            
            if (!Schema::hasColumn('users', 'persona_id')) {
                $table->foreignId('persona_id')->nullable()->constrained('personas')->onDelete('cascade')->after('id');
            }
            
            // NOTA: NO agregamos role_id porque Spatie Permission lo maneja automáticamente
            // Spatie usa una tabla intermedia (model_has_roles) para la relación
        });
        
        // Si ya existe la columna status, actualizar los valores existentes si es necesario
        if (Schema::hasColumn('users', 'status')) {
            DB::statement("UPDATE users SET status = 'pendiente' WHERE status IS NULL OR status = ''");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Solo eliminar las columnas si existen
            $columns = ['verification_code', 'verification_code_sent_at', 'persona_id'];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    if ($column === 'persona_id') {
                        $table->dropForeign(['persona_id']);
                    }
                    $table->dropColumn($column);
                }
            }
            
            // Para status, solo revertir el valor por defecto si existe
            if (Schema::hasColumn('users', 'status')) {
                DB::statement("ALTER TABLE users ALTER COLUMN status SET DEFAULT 'activo'");
                // También podrías revertir el check constraint si lo cambiaste
                DB::statement('ALTER TABLE users DROP CONSTRAINT IF EXISTS users_status_check');
                DB::statement("ALTER TABLE users ADD CONSTRAINT users_status_check CHECK (status IN ('activo', 'inactivo', 'suspendido', 'pendiente'))");
            }
        });
    }
};