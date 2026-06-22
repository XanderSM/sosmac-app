<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CotizacionDetalle extends Model
{
    use HasFactory;

    protected $table = 'cotizacion_detalles';

    protected $fillable = [
        'cotizacion_id',
        'servicio_id',
        'cantidad',
        'precio_unitario',
        'subtotal'
    ];

    // Relación: El detalle pertenece a un servicio del catálogo
    public function servicio()
    {
        return $this->belongsTo(Servicio::class);
    }
}