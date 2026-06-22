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
        Schema::create('cotizaciones', function (Blueprint $table) {
            $table->id();
            // Restricción: No deja borrar a un cliente si ya tiene cotizaciones (Protege tu BD)
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('restrict');
            
            // Regla de Negocio: Cero negativos
            $table->decimal('subtotal', 10, 2)->unsigned();
            $table->decimal('igv', 10, 2)->unsigned(); // 18% obligatorio
            $table->decimal('total', 10, 2)->unsigned();
            
            $table->string('estado')->default('Pendiente'); // Pendiente, Aprobada, Rechazada
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cotizacions');
    }
};
