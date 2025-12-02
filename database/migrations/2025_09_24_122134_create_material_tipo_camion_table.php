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
        Schema::create('material_tipo_camiones', function (Blueprint $table) {
            $table->unsignedBigInteger('material_id');
            $table->unsignedBigInteger('tipo_camion_id');

            $table->primary(['material_id', 'tipo_camion_id']);

            $table->foreign('material_id')->references('material_id')->on('materiales')->onDelete('cascade');
            $table->foreign('tipo_camion_id')->references('tipo_camion_id')->on('tipo_camiones')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_tipo_camion');
    }
};
