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
        Schema::create('transportistas', function (Blueprint $table) {
            $table->id('transportista_id');
            $table->unsignedBigInteger('entidad_id')->unique();
            $table->boolean('puede_gestionar')->default(false);
            // $table->timestamp('email_verified_at')->nullable();
            // $table->string('contraseña');
            // $table->string('email')->unique();
            // $table->rememberToken();


            $table->foreign('entidad_id')->references('entidad_id')->on('entidades')->onDelete('cascade');
        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transportistas');
    }
};
