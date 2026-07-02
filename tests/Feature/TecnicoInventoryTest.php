<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

use App\Models\Cliente;
use App\Models\Cotizacion;
use App\Models\OrdenServicio;
use App\Models\Producto;
use App\Models\User;

it('descuenta el stock del inventario cuando un tecnico completa una orden con un producto', function () {
    $tecnico = User::factory()->create(['rol' => 'tecnico']);

    $cliente = Cliente::create([
        'tipo_documento' => 'DNI',
        'documento' => '12345678',
        'nombre_razon_social' => 'Cliente Test',
        'tipo_cliente' => 'Persona Natural',
        'telefono' => '999999999',
        'email' => 'cliente@test.com',
        'direccion' => 'Av. Test 123',
        'estado' => true,
    ]);

    $cotizacion = Cotizacion::create([
        'cliente_id' => $cliente->id,
        'subtotal' => 100,
        'igv' => 18,
        'total' => 118,
        'estado' => 'Aprobada',
    ]);

    $producto = Producto::create([
        'nombre' => 'Filtro',
        'tipo' => 'Consumible',
        'unidad_medida' => 'unidad',
        'stock' => 10,
        'stock_minimo' => 2,
        'estado' => 'Activo',
    ]);

    $orden = OrdenServicio::create([
        'cotizacion_id' => $cotizacion->id,
        'tecnico_id' => $tecnico->id,
        'fecha_programada' => now()->toDateString(),
        'hora_programada' => '08:00:00',
        'estado' => 'Pendiente',
    ]);

    $this->actingAs($tecnico)
        ->put(route('tecnico.ordenes.estado', $orden->id), [
            'observaciones_tecnicas' => 'Servicio finalizado',
            'producto_id' => $producto->id,
            'cantidad_usada' => 3,
        ])
        ->assertRedirect();

    $orden->refresh();
    $producto->refresh();

    expect($orden->estado)->toBe('Completada')
        ->and($producto->stock)->toBe(7);
});
