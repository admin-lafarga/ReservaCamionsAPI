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
        Schema::create('horarios_muelles', function (Blueprint $table) {
            $table->id('horarios_muelle_id');
            $table->unsignedBigInteger('muelle_id');
            $table->integer('dia_semana');
            $table->time('inicio');
            $table->time('fin');

            $table->timestamps();

            $table->foreign('muelle_id')->references('muelle_id')->on('muelles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('horarios_muelles');
    }
};
