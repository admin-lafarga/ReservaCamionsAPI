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
        Schema::create('bloqueo_grupo_material_detalles', function (Blueprint $table) {
            $table->id('bloqueo_grupo_detalle_id');
            $table->unsignedBigInteger('bloqueo_grupo_id');
            $table->unsignedBigInteger('material_id');

            $table->timestamps();

            $table->foreign('bloqueo_grupo_id')->references('bloqueo_grupo_id')->on('bloqueo_grupo_materiales')->onDelete('cascade');
            $table->foreign('material_id')->references('material_id')->on('materiales')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bloqueo_grupo_material_detalles');
    }
};
