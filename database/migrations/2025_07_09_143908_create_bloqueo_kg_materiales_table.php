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
        Schema::create('bloqueo_kg_materiales', function (Blueprint $table) {
            $table->id('bloqueo_kg_material_id');
            $table->unsignedBigInteger('material_id');
            $table->unsignedBigInteger('usaurio_id');
            $table->unsignedBigInteger('tipo_proveedor_id');
            $table->integer('cantidad');
            $table->dateTime('inicio');
            $table->dateTime('fin')->nullable();

            $table->timestamps();

            $table->foreign('material_id')->references('material_id')->on('materiales')->onDelete('cascade');
            $table->foreign('usaurio_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('tipo_proveedor_id')->references('tipo_proveedor_id')->on('tipo_proveedores')->onDelete('cascade');
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bloqueo_kg_materiales');
    }
};
