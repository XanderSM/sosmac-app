<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion;
use App\Models\Cliente;
use App\Models\OrdenServicio;
use App\Models\Producto; // Para el reporte de inventario
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class DashboardController extends Controller
{
    public function index()
    {
        // Métricas superiores (Tarjetas)
        $ingresos = Cotizacion::where('estado', 'Aprobada')->sum('total');
        $cotizacionesPendientes = Cotizacion::where('estado', 'Pendiente')->count();
        $totalCotizaciones = Cotizacion::count();
        $totalClientes = Cliente::count();

        // Data para los gráficos interactivos (Órdenes)
        $ordenesPendientes = OrdenServicio::where('estado', 'Pendiente')->count();
        $ordenesEnRuta = OrdenServicio::where('estado', 'En Ruta')->count();
        $ordenesCompletadas = OrdenServicio::where('estado', 'Completada')->count();

        return view('admin.dashboard', compact(
            'ingresos', 'cotizacionesPendientes', 'totalCotizaciones', 'totalClientes',
            'ordenesPendientes', 'ordenesEnRuta', 'ordenesCompletadas'
        ));
    }

    public function exportarReporte(Request $request)
    {
        $tipo = $request->input('tipo_reporte');
        $rango = $request->input('rango');
        
        $now = Carbon::now();
        $fechaInicio = $now->copy();
        $fechaFin = $now->copy();

        // 1. Determinar el rango de fechas
        if ($rango == 'diario') {
            $fechaInicio = $now->startOfDay();
            $fechaFin = $now->endOfDay();
            $periodo = "Diario (" . $now->format('d/m/Y') . ")";
        } elseif ($rango == 'mensual') {
            $fechaInicio = $now->startOfMonth();
            $fechaFin = $now->endOfMonth();
            $periodo = "Mensual (" . $now->format('F Y') . ")";
        } elseif ($rango == 'semestral') {
            $fechaInicio = $now->subMonths(6)->startOfDay();
            $fechaFin = Carbon::now()->endOfDay();
            $periodo = "Últimos 6 meses";
        } elseif ($rango == 'personalizado') {
            $fechaInicio = Carbon::parse($request->input('fecha_inicio'))->startOfDay();
            $fechaFin = Carbon::parse($request->input('fecha_fin'))->endOfDay();
            $periodo = "Del " . $fechaInicio->format('d/m/Y') . " al " . $fechaFin->format('d/m/Y');
        }

        // 2. Extraer la data según el tipo de reporte
        if ($tipo == 'ingresos') {
            $tituloReporte = "Reporte de Ingresos (Cotizaciones)";
            $data = Cotizacion::with('cliente')
                        ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                        ->get();
        } elseif ($tipo == 'operaciones') {
            $tituloReporte = "Reporte Operativo (Órdenes de Servicio)";
            $data = OrdenServicio::with(['cotizacion.cliente', 'tecnico'])
                        ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                        ->get();
        } else {
            $tituloReporte = "Reporte de Estado de Inventario";
            $data = Producto::all(); // El inventario es un estado actual, no depende tanto del rango
            $periodo = "Al " . date('d/m/Y');
        }

        // 3. Generar el PDF
        $pdf = Pdf::loadView('admin.reportes.pdf', compact('data', 'tipo', 'tituloReporte', 'periodo'));
        return $pdf->stream('Reporte_SOSMAC_' . date('Ymd_His') . '.pdf');
    }
}