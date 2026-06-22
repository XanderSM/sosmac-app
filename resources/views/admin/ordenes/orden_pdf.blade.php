<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Orden de Servicio</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 10px; color: #000; margin: 0; padding: 0; }
        .w-100 { width: 100%; }
        table { border-collapse: collapse; width: 100%; margin-bottom: 10px; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .fw-bold { font-weight: bold; }
        .border-all th, .border-all td { border: 1px solid #000; padding: 4px; }
        .bg-gray { background-color: #f0f0f0; }
        
        /* Cabecera */
        .header-table td { vertical-align: top; }
        .logo-box { width: 120px; height: 60px; border: 1px dashed #000; text-align: center; line-height: 60px; font-weight: bold; font-size: 12px; color: #555; }
        .company-title { font-size: 14px; font-weight: bold; color: #003366; }
        .doc-box { border: 2px solid #000; padding: 10px; text-align: center; font-size: 14px; font-weight: bold; border-radius: 5px;}
    </style>
</head>
<body>

    <table class="w-100" style="margin-bottom: 20px;">
        <tr>
            <td style="width: 120px; vertical-align: middle; text-align: left; padding: 0;">
                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/logo.png'))) }}" alt="SOSMAC" style="width: 110px; height: auto;">
            </td>
            
            <td style="vertical-align: middle; text-align: left; padding-left: 0;">
                <div style="font-size: 14px; font-weight: bold; color: #003366; margin-bottom: 3px; white-space: nowrap;">SOSMAC SERVICIOS GENERALES SRL</div>
                <div style="font-size: 10px; line-height: 1.3;">
                    CALLE LOS ESTAMBRES Q5-LOTE 6-URB. SAN ANDRES - TRUJILLO<br>
                    JR. CINCO ESQUINAS Nº 1555-CAJAMARCA<br>
                    956134064 | sosmac.srl@gmail.com
                </div>
            </td>
            
            <td style="width: 220px; vertical-align: middle; text-align: right;">
                <div style="border: 2px solid #000; padding: 12px; text-align: center; border-radius: 5px;">
                    <span style="font-size: 14px; font-weight: bold;">ORDEN DE SERVICIO</span><br>
                    <span style="font-size: 14px; font-weight: bold;">N° E001-{{ str_pad($orden->id, 4, '0', STR_PAD_LEFT) }}</span>
                </div>
            </td>
        </tr>
    </table>

    <table class="border-all">
        <tr class="bg-gray">
            <td class="fw-bold" width="15%">CLIENTE</td>
            <td width="35%">{{ $orden->cotizacion->cliente->nombre_razon_social }}</td>
            <td class="fw-bold" width="15%">RUC/DNI</td>
            <td width="35%">{{ $orden->cotizacion->cliente->documento }}</td>
        </tr>
        <tr>
            <td class="fw-bold">TELÉFONO</td>
            <td>{{ $orden->cotizacion->cliente->telefono ?? 'S/D' }}</td>
            <td class="fw-bold">EMAIL</td>
            <td>{{ $orden->cotizacion->cliente->email ?? 'S/D' }}</td>
        </tr>
        <tr class="bg-gray">
            <td class="fw-bold">RECIBIDO POR</td>
            <td>ADMINISTRACIÓN</td>
            <td class="fw-bold">AUTORIZADO POR</td>
            <td>S/D</td>
        </tr>
    </table>

    <table class="border-all">
        <tr class="bg-gray text-center fw-bold">
            <td width="33%">FECHA DEL PEDIDO</td>
            <td width="33%">FECHA PREVISTA DE INICIO</td>
            <td width="33%">FECHA DEL SERV. EJECUTADO</td>
        </tr>
        <tr class="text-center">
            <td>{{ $orden->cotizacion->created_at->format('Y-m-d') }}</td>
            <td>{{ $orden->fecha_programada }}</td>
            <td>{{ $orden->fecha_programada }}</td>
        </tr>
    </table>

    <div class="fw-bold" style="margin-bottom: 5px;">TRABAJO / DESCRIPCIÓN:</div>
    <div style="border: 1px solid #000; padding: 5px; margin-bottom: 10px; min-height: 40px;">
        {{ $orden->cotizacion->notas_areas ?? 'S/D' }}
    </div>

    <table class="border-all">
        <tr class="bg-gray text-center fw-bold">
            <td width="55%">DESCRIPCIÓN DEL SERVICIO</td>
            <td width="15%">REFER. M2</td>
            <td width="15%">PRECIO</td>
            <td width="15%">IMPORTE</td>
        </tr>
        @foreach($orden->cotizacion->detalles as $detalle)
        <tr>
            <td>{{ $detalle->servicio->nombre }}</td>
            <td class="text-center">{{ $detalle->cantidad }}</td>
            <td class="text-right">S/ {{ number_format($detalle->precio_unitario, 2) }}</td>
            <td class="text-right">S/ {{ number_format($detalle->subtotal, 2) }}</td>
        </tr>
        @endforeach
        <tr>
            <td colspan="2" style="border:none;"></td>
            <td class="bg-gray fw-bold text-right">SUB TOTAL</td>
            <td class="text-right fw-bold">S/ {{ number_format($orden->cotizacion->subtotal, 2) }}</td>
        </tr>
    </table>

    <table class="border-all">
        <tr class="bg-gray text-center fw-bold">
            <td width="55%">DESCRIPCIÓN DE EQUIPOS Y MATERIALES</td>
            <td width="15%">CANTIDAD</td>
            <td width="15%">PRECIO UNIT.</td>
            <td width="15%">IMPORTE</td>
        </tr>
        <tr>
            <td>{{ $orden->cotizacion->notas_materiales ?? 'S/D' }}</td>
            <td class="text-center">-</td>
            <td class="text-right">S/ 0.00</td>
            <td class="text-right">S/ 0.00</td>
        </tr>
        <tr>
            <td colspan="2" style="border:none;"></td>
            <td class="bg-gray fw-bold text-right">MATERIAL TOTAL</td>
            <td class="text-right fw-bold">S/ 0.00</td>
        </tr>
    </table>

    <table class="w-100" style="margin-top: 20px;">
        <tr>
            <td width="60%" class="text-center" style="vertical-align: bottom;">
                <div style="border-top: 1px solid #000; width: 80%; margin: 0 auto; padding-top: 5px;">
                    <div class="fw-bold">CONFORMIDAD DE SERVICIO</div>
                    <div>{{ $orden->tecnico->name ?? 'Técnico Designado' }}</div>
                    <div style="font-size: 8px;">(FIRMA DE CONFORMIDAD DEL SERVICIO)</div>
                </div>
            </td>
            <td width="40%">
                <table class="border-all w-100">
                    <tr><td class="bg-gray fw-bold">COSTO DIRECTO</td><td class="text-right">S/ {{ number_format($orden->cotizacion->subtotal, 2) }}</td></tr>
                    <tr><td class="bg-gray fw-bold">DESCUENTO</td><td class="text-right">S/ 0.00</td></tr>
                    <tr><td class="bg-gray fw-bold">SUB TOTAL</td><td class="text-right">S/ {{ number_format($orden->cotizacion->subtotal, 2) }}</td></tr>
                    <tr><td class="bg-gray fw-bold">IGV 18%</td><td class="text-right">S/ {{ number_format($orden->cotizacion->igv, 2) }}</td></tr>
                    <tr><td class="bg-gray fw-bold" style="font-size: 12px;">TOTAL</td><td class="text-right fw-bold" style="font-size: 12px;">S/ {{ number_format($orden->cotizacion->total, 2) }}</td></tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>