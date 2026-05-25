<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('staff_users', function (Blueprint $table) {
            $table->timestamp('last_login_at')->nullable()->after('remember_token');
            $table->timestamp('last_access_at')->nullable()->after('last_login_at');
        });
    }

    public function down(): void
    {
        Schema::table('staff_users', function (Blueprint $table) {
            $table->dropColumn(['last_login_at', 'last_access_at']);
        });
    }
};
