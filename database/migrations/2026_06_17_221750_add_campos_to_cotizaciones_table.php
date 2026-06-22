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
        Schema::table('cotizaciones', function (Blueprint $table) {
            $table->string('titulo_proyecto')->nullable();
            $table->string('direccion_proyecto')->nullable();
            $table->text('notas_areas')->nullable();
            $table->text('notas_materiales')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('cotizaciones', function (Blueprint $table) {
            $table->dropColumn(['titulo_proyecto', 'direccion_proyecto', 'notas_areas', 'notas_materiales']);
        });
    }
};
