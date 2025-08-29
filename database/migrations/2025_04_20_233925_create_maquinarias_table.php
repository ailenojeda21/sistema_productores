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
    Schema::create('maquinarias', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('propiedad_id');
        $table->boolean('tractor')->default(0);
        $table->integer('modelo_tractor')->nullable();
        $table->boolean('arado')->default(0);
        $table->boolean('rastra')->default(0);
        $table->boolean('niveleta_comun')->default(0);
        $table->boolean('niveleta_laser')->default(0);
        $table->boolean('cincel_cultivadora')->default(0);
        $table->boolean('desmalezadora')->default(0);
        $table->boolean('pulverizadora_tractor')->default(0);
        $table->boolean('mochila_pulverizadora')->default(0);
        $table->boolean('cosechadora')->default(0);
        $table->boolean('enfardadora')->default(0);
        $table->boolean('retroexcavadora')->default(0);
        $table->timestamps();

        $table->foreign('propiedad_id')
              ->references('id')->on('propiedades')
              ->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maquinarias');
    }
};
