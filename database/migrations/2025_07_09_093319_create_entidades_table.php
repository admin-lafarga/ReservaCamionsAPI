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
        Schema::create('entidades', function (Blueprint $table) {
            $table->id('entidad_id');
            $table->string('nombre');
            $table->string('abreviatura',)->nullable();
            $table->string('nif');
            $table->string('pin');
            $table->string('nombre_contacto')->nullable();
            $table->string('email');
            $table->string('telefono1');
            $table->string('telefono2')->nullable();
            $table->boolean('alerta')->default(1);
            $table->enum('idioma',['es', 'cat', 'fr', 'en'])->default('es');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entidades');
    }
};
