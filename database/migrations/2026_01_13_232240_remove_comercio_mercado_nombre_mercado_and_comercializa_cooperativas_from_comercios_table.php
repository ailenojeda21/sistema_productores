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
        Schema::table('comercios', function (Blueprint $table) {
            $table->dropColumn('comercio_mercado');
            $table->dropColumn('nombre_mercado');
            $table->dropColumn('comercializa_cooperativas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comercios', function (Blueprint $table) {
            $table->boolean('comercio_mercado')->default(false)->after('infraestructura_empaque');
            $table->string('nombre_mercado')->nullable()->after('vende_en_finca');
            $table->boolean('comercializa_cooperativas')->default(false)->after('mercados');
        });
    }
};
