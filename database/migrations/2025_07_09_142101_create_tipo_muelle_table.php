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
        Schema::create('tipo_muelles', function (Blueprint $table) {
            $table->id('tipo_muelle_id');
            $table->string('nombre');
            $table->string('descripcion');
            $table->string('materiales_acceptados');
            $table->boolean('estado')->nullable()->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_muelles');
    }
};
