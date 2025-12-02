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
            $table->unsignedBigInteger('entidad_id')->unique();
            $table->string('email_notificaciones');
            $table->string('codigo_sap');
            // $table->timestamp('email_verified_at')->nullable();
            // $table->string('email')->unique();
            // $table->string('contraseña');
            // $table->rememberToken();


            $table->foreign('tipo_proveedor_id')->references('tipo_proveedor_id')->on('tipo_proveedores')->onDelete('cascade');
            $table->foreign('entidad_id')->references('entidad_id')->on('entidades')->onDelete('cascade');

            $table->timestamps();
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
