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
        Schema::create('privilegios', function (Blueprint $table) {
            $table->id('privilegio_id');
            $table->unsignedBigInteger('id_tipo_usuario');
            $table->string('view');
            $table->boolean('canEdit')->default(false);
            $table->boolean('canEditAll')->default(false);
            $table->boolean('canAdd')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('privilegios');
    }
};
