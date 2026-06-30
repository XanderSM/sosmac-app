<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cotización - SOSMAC</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 9px; color: #000; margin: 0; padding: 0; }
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
        table { page-break-inside: auto; }
        tr    { page-break-inside: avoid; page-break-after: auto; }
    </style>
</head>
<body>

    <table class="w-100" style="border: none; margin-bottom: 5px;">
        <tr>
            <td width="50%" class="text-left" style="vertical-align: middle;">
                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/logo.png'))) }}" alt="SOSMAC" style="height: 60px; width: auto;">
            </td>
            <td width="50%" class="text-right" style="vertical-align: middle;">
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
            <tr class="bg-cyan fw-bold">
                <td colspan="8">DESINSECTACION</td>
            </tr>
            @php $countDetalles = count($cotizacion->detalles); @endphp
            @if($countDetalles > 0)
                @foreach($cotizacion->detalles as $index => $detalle)
                <tr>
                    @if($index == 0)
                    <td rowspan="{{ $countDetalles }}" class="text-center fw-bold">1</td>
                    <td rowspan="{{ $countDetalles }}" class="text-center fw-bold" width="18%">DESINSECTACION<br>APLICACIÓN X<br>NEBULIZACION Y/O<br>TERMONEBULIZACION</td>
                    @endif
                    <td class="text-left" width="22%">{{ $detalle->servicio->nombre }}</td>
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
                    <td class="fw-bold text-center" width="18%">DESINSECTACION<br>APLICACIÓN X<br>NEBULIZACION Y/O<br>TERMONEBULIZACION</td>
                    <td></td><td></td><td></td><td></td><td></td><td class="text-red">0.00</td>
                </tr>
            @endif
            <tr>
                <td colspan="7" class="text-right border-0" style="border: none;"></td>
                <td class="text-red">0.00</td>
            </tr>

            <tr class="bg-cyan fw-bold">
                <td colspan="8">DESINFECCION</td>
            </tr>
            <tr>
                <td rowspan="2" class="fw-bold">2</td>
                <td rowspan="2"></td>
                <td></td><td></td><td></td><td></td><td></td><td class="text-red">0.00</td>
            </tr>
            <tr>
                <td></td><td></td><td></td><td></td><td></td><td class="text-red">0.00</td>
            </tr>

            <tr class="bg-cyan fw-bold">
                <td colspan="8">DESRATIZACION</td>
            </tr>
            <tr>
                <td rowspan="3" class="fw-bold">3</td>
                <td rowspan="3"></td>
                <td class="text-left">CEBADERO COMUN (TUBO PVC 4")</td>
                <td></td><td></td><td></td><td></td><td class="text-red">0.00</td>
            </tr>
            <tr>
                <td class="text-left">CAJA CEBADERA</td>
                <td></td><td></td><td></td><td></td><td class="text-red">0.00</td>
            </tr>
            <tr>
                <td class="text-left">TRAMPAS ADHESIVAS</td>
                <td></td><td></td><td></td><td></td><td class="text-red">0.00</td>
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
                    $tipoDocCliente = strlen($cotizacion->cliente->documento) == 11 ? '6' : '1'; 
                    $numDocCliente = $cotizacion->cliente->documento;

                    // Cadena oficial de SUNAT
                    $sunatString = "{$rucEmisor}|{$tipoDoc}|{$serie}|{$numero}|{$igvFormatted}|{$totalFormatted}|{$fecha}|{$tipoDocCliente}|{$numDocCliente}|";
                    $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=" . urlencode($sunatString);

                    // TRUCO MAESTRO: Forzamos a PHP a ignorar la falta de certificados SSL en tu XAMPP local
                    $contextoSsl = stream_context_create([
                        "ssl" => [
                            "verify_peer" => false,
                            "verify_peer_name" => false,
                        ],
                    ]);

                    // Descargamos el QR de internet y lo transformamos a Base64
                    try {
                        $qrContenido = file_get_contents($qrUrl, false, $contextoSsl);
                        $qrBase64 = "data:image/png;base64," . base64_encode($qrContenido);
                    } catch (\Exception $e) {
                        $qrBase64 = null; 
                    }
                @endphp

                <table style="border: none; width: 100%;">
                    <tr>
                        <td width="80px" style="border: none; padding: 0; vertical-align: middle;">
                            @if($qrBase64)
                                <img src="{{ $qrBase64 }}" alt="QR SUNAT" style="width: 70px; height: 70px; border: 1px solid #000; padding: 2px;">
                            @else
                                <div style="width: 70px; height: 70px; border: 1px solid #000; text-align: center; font-size: 8px; padding-top: 25px;">[QR ERROR]</div>
                            @endif
                        </td>
                        <td style="border: none; padding-left: 10px; vertical-align: middle; font-size: 7.5px; color: #444; font-style: italic; line-height: 1.2;">
                            Representación impresa de la Cotización Comercial.<br>
                            Simulación de validación con firma digital y estándar de Facturación Electrónica SUNAT.
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
            <td>{{ $cotizacion->cliente->nombre_razon_social }}</td>
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