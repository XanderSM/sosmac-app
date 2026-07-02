<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('orden_servicios', function (Blueprint $table) {
            $table->string('trabajos_autorizados_por')->nullable();
            $table->string('pedido_recibido_por')->nullable();
            $table->text('trabajo_descripcion')->nullable();
            $table->text('comentarios_adicionales')->nullable();
            $table->decimal('descuento', 10, 2)->default(0.00);
            $table->date('fecha_servicio_ejecutado')->nullable();
        });
    }

    public function down()
    {
        Schema::table('orden_servicios', function (Blueprint $table) {
            $table->dropColumn(['trabajos_autorizados_por', 'pedido_recibido_por', 'trabajo_descripcion', 'comentarios_adicionales', 'descuento', 'fecha_servicio_ejecutado']);
        });
    }
};
