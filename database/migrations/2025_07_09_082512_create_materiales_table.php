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
        Schema::create('materiales', function (Blueprint $table) {
            $table->id('material_id');
            $table->string('codigo_sap')->unique();
            $table->string('nombre_material');
            $table->boolean('estado')->nullable()->default(false);
            $table->string('camiones_permitidos');
            $table->string('muelles_permitidos');
            $table->string('max_concurrencia');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materiales');
    }
};
