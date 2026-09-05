<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Unicidad del DNI que permite multiplas filas con DNI vacío (''),
     * porque el registro y el soft delete generan '' (no NULL).
     *
     * - SQLite (tests)         : indice parcial nativo `WHERE dni <> ''`.
     * - MySQL / MariaDB        : columna generada STORED que devuelve NULL
     *   cuando dni == '', con indice unique. (MariaDB 10.x no soporta
     *   índices funcionales, solo columnas generadas.)
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            DB::statement("CREATE UNIQUE INDEX users_dni_unique ON users (dni) WHERE dni <> ''");
        } else {
            Schema::table('users', function (Blueprint $table) {
                $table->string('dni_unique_key')
                    ->storedAs("CASE WHEN `dni` = '' THEN NULL ELSE `dni` END")
                    ->unique();
            });
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            Schema::table('users', function ($table) {
                $table->dropUnique('users_dni_unique');
            });
        } else {
            Schema::table('users', function (Blueprint $table) {
                $table->dropUnique('users_dni_unique_key_unique');
                $table->dropColumn('dni_unique_key');
            });
        }
    }
};
