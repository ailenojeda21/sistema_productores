<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('users')->whereNull('dni')->update(['dni' => '']);
        DB::table('users')->whereNull('telefono')->update(['telefono' => '']);
        DB::table('users')->whereNull('direccion')->update(['direccion' => '']);

        Schema::table('users', function (Blueprint $table) {
            $table->string('dni', 20)->nullable(false)->change();
            $table->string('telefono', 20)->nullable(false)->change();
            $table->string('direccion', 255)->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('dni', 20)->nullable()->change();
            $table->string('telefono', 20)->nullable()->change();
            $table->string('direccion', 255)->nullable()->change();
        });
    }
};
