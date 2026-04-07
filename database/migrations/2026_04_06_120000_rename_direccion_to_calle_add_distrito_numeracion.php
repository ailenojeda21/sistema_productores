<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('propiedades', function (Blueprint $table) {
            $table->renameColumn('direccion', 'calle');
            $table->string('numeracion')->nullable()->after('calle');
            $table->string('distrito')->nullable()->after('numeracion');
        });
    }

    public function down(): void
    {
        Schema::table('propiedades', function (Blueprint $table) {
            $table->dropColumn(['numeracion', 'distrito']);
            $table->renameColumn('calle', 'direccion');
        });
    }
};
