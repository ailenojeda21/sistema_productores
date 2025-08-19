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
        Schema::create('cultivos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('propiedad_id');
            $table->string('nombre');
            $table->string('estacion');
            $table->string('tipo');
            $table->decimal('hectareas', 8, 2);
            $table->enum('manejo_cultivo', ['Convencional', 'Agroecologico', 'Organico'])->default('Convencional');
            $table->enum('tecnologia_riego', ['Surco', 'Inundación', 'Cimalco', 'Manga', 'Goteo', 'Aspersión'])->nullable();
            $table->timestamps();

            $table->foreign('propiedad_id')->references('id')->on('propiedades')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cultivos');
    }
};
