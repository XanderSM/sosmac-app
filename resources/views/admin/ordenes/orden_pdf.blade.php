<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Orden de Servicio</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #000; margin: -15px 18px 0 18px; padding: 0; }
        table { border-collapse: collapse; width: 100%; }
        .w-100 { width: 100%; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .fw-bold { font-weight: bold; }
        .valign-top { vertical-align: top; }
        .valign-middle { vertical-align: middle; }

        .bg-label { background-color: #e7e6e6; }
        .bg-header { background-color: #b8cce4; color: #000; }

        .border-all td, .border-all th { border: 1px solid #000; padding: 4px 6px; }
        .border-box { border: 1px solid #000; }

        .company-name { font-size: 14px; font-weight: bold; margin-bottom: 6px; letter-spacing: 0.3px; }
        .company-info { font-size: 10px; line-height: 1.55; }
        .company-email { color: #0563c1; font-size: 10px; }

        .header-company { padding: 6px 8px 6px 14px; vertical-align: middle; }
        .header-brand { padding: 4px 6px 4px 0; vertical-align: middle; text-align: right; }

        .doc-title { font-size: 24px; font-weight: bold; color: #808080; letter-spacing: 1.5px; margin-top: 0px; }

        .section-label { background-color: #e7e6e6; border: 1px solid #000; border-bottom: none; padding: 4px 6px; font-weight: bold; }
        .section-content { border: 1px solid #000; padding: 6px; min-height: 36px; }

        .signature-box { border: 1px solid #000; min-height: 70px; }
        .signature-header { background-color: #b8cce4; border-bottom: 1px solid #000; padding: 4px 6px; font-weight: bold; text-align: center; font-size: 8px; }

        .totals-table td { border: 1px solid #000; padding: 4px 6px; }
        .totals-label { background-color: #e7e6e6; font-weight: bold; width: 55%; }
    </style>
</head>
<body>

@php
    $logoPath = public_path('img/logo.png');
    $logoBase64 = file_exists($logoPath) ? base64_encode(file_get_contents($logoPath)) : null;
    $numeroOrden = 'E001-' . str_pad($orden->id, 4, '0', STR_PAD_LEFT);
    $cliente = $orden->cotizacion->cliente;

    $subtotalServicios = $orden->cotizacion->detalles->sum('subtotal');
    $materialTotal = 0;
    $productosUsados = [];

    if (!empty($orden->comentarios_adicionales)) {
        preg_match_all('/\[SISTEMA\]: Se utilizaron (\d+) ([^\s]+) de ([^\n]+)/', $orden->comentarios_adicionales, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $productosUsados[] = [
                'cantidad' => (int) $match[1],
                'unidad' => $match[2],
                'nombre' => trim($match[3]),
            ];
        }
    }

    $costoDirecto = $subtotalServicios + $materialTotal;
    $descuento = (float) ($orden->descuento ?? 0);
    $subTotalFinal = $costoDirecto - $descuento;
    $igvFinal = $subTotalFinal * 0.18;
    $totalFinal = $subTotalFinal + $igvFinal;

    $fechaPedido = $orden->cotizacion->created_at->format('Y-m-d');
    $fechaPrevista = $orden->fecha_programada
        ? \Carbon\Carbon::parse($orden->fecha_programada)->format('Y-m-d')
        : '—';
    $fechaEjecutado = $orden->fecha_servicio_ejecutado
        ? \Carbon\Carbon::parse($orden->fecha_servicio_ejecutado)->format('Y-m-d')
        : '—';
@endphp

{{-- Cabecera --}}
<table class="w-100" style="margin-bottom: 5px;">
    <tr>
        <td width="55%" class="header-company text-left">
            <div class="company-name">SOSMAC SERVICIOS GENERALES SRL</div>
            <div class="company-info">
                CALLE LOS ESTAMBRES Q5- LOTE 6 - URB. SAN ANDRES - TRUJILLO<br>
                JR. CINCO ESQUINAS N° 1555 - CAJAMARCA<br>
                956134064<br>
                <span class="company-email">sosmac.srl@gmail.com</span>
            </div>
        </td>
        <td width="45%" class="header-brand">
            @if($logoBase64)
                <img src="data:image/png;base64,{{ $logoBase64 }}" alt="SOSMAC" style="width: 145px; height: auto; display: block; margin-left: auto;">
            @endif
            <div class="doc-title">ORDEN DE SERVICIO</div>
        </td>
    </tr>
</table>

{{-- Datos del cliente y orden --}}
<table class="border-all w-100" style="margin-bottom: 6px;">
    <tr>
        <td class="bg-label fw-bold" width="14%">CLIENTE</td>
        <td width="36%">{{ $cliente->nombre_razon_social }}</td>
        <td class="bg-label fw-bold" width="14%">N° DE OS</td>
        <td width="36%">{{ $numeroOrden }}</td>
    </tr>
    <tr>
        <td class="bg-label fw-bold">TELÉFONO</td>
        <td>{{ $cliente->telefono ?? '—' }}</td>
        <td class="bg-label fw-bold">ID DE CLIENTE</td>
        <td>{{ $cliente->documento ?? '—' }}</td>
    </tr>
    <tr>
        <td class="bg-label fw-bold">EMAIL</td>
        <td>{{ $cliente->email ?? '—' }}</td>
        <td class="bg-label fw-bold">PEDIDO RECIBIDO POR</td>
        <td>{{ strtoupper($orden->pedido_recibido_por ?? '—') }}</td>
    </tr>
</table>

<table class="border-all w-100" style="margin-bottom: 6px;">
    <tr class="bg-label text-center fw-bold">
        <td width="33%">FECHA DEL PEDIDO</td>
        <td width="34%">FECHA PREVISTA DE INICIO</td>
        <td width="33%">FECHA DEL SERV. EJECUTADO</td>
    </tr>
    <tr class="text-center">
        <td>{{ $fechaPedido }}</td>
        <td>{{ $fechaPrevista }}</td>
        <td>{{ $fechaEjecutado }}</td>
    </tr>
</table>

<table class="border-all w-100" style="margin-bottom: 6px;">
    <tr>
        <td class="bg-label fw-bold" width="25%">TRABAJOS AUTORIZADOS POR</td>
        <td width="35%">{{ strtoupper($orden->trabajos_autorizados_por ?? '—') }}</td>
        <td class="bg-label fw-bold text-center" width="10%">FIRMA</td>
        <td width="30%" style="height: 32px;"></td>
    </tr>
</table>

{{-- Descripción del trabajo --}}
<div class="section-label">TRABAJO DESCRIPCIÓN:</div>
<div class="section-content" style="margin-bottom: 6px;">
    {{ $orden->trabajo_descripcion ?? $orden->cotizacion->notas_areas ?? '—' }}
</div>

<div class="section-label">COMENTARIOS ADICIONALES:</div>
<div class="section-content" style="margin-bottom: 8px;">
    {{ $orden->comentarios_adicionales ?? '—' }}
</div>

{{-- Tabla de servicios --}}
<table class="border-all w-100" style="margin-bottom: 6px;">
    <tr class="bg-label text-center fw-bold">
        <td width="55%">DESCRIPCIÓN DEL SERVICIO</td>
        <td width="15%">REFER. M2</td>
        <td width="15%">PRECIO</td>
        <td width="15%">IMPORTE</td>
    </tr>
    @forelse($orden->cotizacion->detalles as $detalle)
    <tr>
        <td>{{ strtoupper($detalle->servicio->nombre ?? 'SERVICIO') }}</td>
        <td class="text-center">{{ number_format($detalle->cantidad, 2) }}</td>
        <td class="text-right">S/ {{ number_format($detalle->precio_unitario, 2) }}</td>
        <td class="text-right">S/ {{ number_format($detalle->subtotal, 2) }}</td>
    </tr>
    @empty
    <tr>
        <td>—</td>
        <td class="text-center">0.00</td>
        <td class="text-right">S/ 0.00</td>
        <td class="text-right">S/ 0.00</td>
    </tr>
    @endforelse
    <tr>
        <td colspan="2" style="border-left: none; border-bottom: none;"></td>
        <td class="bg-label fw-bold text-right">SUB TOTAL</td>
        <td class="text-right fw-bold">S/ {{ number_format($subtotalServicios, 2) }}</td>
    </tr>
</table>

{{-- Tabla de productos/insumos usados --}}
<table class="border-all w-100" style="margin-bottom: 8px;">
    <tr class="bg-label text-center fw-bold">
        <td width="60%">PRODUCTOS / INSUMOS UTILIZADOS</td>
        <td width="20%">CANTIDAD</td>
        <td width="20%">UNIDAD</td>
    </tr>
    @if(count($productosUsados) > 0)
        @foreach($productosUsados as $producto)
        <tr>
            <td>{{ $producto['nombre'] }}</td>
            <td class="text-center">{{ $producto['cantidad'] }}</td>
            <td class="text-center">{{ strtoupper($producto['unidad']) }}</td>
        </tr>
        @endforeach
    @else
        <tr>
            <td colspan="3">No se registraron productos o insumos utilizados en esta orden.</td>
        </tr>
    @endif
</table>

{{-- Pie: técnico, conformidad y totales --}}
<table class="w-100">
    <tr>
        <td width="58%" class="valign-top" style="padding-right: 8px;">
            <table class="w-100" style="margin-bottom: 6px;">
                <tr>
                    <td class="bg-label fw-bold" width="35%">TECNICO DESIGNADO</td>
                    <td class="border-box">{{ strtoupper($orden->tecnico->name ?? '—') }}</td>
                </tr>
            </table>
            <div class="signature-box">
                <div class="signature-header">CONFORMIDAD DE SERVICIO</div>
                <div style="padding: 8px; font-size: 8px; text-align: center; color: #555;">
                    
                </div>
            </div>
        </td>
        <td width="42%" class="valign-top">
            <table class="totals-table w-100">
                <tr>
                    <td class="totals-label">COSTO DIRECTO</td>
                    <td class="text-right">S/ {{ number_format($costoDirecto, 2) }}</td>
                </tr>
                <tr>
                    <td class="totals-label">DESCUENTO</td>
                    <td class="text-right">S/ {{ number_format($descuento, 2) }}</td>
                </tr>
                <tr>
                    <td class="totals-label">SUB TOTAL</td>
                    <td class="text-right">S/ {{ number_format($subTotalFinal, 2) }}</td>
                </tr>
                <tr>
                    <td class="totals-label">IGV 18%</td>
                    <td class="text-right">S/ {{ number_format($igvFinal, 2) }}</td>
                </tr>
                <tr>
                    <td class="totals-label" style="font-size: 11px;">TOTAL</td>
                    <td class="text-right fw-bold" style="font-size: 11px;">S/ {{ number_format($totalFinal, 2) }}</td>
                </tr>
            </table>
        </td>
    </tr>
</table>

</body>
</html>
