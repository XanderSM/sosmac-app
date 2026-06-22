<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $tituloReporte }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; color: #333; }
        .header { width: 100%; border-bottom: 2px solid #11235A; padding-bottom: 10px; margin-bottom: 20px; }
        .logo { width: 120px; height: auto; }
        .title-box { text-align: right; }
        .title { color: #11235A; font-size: 18px; font-weight: bold; margin: 0; text-transform: uppercase; }
        .subtitle { color: #666; font-size: 12px; margin: 5px 0 0 0; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { background-color: #11235A; color: white; padding: 8px; text-align: left; font-size: 10px; text-transform: uppercase; }
        td { border-bottom: 1px solid #ddd; padding: 8px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .fw-bold { font-weight: bold; }
        .badge { padding: 3px 6px; border-radius: 4px; font-size: 9px; color: white; }
        .bg-success { background-color: #10b981; }
        .bg-warning { background-color: #f59e0b; }
        .bg-primary { background-color: #3b82f6; }
    </style>
</head>
<body>

    <table class="header">
        <tr>
            <td width="50%">
                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/logo.png'))) }}" class="logo" alt="SOSMAC">
            </td>
            <td width="50%" class="title-box">
                <h1 class="title">{{ $tituloReporte }}</h1>
                <p class="subtitle">Periodo analizado: <strong>{{ $periodo }}</strong></p>
                <p class="subtitle">Fecha de emisión: {{ date('d/m/Y H:i') }}</p>
            </td>
        </tr>
    </table>

    @if($tipo == 'ingresos')
        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Cliente / Proyecto</th>
                    <th>Estado</th>
                    <th class="text-right">Total (S/)</th>
                </tr>
            </thead>
            <tbody>
                @php $sumaTotal = 0; @endphp
                @forelse($data as $cotizacion)
                    <tr>
                        <td>{{ $cotizacion->created_at->format('d/m/Y') }}</td>
                        <td>{{ $cotizacion->cliente->nombre_razon_social ?? 'S/D' }}</td>
                        <td>
                            <span style="color: {{ $cotizacion->estado == 'Aprobada' ? '#10b981' : '#f59e0b' }}; font-weight: bold;">
                                {{ strtoupper($cotizacion->estado) }}
                            </span>
                        </td>
                        <td class="text-right">{{ number_format($cotizacion->total, 2) }}</td>
                    </tr>
                    @php if($cotizacion->estado == 'Aprobada') $sumaTotal += $cotizacion->total; @endphp
                @empty
                    <tr><td colspan="4" class="text-center">No hay registros en este periodo.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div style="text-align: right; margin-top: 15px; font-size: 14px;">
            <strong>Total Ingresos Aprobados: <span style="color: #11235A;">S/ {{ number_format($sumaTotal, 2) }}</span></strong>
        </div>

    @elseif($tipo == 'operaciones')
        <table>
            <thead>
                <tr>
                    <th>OS ID</th>
                    <th>Fecha Prog.</th>
                    <th>Técnico Asignado</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $orden)
                    <tr>
                        <td class="fw-bold">OS-{{ str_pad($orden->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td>{{ \Carbon\Carbon::parse($orden->fecha_programada)->format('d/m/Y') }}</td>
                        <td>{{ $orden->tecnico->name ?? 'Sin asignar' }}</td>
                        <td class="fw-bold">{{ strtoupper($orden->estado) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center">No hay operaciones en este periodo.</td></tr>
                @endforelse
            </tbody>
        </table>

    @else
        <table>
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Producto / Insumo</th>
                    <th>Unidad</th>
                    <th class="text-right">Stock Actual</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $producto)
                    <tr>
                        <td class="fw-bold">{{ $producto->codigo }}</td>
                        <td>{{ $producto->nombre }}</td>
                        <td>{{ $producto->unidad_medida }}</td>
                        <td class="text-right fw-bold" style="color: {{ $producto->stock < 5 ? 'red' : 'black' }}">{{ $producto->stock }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center">No hay productos registrados.</td></tr>
                @endforelse
            </tbody>
        </table>
    @endif

</body>
</html>