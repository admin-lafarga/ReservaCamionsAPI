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
        Schema::create('transportes', function (Blueprint $table) {
            $table->id('transporte_id');
            $table->unsignedBigInteger('proveedor_id');
            $table->string('nombre');
            $table->string('abreviatura');
            $table->string('NIF');
            $table->string('PIN');
            $table->string('nombre_contacto');
            $table->string('email');
            $table->string('tel1');
            $table->string('tel2');
            $table->boolean('alert')->default(false);
            $table->boolean('estado')->default(true);
            $table->boolean('puede_gestionar')->default(false);

            $table->timestamps();

            $table->foreign('proveedor_id')->references('proveedor_id')->on('proveedores')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transportes');
    }
};
