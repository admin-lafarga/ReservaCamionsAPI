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
            $table->unsignedBigInteger('rol_id');
            $table->string('apellidos');
            $table->string('PIN');
            $table->string('NIF')->unique();
            $table->string('tel1')->unique();
            $table->boolean('estado')->default(true);


            $table->foreign('rol_id')->references('rol_id')->on('roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
        // Eliminar la foreign key primero
        $table->dropForeign(['rol_id']);

        // Luego eliminar las columnas
        $table->dropColumn('rol_id');
        $table->dropColumn('apellidos');
        $table->dropColumn('PIN');
        $table->dropColumn('NIF');
        $table->dropColumn('tel1');
        $table->dropColumn('estado');
    });
    }
};
