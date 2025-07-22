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
        Schema::create('proveedores', function (Blueprint $table) {
            $table->id('proveedor_id');
            $table->unsignedBigInteger('tipo_proveedor_id');
            $table->string('codigo_sap')->unique();
            $table->string('nombre');
            $table->string('abreviatura');
            $table->string('NIF')->unique();
            $table->string('PIN');
            $table->string('nombre_contacto');
            $table->string('email');
            $table->string('notificaciones_email');
            $table->string('tel1');
            $table->string('tel2');
            $table->string('alerta');
            $table->string('estado');

            $table->timestamps();

            $table->foreign('tipo_proveedor_id')->references('tipo_proveedor_id')->on('tipo_proveedores')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proveedores');
    }
};
