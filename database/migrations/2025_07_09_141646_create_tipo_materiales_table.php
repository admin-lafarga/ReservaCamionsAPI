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
        Schema::create('tipo_materiales', function (Blueprint $table) {
            $table->id('tipo_material_id');
            $table->string('nombre');
            $table->integer('tiempoD');
            $table->boolean('estado')->nullable()->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_materiales');
    }
};
