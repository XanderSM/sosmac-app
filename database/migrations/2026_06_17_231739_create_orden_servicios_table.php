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
        Schema::create('orden_servicios', function (Blueprint $table) {
            $table->id();
            
            // Llave foránea hacia la Cotización (De dónde nace el trabajo)
            $table->foreignId('cotizacion_id')->constrained('cotizaciones')->onDelete('cascade');
            
            // Llave foránea hacia el Usuario (El técnico asignado) - Puede ser nulo hasta que se le asigne a alguien
            $table->foreignId('tecnico_id')->nullable()->constrained('users')->onDelete('set null');
            
            // Datos de programación
            $table->date('fecha_programada');
            $table->time('hora_programada');
            
            // Estado de la ejecución en campo
            $table->string('estado')->default('Pendiente'); // Pendiente, En Ruta, Completada
            
            $table->text('observaciones_admin')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orden_servicios');
    }
};
