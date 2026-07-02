<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cotización - SOSMAC</title>
    <style>
        * { box-sizing: border-box; }
        
        /* Ajuste de márgenes para eliminar el espacio superior */
        body { 
            font-family: Arial, sans-serif; 
            font-size: 9px; 
            color: #000; 
            margin: -15px 18px 0 18px; 
            padding: 0; 
        }
        
        .w-100 { width: 100%; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .fw-bold { font-weight: bold; }
        
        /* Colores exactos del documento original */
        .bg-cyan { background-color: #5bc0de; } 
        .text-red { color: #ff0000; }
        
        /* Utilidades de bordes */
        .border-all { border-collapse: collapse; width: 100%; }
        .border-all th, .border-all td { border: 1px solid #000; padding: 4px; }
        
        /* Evitar saltos de página dentro de las tablas */
        table { page-break-inside: auto; border-collapse: collapse; }
        tr    { page-break-inside: avoid; page-break-after: auto; }
        
        .header-table { margin-top: 0 !important; margin-bottom: 5px; }
    </style>
</head>
<body>

    <table class="w-100 header-table" style="border: none;">
        <tr>
            <td width="50%" class="text-left" style="vertical-align: middle; padding: 0;">
                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/logo.png'))) }}" alt="SOSMAC" style="height: 60px; width: auto;">
            </td>
            <td width="50%" class="text-right" style="vertical-align: middle; padding: 0;">
                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/minsa.png'))) }}" alt="MINSA" style="height: 40px; width: auto;">
            </td>
        </tr>
    </table>

    <table class="border-all text-center fw-bold" style="margin-bottom: 5px;">
        <tr>
            <td class="bg-cyan" style="font-size: 14px; padding: 6px; letter-spacing: 1px;">COTIZACION</td>
        </tr>
        <tr>
            <td class="bg-cyan" style="font-size: 9px;">AUTORIZACION Nº 006-2017-GRS-LL-SGPGT</td>
        </tr>
        <tr>
            <td style="font-size: 11px; padding: 6px;">{{ $cotizacion->titulo_proyecto ?? 'S/D' }}</td>
        </tr>
    </table>

    <table class="border-all text-center" style="margin-bottom: 15px;">
        <thead>
            <tr class="bg-cyan fw-bold">
                <th width="3%">Nº</th>
                <th colspan="2">ÁREA A TRATAR</th>
                <th width="10%">UND / REF</th>
                <th width="8%">APLIC</th>
                <th width="10%">SERVICIO</th>
                <th width="10%">PRECIO</th>
                <th width="12%">COSTO SERVICIO</th>
            </tr>
        </thead>
        <tbody>
            @php $detalles = $cotizacion->detalles ?? collect(); @endphp
            @if($detalles->isNotEmpty())
                @foreach($detalles as $index => $detalle)
                <tr>
                    <td class="text-center fw-bold">{{ $index + 1 }}</td>
                    <td class="text-center fw-bold" width="18%">{{ strtoupper($detalle->servicio->nombre ?? 'SERVICIO') }}</td>
                    <td class="text-left" width="22%">{{ $detalle->servicio->descripcion ?? 'SERVICIO REGISTRADO' }}</td>
                    <td>GLB</td>
                    <td>1.00</td>
                    <td>{{ number_format($detalle->cantidad, 2) }}</td>
                    <td>{{ number_format($detalle->precio_unitario, 2) }}</td>
                    <td class="text-red">{{ number_format($detalle->subtotal, 2) }}</td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td class="fw-bold">1</td>
                    <td class="fw-bold text-center" width="18%">SIN SERVICIOS</td>
                    <td colspan="6" class="text-left">No se registraron servicios en esta cotización.</td>
                </tr>
            @endif
            <tr>
                <td colspan="7" class="text-right border-0" style="border: none;"></td>
                <td class="text-red">{{ number_format($detalles->sum('subtotal'), 2) }}</td>
            </tr>
        </tbody>
    </table>

    <table class="w-100" style="font-weight: bold; border-collapse: collapse; margin-bottom: 10px; font-size: 9px;">
        <tr>
            <td width="35%" style="border: none;"></td>
            <td width="50%" class="text-right" style="border: 1px solid #000; padding: 5px;"><u>COSTO DIRECTO</u></td>
            <td width="15%" class="text-center text-red" style="border: 1px solid #000; padding: 5px;">{{ number_format($cotizacion->subtotal, 2) }}</td>
        </tr>
        <tr>
            <td style="border: none;"></td>
            <td class="text-right" style="border: none; padding: 5px; padding-right: 6px;"><u>DESCUENTO</u></td>
            <td class="bg-cyan" style="border: 1px solid #000; padding: 5px;"></td>
        </tr>
        <tr>
            <td style="border: none; text-align: left; padding-left: 60px;"><u>IGV</u></td>
            <td class="text-right" style="border: 1px solid #000; padding: 5px;"><u>18.00%</u></td>
            <td class="text-center text-red" style="border: 1px solid #000; padding: 5px;">{{ number_format($cotizacion->igv, 2) }}</td>
        </tr>
        <tr>
            <td style="border: none;"></td>
            <td class="text-right" style="border: solid 1px #000; padding: 5px;"><u>TOTAL COSTO ANUAL FUMIGACIÓN Y DESRATIZACIÓN</u></td>
            <td class="text-center text-red" style="border: 1px solid #000; padding: 5px;">{{ number_format($cotizacion->total, 2) }}</td>
        </tr>
    </table>

    <table class="w-100" style="margin-bottom: 15px; border-collapse: collapse; font-size: 9px;">
        <tr>
            <td width="65%" style="vertical-align: top; border: none; padding-right: 15px;">
                <div style="font-weight: bold; border: 1px solid #000; padding: 6px; margin-bottom: 8px;">
                    SON: {{ \App\Helpers\NumeroALetras::convertir($cotizacion->total) }}
                </div>
                
                @php
                    $rucEmisor = '20601474122';
                    $tipoDoc = '01';
                    $serie = 'C001';
                    $numero = str_pad($cotizacion->id, 8, '0', STR_PAD_LEFT);
                    $igvFormatted = number_format($cotizacion->igv, 2, '.', '');
                    $totalFormatted = number_format($cotizacion->total, 2, '.', '');
                    $fecha = $cotizacion->created_at ? $cotizacion->created_at->format('Y-m-d') : date('Y-m-d');
                    
                    // Manejo seguro del documento del cliente
                    $numDocCliente = $cotizacion->cliente->documento ?? '00000000';
                    $tipoDocCliente = strlen($numDocCliente) == 11 ? '6' : '1';
                    
                    $sunatString = "{$rucEmisor}|{$tipoDoc}|{$serie}|{$numero}|{$igvFormatted}|{$totalFormatted}|{$fecha}|{$tipoDocCliente}|{$numDocCliente}|";
                    $hashSunat = strtoupper(substr(hash('sha256', $sunatString), 0, 20));

                    // Generar QR externo y codificar en base64 para que DOMPDF lo imprima sin conexión remota
                    $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=100x100&margin=1&data=' . urlencode($sunatString);
                    $qrData = 'data:image/png;base64,' . base64_encode(file_get_contents($qrUrl));
                @endphp

                <table style="border: none; width: 100%;">
                    <tr>
                        <td width="80px" style="border: none; padding: 0; vertical-align: middle;">
                            <div style="width: 72px; height: 72px; border: 1px solid #000; padding: 2px;">
                                <img src="{{ $qrData }}" alt="QR SUNAT" style="width: 66px; height: 66px; display: block;" />
                            </div>
                        </td>
                        <td style="border: none; padding-left: 10px; vertical-align: middle; font-size: 7.5px; color: #444; font-style: italic; line-height: 1.2;">
                            Documento electrónico de cotización registrado en el sistema.<br>
                            Verificación de integridad mediante firma digital y estándar de Facturación Electrónica SUNAT.<br>
                            Hash de validación: {{ $hashSunat }}
                        </td>
                    </tr>
                </table>
            </td>
            <td width="35%" style="border: none;"></td>
        </tr>
    </table>

    <table class="border-all w-100" style="margin-bottom: 20px;">
        <tr>
            <td colspan="2" class="bg-cyan fw-bold text-center">CONDICIONES DE VENTA</td>
        </tr>
        <tr>
            <td class="fw-bold" width="25%">EMPRESA</td>
            <td>{{ $cotizacion->cliente->nombre_razon_social ?? 'S/D' }}</td>
        </tr>
        <tr>
            <td class="fw-bold">DIRECCION</td>
            <td>{{ $cotizacion->direccion_proyecto ?? $cotizacion->cliente->direccion ?? 'S/D' }}</td>
        </tr>
        <tr>
            <td class="fw-bold">AREAS A TRATAR</td>
            <td>{{ $cotizacion->notas_areas ?? 'CAMPAMENTO : CONSISTE EN LA FUMIGACION DE OFICINAS, SS.HH. AREA DE RESIDUOS SOLIDOS, ALMACENES Y EXTERIORES DEL CAMPAMENTO, EL TRATAMIENTO ES PARA MOSCAS Y ZANCUDOS.' }}</td>
        </tr>
        <tr class="bg-cyan">
            <td class="fw-bold">Frecuencia sugerida</td>
            <td>60 DIAS</td>
        </tr>
        <tr class="bg-cyan">
            <td class="fw-bold">Forma de pago</td>
            <td>A CONVENIR</td>
        </tr>
        <tr class="bg-cyan">
            <td class="fw-bold">Aprobación</td>
            <td style="text-decoration: underline;">sosmac.srl@gmail.com</td>
        </tr>
        <tr>
            <td class="fw-bold">Depositar a</td>
            <td>SERVICIOS GENERALES SOSMAC SRL</td>
        </tr>
        <tr>
            <td class="fw-bold">CUENTA AHORROS</td>
            <td style="padding: 0; border: none;">
                <table class="w-100" style="border-collapse: collapse;">
                    <tr>
                        <td style="border-bottom: 1px solid #000; border-right: 1px solid #000; padding: 4px;" width="40%">CONTINENTAL</td>
                        <td style="border-bottom: 1px solid #000; padding: 4px; text-align: center;" width="60%">0011 - 0248 - 0200147412 - 27</td>
                    </tr>
                    <tr>
                        <td style="border-right: 1px solid #000; padding: 4px;">CCI CONTINENTAL</td>
                        <td style="padding: 4px; text-align: center;">011 - 248 - 000200147412 - 27</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td class="fw-bold">Cta. Detracción</td>
            <td style="padding: 0; border: none;">
                <table class="w-100" style="border-collapse: collapse;">
                    <tr>
                        <td style="border-right: 1px solid #000; padding: 4px;" width="40%">NACIÓN, Afecto 10%</td>
                        <td style="padding: 4px; text-align: center;" width="60%">00761 150863</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="bg-cyan fw-bold text-center">DEL SERVICIO</td>
        </tr>
        <tr>
            <td class="fw-bold">Materiales</td>
            <td>{{ $cotizacion->notas_materiales ?? 'Malathion, Deltamax 2.5%, Estoque, S Delta- Dependiendo de la evaluación tecnica la aplicación por termonebulización / aspersión / Nebulización' }}</td>
        </tr>
        <tr>
            <td class="fw-bold">OTROS</td>
            <td>No incluye movilidad para el traslado interior</td>
        </tr>
        <tr>
            <td class="fw-bold">NOTA</td>
            <td>CUENTA SCOTIABANK - JAVIER SOSA PILCO - GERENTE GENERAL - 710 8419578</td>
        </tr>
    </table>

</body>
</html>