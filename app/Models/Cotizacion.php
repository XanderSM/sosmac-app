<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cotizacion extends Model
{
    use HasFactory;

    protected $table = 'cotizaciones';

    protected $fillable = [
        'cliente_id',
        'titulo_proyecto',
        'direccion_proyecto',
        'notas_areas',
        'notas_materiales',
        'subtotal',
        'igv',
        'total',
        'estado'
    ];

    // Relación: Una cotización pertenece a un cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    // Relación: Una cotización tiene muchos detalles
    public function detalles()
    {
        return $this->hasMany(CotizacionDetalle::class);
    }

    public function orden()
    {
        return $this->hasOne(OrdenServicio::class);
    }
}