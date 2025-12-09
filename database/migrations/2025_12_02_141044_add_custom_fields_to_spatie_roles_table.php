<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        public function up()
        {
            Schema::table('roles', function (Blueprint $table) {
                $table->string('display_name')->after('name')->nullable();
                $table->text('description')->after('display_name')->nullable();
            });
        }

        public function down()
        {
            Schema::table('roles', function (Blueprint $table) {
                $table->dropColumn(['display_name', 'description']);
            });
        }
    };