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
        Schema::table('staff_users', function (Blueprint $table) {
            // Esto agregará la columna 'deleted_at' de tipo timestamp (nullable)
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff_users', function (Blueprint $table) {
            // Esto eliminará la columna si decides revertir la migración
            $table->dropSoftDeletes();
        });
    }
};