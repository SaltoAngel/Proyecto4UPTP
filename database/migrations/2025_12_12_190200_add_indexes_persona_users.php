<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'persona_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->index('persona_id', 'users_persona_id_index');
            });
        }

        if (Schema::hasTable('persona') && Schema::hasColumn('persona', 'documento')) {
            Schema::table('persona', function (Blueprint $table) {
                $table->index('documento', 'persona_documento_index');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropIndex('users_persona_id_index');
            });
        }

        if (Schema::hasTable('persona')) {
            Schema::table('persona', function (Blueprint $table) {
                $table->dropIndex('persona_documento_index');
            });
        }
    }
};
