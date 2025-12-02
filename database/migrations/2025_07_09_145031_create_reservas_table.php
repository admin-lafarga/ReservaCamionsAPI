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
        Schema::create('reservas', function (Blueprint $table) {
            $table->id('reserva_id');
            $table->unsignedBigInteger('empresa_lfycs_id');
            $table->unsignedBigInteger('tipo_camion_id');
            $table->unsignedBigInteger('material1_id');
            $table->unsignedBigInteger('material2_id')->nullable();
            $table->unsignedBigInteger('proveedor_id');
            $table->unsignedBigInteger('transportista_id');
            $table->unsignedBigInteger('muelle_id');
            $table->unsignedBigInteger('estado_id')->default(1);
            $table->integer('cantidad1');
            $table->integer('cantidad2')->nullable();
            $table->string('pedido1');
            $table->string('pedido2')->nullable();
            $table->string('matricula_camion');
            $table->dateTime('inicio');
            $table->dateTime('fin');
            $table->integer('duracion');
            $table->string('telefono')->nullable();
            $table->boolean('aduana')->default(false);
            $table->string('notas')->nullable();

            $table->foreign('tipo_camion_id')->references('tipo_camion_id')->on('tipo_camiones')->onDelete('cascade');
            $table->foreign('material1_id')->references('material_id')->on('materiales')->onDelete('cascade');
            $table->foreign('material2_id')->references('material_id')->on('materiales')->onDelete('cascade');
            $table->foreign('proveedor_id')->references('proveedor_id')->on('proveedores')->onDelete('cascade');
            $table->foreign('transportista_id')->references('transportista_id')->on('transportistas')->onDelete('cascade');
            $table->foreign('muelle_id')->references('muelle_id')->on('muelles')->onDelete('cascade');
            $table->foreign('estado_id')->references('estado_id')->on('estados')->onDelete('cascade');
            $table->foreign('empresa_lfycs_id')->references('empresa_lfycs_id')->on('empresas_lfycs')->onDelete('cascade');

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};
