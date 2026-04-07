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
        // Verifica si NO existe la columna antes de agregarla
        if (!Schema::hasColumn('staff_users', 'deleted_at')) {
            Schema::table('staff_users', function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Verifica si existe antes de eliminarla
        if (Schema::hasColumn('staff_users', 'deleted_at')) {
            Schema::table('staff_users', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
    }
};