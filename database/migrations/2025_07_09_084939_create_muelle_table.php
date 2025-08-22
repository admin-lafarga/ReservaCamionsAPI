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
        Schema::create('muelles', function (Blueprint $table) {
            $table->id('muelle_id');
            $table->unsignedBigInteger('empresa_id');
            $table->string('nombre_muelle');
            $table->string('descripcion');
            $table->integer('numero');
            $table->string('zona');
            $table->boolean('abierto_festivos')->nullable()->default(false);
            $table->string('color');
            $table->boolean('estado')->nullable()->default(false);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('muelles');
    }
};
