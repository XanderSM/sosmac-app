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
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('tipo'); // Ej: Químico, Trampa, Material
            $table->string('unidad_medida'); // Ej: Litros, Tubos 30g, Unidades
            
            // EL SECRETO CONTRA NEGATIVOS: unsignedInteger()
            $table->unsignedInteger('stock')->default(0); 
            $table->unsignedInteger('stock_minimo')->default(5);
            
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
