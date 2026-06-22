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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_documento', 10); // 'DNI' o 'RUC'
            $table->string('documento', 15)->unique(); // Único en la base de datos (RN: no duplicados)
            $table->string('nombre_razon_social');
            $table->string('tipo_cliente', 20); // 'Persona Natural' o 'Empresa'
            $table->string('telefono', 15)->nullable();
            $table->string('email')->nullable();
            $table->string('direccion')->nullable();
            $table->boolean('estado')->default(true); // true = Activo, false = Inactivo
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
