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
        Schema::create('restricciones', function (Blueprint $table) {
            $table->id('restriccion_id');
            $table->unsignedBigInteger('muelle_id');
            $table->unsignedBigInteger('muelle_restringido_id');
            $table->timestamps();

            $table->foreign('muelle_id')->references('muelle_id')->on('muelles')->onDelete('cascade');
            $table->foreign('muelle_restringido_id')->references('muelle_id')->on('muelles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restricciones');
    }
};
