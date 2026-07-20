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
        Schema::table('maquinarias', function (Blueprint $table) {
            $table->unique('propiedad_id', 'maquinarias_propiedad_id_unique');
        });

        Schema::table('comercios', function (Blueprint $table) {
            $table->unique('usuario_id', 'comercios_usuario_id_unique');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->index('deleted_at');
        });

        Schema::table('staff_users', function (Blueprint $table) {
            $table->index('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('maquinarias', function (Blueprint $table) {
            $table->dropUnique('maquinarias_propiedad_id_unique');
        });

        Schema::table('comercios', function (Blueprint $table) {
            $table->dropUnique('comercios_usuario_id_unique');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['deleted_at']);
        });

        Schema::table('staff_users', function (Blueprint $table) {
            $table->dropIndex(['deleted_at']);
        });
    }
};
