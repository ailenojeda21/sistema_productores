<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Verificamos si las columnas existen antes de intentar borrarlas
            if (Schema::hasColumn('users', 'tipo_tenencia')) {
                $table->dropColumn('tipo_tenencia');
            }
            
            if (Schema::hasColumn('users', 'especificar_tenencia')) {
                $table->dropColumn('especificar_tenencia');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('tipo_tenencia')->nullable();
            $table->string('especificar_tenencia')->nullable();
        });
    }
};