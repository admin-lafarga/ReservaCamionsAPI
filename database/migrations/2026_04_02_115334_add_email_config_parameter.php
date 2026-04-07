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
        \App\Models\Parametro::updateOrCreate(
            ['clave' => 'email_notificaciones_recepcion'],
            ['valor' => 'hassan.abbas@lafarga.es'] // Default value as requested for testing, but easily changeable
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \App\Models\Parametro::where('clave', 'email_notificaciones_recepcion')->delete();
    }
};
