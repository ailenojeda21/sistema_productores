<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('comercios', function (Blueprint $table) {
        $table->id(); // ID (clave primaria)
        $table->unsignedBigInteger('usuario_id'); // Usuario_ID (clave foránea)

    $table->boolean('infraestructura_empaque')->default(false);
    $table->boolean('comercio_feria')->default(false);
    $table->boolean('vende_en_finca')->default(false);
    $table->string('nombre_feria')->nullable();

        $table->timestamps();

        // Clave foránea
        $table->foreign('usuario_id')->references('id')->on('users')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comercios');
    }
};
