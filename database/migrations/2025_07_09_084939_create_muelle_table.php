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
        Schema::create('muelles', function (Blueprint $table) {
            $table->id('muelle_id');
            $table->unsignedBigInteger('empresa_lfycs_id');
            $table->string('nombre');
            $table->string('descripcion');
            $table->string('color');
            
            $table->timestamps();

            $table->foreign('empresa_lfycs_id')->references('empresa_lfycs_id')->on('empresas_lfycs');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('muelles');
    }
};
