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
        Schema::create('material_muelles', function (Blueprint $table) {
            $table->unsignedBigInteger('material_id');
            $table->unsignedBigInteger('muelle_id');

            $table->primary(['material_id', 'muelle_id']);

            $table->foreign('material_id')->references('material_id')->on('materiales')->onDelete('cascade');
            $table->foreign('muelle_id')->references('muelle_id')->on('muelles')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_muelle');
    }
};
