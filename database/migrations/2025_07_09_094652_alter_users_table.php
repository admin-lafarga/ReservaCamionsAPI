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
        Schema::table('users', function (Blueprint $table) {
            $table->string('NIF')->nullable();
            $table->string('tel1')->nullable();
            $table->enum('idioma',['es', 'cat', 'fr', 'en'])->default('es');
            $table->unsignedBigInteger('rol_id')->after('id')->default(3);
            $table->string('contraseña_antigua')->nullable();

            $table->foreign('rol_id')->references('rol_id')->on('roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('NIF');
        $table->dropColumn('tel1');
        $table->dropColumn('idioma');
    });
    }
};
