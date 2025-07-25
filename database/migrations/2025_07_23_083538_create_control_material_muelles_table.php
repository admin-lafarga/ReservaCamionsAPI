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
        Schema::create('control_material_muelles', function (Blueprint $table) {
            $table->id('control_material_muelle_id');
            $table->unsignedBigInteger('material_id');
            $table->unsignedBigInteger('tipo_camion_id');
            $table->unsignedBigInteger('muelle_id');
            $table->timestamps();

            $table->unique(['material_id', 'tipo_camion_id', 'muelle_id'], 'control_material_unique');

            $table->foreign('tipo_camion_id')->references('tipo_camion_id')->on('tipo_camiones')->onDelete('cascade');
            $table->foreign('muelle_id')->references('muelle_id')->on('muelles')->onDelete('cascade');
            $table->foreign('material_id')->references('material_id')->on('materiales')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('control_material_muelles');
    }
};
