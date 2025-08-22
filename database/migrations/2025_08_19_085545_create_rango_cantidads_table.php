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
        Schema::create('rango_cantidades', function (Blueprint $table) {
            $table->id('rango_cantidad_id');
            $table->integer('min_kg')->default(0);
            $table->integer('max_kg')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rango_cantidades');
    }
};
