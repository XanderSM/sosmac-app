<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comprobante de Pago</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; margin: 0; padding: 0; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .fw-bold { font-weight: bold; }
        .w-100 { width: 100%; border-collapse: collapse; }
        
        .header-box { text-align: center; margin-bottom: 20px; }
        .company-name { font-size: 16px; font-weight: bold; margin-bottom: 5px;}
        .sunat-box { border: 2px solid #000; padding: 10px; text-align: center; margin: 10px auto; width: 300px; border-radius: 5px; }
        .sunat-box h3 { margin: 0; font-size: 16px; }
        
        .info-table td { padding: 3px; }
        .items-table th, .items-table td { border: 1px solid #ccc; padding: 6px; text-align: center; }
        .items-table th { background-color: #f9f9f9; }
        
        .totals-table { width: 40%; float: right; margin-top: 15px; border-collapse: collapse; }
        .totals-table td { padding: 4px; border: 1px solid #ccc; text-align: right; }
        .totals-table .label { font-weight: bold; text-align: left; background-color: #f9f9f9;}
    </style>
</head>
<body>

    <div class="header-box">
        <div class="company-name">SOSMAC SERVICIOS GENERALES SRL</div>
        <div>JR. CINCO ESQUINAS 1555 URB. SAN LUIS II ETAPA JR. CINCO ESQUINAS 1553</div>
        <div>CAJAMARCA-CAJAMARCA-CAJAMARCA</div>
        
        <div class="sunat-box">
            <h3>RUC: 20529405597</h3>
            <h3>{{ $tipo_comprobante }} ELECTRONICA</h3>
            <h3>E001-{{ str_pad($orden->id, 4, '0', STR_PAD_LEFT) }}</h3>
        </div>
    </div>

    <table class="info-table w-100" style="margin-bottom: 20px;">
        <tr>
            <td width="25%" class="fw-bold">Fecha de Emisión</td>
            <td width="75%">: {{ date('Y-m-d') }}</td>
        </tr>
        <tr>
            <td class="fw-bold">Señor(es)</td>
            <td>: {{ $orden->cotizacion->cliente->nombre_razon_social }}</td>
        </tr>
        <tr>
            <td class="fw-bold">{{ $orden->cotizacion->cliente->tipo_documento }}</td>
            <td>: {{ $orden->cotizacion->cliente->documento }}</td>
        </tr>
        <tr>
            <td class="fw-bold">Dirección del Cliente</td>
            <td>: {{ $orden->cotizacion->direccion_proyecto ?? $orden->cotizacion->cliente->direccion ?? 'S/D' }}</td>
        </tr>
        <tr>
            <td class="fw-bold">Tipo de Moneda</td>
            <td>: SOLES</td>
        </tr>
        <tr>
            <td class="fw-bold">Observación</td>
            <td>: {{ $orden->cotizacion->titulo_proyecto }}</td>
        </tr>
    </table>

    <table class="items-table w-100">
        <thead>
            <tr>
                <th>Cantidad</th>
                <th>Unidad Medida</th>
                <th>Descripción</th>
                <th>Valor Unitario</th>
                <th>ICBPER</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orden->cotizacion->detalles as $detalle)
            <tr>
                <td>{{ $detalle->cantidad }}.00</td>
                <td>UNIDAD</td>
                <td style="text-align: left;">POR {{ strtoupper($detalle->servicio->nombre) }} E001-{{ str_pad($orden->id, 4, '0', STR_PAD_LEFT) }}</td>
                <td>{{ number_format($detalle->precio_unitario, 2) }}</td>
                <td>0.00</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 10px; font-weight: bold;">
        Forma de pago<br>: CONTADO
    </div>

    <table class="totals-table">
        <tr><td class="label">Sub Total Ventas</td><td>S/ {{ number_format($orden->cotizacion->subtotal, 2) }}</td></tr>
        <tr><td class="label">Anticipos</td><td>S/ 0.00</td></tr>
        <tr><td class="label">Descuentos</td><td>S/ 0.00</td></tr>
        <tr><td class="label">Valor Venta</td><td>S/ {{ number_format($orden->cotizacion->subtotal, 2) }}</td></tr>
        <tr><td class="label">ISC</td><td>S/ 0.00</td></tr>
        <tr><td class="label">IGV</td><td>S/ {{ number_format($orden->cotizacion->igv, 2) }}</td></tr>
        <tr><td class="label">ICBPER</td><td>S/ 0.00</td></tr>
        <tr><td class="label">Otros Cargos</td><td>S/ 0.00</td></tr>
        <tr><td class="label">Otros Tributos</td><td>S/ 0.00</td></tr>
        <tr><td class="label">Monto de redondeo</td><td>S/ 0.00</td></tr>
        <tr><td class="label fw-bold">Importe Total</td><td class="fw-bold">S/ {{ number_format($orden->cotizacion->total, 2) }}</td></tr>
    </table>

    <div style="clear: both;"></div>

    <div class="text-center" style="margin-top: 50px; font-size: 10px; color: #555;">
        Esta es una representación impresa de la {{ strtolower($tipo_comprobante) }} electrónica, generada en el Sistema de SUNAT.<br>
        Puede verificarla utilizando su clave SOL.
    </div>

</body>
</html>