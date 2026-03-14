<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('comercios', 'comercializa_cooperativas')) {
            Schema::table('comercios', function (Blueprint $table) {
                $table->boolean('comercializa_cooperativas')
                      ->default(false)
                      ->after('infraestructura_empaque');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('comercios', 'comercializa_cooperativas')) {
            Schema::table('comercios', function (Blueprint $table) {
                $table->dropColumn('comercializa_cooperativas');
            });
        }
    }
};