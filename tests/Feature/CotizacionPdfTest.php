<?php

use App\Models\Cliente;
use App\Models\Cotizacion;
use App\Models\CotizacionDetalle;
use App\Models\Servicio;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('solo muestra en el pdf los servicios que realmente fueron seleccionados', function () {
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
        'titulo_proyecto' => 'Proyecto de prueba',
        'direccion_proyecto' => 'Dirección de prueba',
        'subtotal' => 100,
        'igv' => 18,
        'total' => 118,
        'estado' => 'Aprobada',
        'estado_documento' => 'Borrador',
    ]);

    $servicio = Servicio::create([
        'nombre' => 'Desinsectación',
        'descripcion' => 'Servicio de prueba',
        'precio_base' => 100,
        'estado' => 'Activo',
    ]);

    CotizacionDetalle::create([
        'cotizacion_id' => $cotizacion->id,
        'servicio_id' => $servicio->id,
        'cantidad' => 1,
        'precio_unitario' => 100,
        'subtotal' => 100,
    ]);

    $html = view('admin.cotizaciones.pdf', compact('cotizacion'))->render();

    expect($html)->toContain('Desinsectación')
        ->and($html)->not->toContain('CEBADERO COMUN');
});

it('renderiza el pdf de cotización sin depender de un qr externo', function () {
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
        'titulo_proyecto' => 'Proyecto de prueba',
        'direccion_proyecto' => 'Dirección de prueba',
        'subtotal' => 100,
        'igv' => 18,
        'total' => 118,
        'estado' => 'Aprobada',
        'estado_documento' => 'Borrador',
    ]);

    $html = view('admin.cotizaciones.pdf', compact('cotizacion'))->render();

    // The QR is embedded as a data URI and uses a neutral alt text 'QR SUNAT'.
    expect($html)->toContain('data:image/svg+xml;base64,')
        ->and($html)->toContain('QR SUNAT');
});
