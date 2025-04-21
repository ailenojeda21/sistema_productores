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
        Schema::create('implementos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('maquinaria_id');
            $table->string('nombre');
            $table->timestamps();

            $table->foreign('maquinaria_id')->references('id')->on('maquinarias')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('implementos');
    }
};
