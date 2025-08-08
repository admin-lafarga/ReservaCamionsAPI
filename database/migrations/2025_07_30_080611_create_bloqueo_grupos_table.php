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
        Schema::create('bloqueo_grupos', function (Blueprint $table) {
            $table->id('bloqueo_grupo_id');
            $table->unsignedBigInteger('tipo_proveedor_id');
            $table->unsignedBigInteger('usuario_id');
            $table->integer('cantidad_total')->default(0);
            $table->integer('cantidad_disponible')->default(0);
            $table->dateTime('fecha_desde');
            $table->dateTime('fecha_hasta');
            $table->boolean('activo')->default(false);

            $table->timestamps();

            $table->foreign('tipo_proveedor_id')->references('tipo_proveedor_id')->on('tipo_proveedores')->onDelete('cascade');
            $table->foreign('usuario_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bloqueo_grupos');
    }
};
