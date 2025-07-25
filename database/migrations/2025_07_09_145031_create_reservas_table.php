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
            $table->unsignedBigInteger('tipo_camion_id');
            $table->unsignedBigInteger('tipo_material1_id');
            $table->unsignedBigInteger('tipo_material2_id')->nullable();
            $table->unsignedBigInteger('proveedor_id');
            $table->unsignedBigInteger('transporte_id');
            $table->unsignedBigInteger('muelle1_id');
            $table->unsignedBigInteger('muelle2_id')->nullable();;
            $table->unsignedBigInteger('status_id');
            $table->unsignedBigInteger('empresa_id');
            $table->integer('cantidad1');
            $table->integer('cantidad2')->nullable();;
            $table->string('pedido_LF');
            $table->string('matricula_camion');
            $table->dateTime('inicio1');
            $table->dateTime('fin1');
            $table->dateTime('inicio2')->nullable();;
            $table->dateTime('fin2')->nullable();
            $table->boolean('es_aduana')->default(false);
            $table->string('notas')->nullable();
            $table->string('tel1')->nullable();
            $table->integer('duracion1');
            $table->integer('duracion2')->nullable();;

            $table->timestamps();

            $table->foreign('tipo_camion_id')->references('tipo_camion_id')->on('tipo_camiones')->onDelete('cascade');

            $table->foreign('tipo_material1_id')->references('tipo_material_id')->on('tipo_materiales')->onDelete('cascade');
            $table->foreign('tipo_material2_id')->references('tipo_material_id')->on('tipo_materiales')->onDelete('cascade');

            $table->foreign('proveedor_id')->references('proveedor_id')->on('proveedores')->onDelete('cascade');

            $table->foreign('transporte_id')->references('transporte_id')->on('transportes')->onDelete('cascade');

            $table->foreign('muelle1_id')->references('muelle_id')->on('muelles')->onDelete('cascade');
            $table->foreign('muelle2_id')->references('muelle_id')->on('muelles')->onDelete('cascade');

            $table->foreign('status_id')->references('status_id')->on('status')->onDelete('cascade');

            $table->foreign('empresa_id')->references('empresa_id')->on('empresas')->onDelete('cascade');
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
