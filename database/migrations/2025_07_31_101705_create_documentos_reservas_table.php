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
        Schema::create('documentos_reservas', function (Blueprint $table) {
            $table->id('documento_reserva_id');
            $table->unsignedBigInteger('reserva_id');
            $table->string('url')->unique();
            $table->string('name');

            $table->foreign('reserva_id')->references('reserva_id')->on('reservas')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentos_reservas');
    }
};
