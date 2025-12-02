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
        Schema::create('bloqueo_muelles', function (Blueprint $table) {
            $table->id('bloqueo_muelle_id');
            $table->unsignedBigInteger('muelle_id');
            $table->text('asunto');
            $table->timestamp('inicio');
            $table->timestamp('fin')->nullable();

            $table->foreign('muelle_id')->references('muelle_id')->on('muelles')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bloqueo_muelles');
    }
};
