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
        Schema::create('propiedades', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuario_id');
            $table->string('direccion')->nullable();
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lng', 10, 7)->nullable();
            
            $table->decimal('hectareas', 8, 2)->nullable();
            $table->boolean('es_propietario')->default(false);
            $table->boolean('malla')->default(false);
            $table->boolean('derecho_riego')->default(false);
            $table->string('tipo_derecho_riego')->nullable();
            $table->boolean('rut')->default(false);
            $table->decimal('rut_valor', 8, 2)->nullable();
            $table->string('rut_archivo')->nullable();
            $table->decimal('hectareas_malla', 8, 2)->nullable();
            $table->boolean('cierre_perimetral')->default(false);
            $table->timestamps();
            $table->foreign('usuario_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('propiedades');
    }
};
