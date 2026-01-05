<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('propiedades', function (Blueprint $table) {
            // eliminar campo viejo
            $table->dropColumn('es_propietario');

            // agregar campos de tenencia
            $table->string('tipo_tenencia')->nullable();
            $table->string('especificar_tenencia')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('propiedades', function (Blueprint $table) {
            // volver a crear es_propietario
            $table->boolean('es_propietario')->default(false);

            // eliminar tenencia
            $table->dropColumn(['tipo_tenencia', 'especificar_tenencia']);
        });
    }
};
