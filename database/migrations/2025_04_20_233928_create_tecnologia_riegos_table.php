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
        Schema::create('tecnologia_riegos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('propiedad_id');
            $table->string('tipo');
            $table->timestamps();

            $table->foreign('propiedad_id')->references('id')->on('propiedades')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tecnologia_riegos');
    }
};
