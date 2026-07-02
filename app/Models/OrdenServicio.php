<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenServicio extends Model
{
    use HasFactory;

    protected $table = 'orden_servicios';

    protected $fillable = [
        'cotizacion_id',
        'tecnico_id',
        'fecha_programada',
        'hora_programada',
        'estado',
        'observaciones_admin',
        'trabajos_autorizados_por',
        'pedido_recibido_por',
        'trabajo_descripcion',
        'comentarios_adicionales',
        'descuento',
        'fecha_servicio_ejecutado',
    ];

    protected function casts(): array
    {
        return [
            'fecha_programada' => 'date',
            'fecha_servicio_ejecutado' => 'date',
            'descuento' => 'decimal:2',
        ];
    }

    public function cotizacion()
    {
        return $this->belongsTo(Cotizacion::class);
    }

    public function tecnico()
    {
        return $this->belongsTo(User::class, 'tecnico_id');
    }
}
