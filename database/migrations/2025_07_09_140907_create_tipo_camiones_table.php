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
        Schema::create('tipo_camiones', function (Blueprint $table) {
            $table->id('tipo_camion_id');
            $table->string('nombre');
            $table->string('descripcion');
            $table->float('tiempo_descarga_1');
            $table->boolean('bloqueo_muelles')->nullable()->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_camiones');
    }
};
