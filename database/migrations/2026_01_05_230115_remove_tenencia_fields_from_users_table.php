<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // No-op: estas columnas nunca existieron en la tabla users,
        // fueron agregadas directamente a propiedades.
    }

    public function down(): void
    {
    }
};
