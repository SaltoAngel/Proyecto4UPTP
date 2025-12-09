<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('login_count')->default(0)->after('isactive');
            $table->boolean('first_login_completed')->default(false)->after('login_count');
            $table->integer('next_2fa_attempt')->nullable()->after('first_login_completed');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['login_count', 'first_login_completed', 'next_2fa_attempt']);
        });
    }
};  