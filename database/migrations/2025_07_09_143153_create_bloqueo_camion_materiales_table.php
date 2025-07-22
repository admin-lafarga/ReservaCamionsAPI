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
        Schema::create('bloqueo_camion_materiales', function (Blueprint $table) {
            $table->id('bloqueo_camion_material_id');
            $table->unsignedBigInteger('material_id');
            $table->unsignedBigInteger('proveedor_id');
            $table->unsignedBigInteger('usuario_id');
            $table->integer('cantidad');
            $table->dateTime('inicio');
            $table->dateTime('fin')->nullable();

            $table->foreign('material_id')->references('material_id')->on('materiales')->onDelete('cascade');
            $table->foreign('proveedor_id')->references('proveedor_id')->on('proveedores')->onDelete('cascade');
            $table->foreign('usuario_id')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bloqueo_camion_materiales');
    }
};
