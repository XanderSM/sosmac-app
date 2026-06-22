<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion;
use App\Models\CotizacionDetalle;
use App\Models\Cliente;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class CotizacionController extends Controller
{
    // LEER: Mostrar la lista de cotizaciones con buscador
    public function index(Request $request)
    {
        $buscar = $request->get('buscar');
        
        $cotizaciones = Cotizacion::with('cliente')
            ->orderBy('created_at', 'desc')
            ->when($buscar, function ($query, $buscar) {
                return $query->whereHas('cliente', function($q) use ($buscar) {
                    $q->where('nombre_razon_social', 'like', "%{$buscar}%")
                      ->orWhere('documento', 'like', "%{$buscar}%");
                });
            })
            ->get();
            
        return view('admin.cotizaciones.index', compact('cotizaciones', 'buscar'));
    }

    // VISTA CREAR: Formulario para nueva cotización
    public function create()
    {
        // Solo traemos clientes y servicios activos
        $clientes = Cliente::where('estado', true)->orderBy('nombre_razon_social', 'asc')->get();
        $servicios = Servicio::where('estado', true)->orderBy('nombre', 'asc')->get();
        
        return view('admin.cotizaciones.create', compact('clientes', 'servicios'));
    }

    // GUARDAR: Procesar y calcular la nueva cotización
    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'servicios' => 'required|array|min:1',
            'servicios.*.id' => 'required|exists:servicios,id',
            'servicios.*.cantidad' => 'required|integer|min:1', // Cero negativos ni nulos
            'servicios.*.precio' => 'required|numeric|min:0' // Cero negativos
        ]);

        // 1. Recalcular todo en el backend por seguridad (RN-FC-03)
        $subtotal = 0;
        foreach ($request->servicios as $item) {
            $subtotal += ($item['cantidad'] * $item['precio']);
        }
        
        $igv = $subtotal * 0.18; // 18% de IGV
        $total = $subtotal + $igv;

        // 2. Crear la cabecera
        $cotizacion = Cotizacion::create([
            'cliente_id' => $request->cliente_id,
            'titulo_proyecto' => $request->titulo_proyecto,
            'direccion_proyecto' => $request->direccion_proyecto,
            'notas_areas' => $request->notas_areas,
            'notas_materiales' => $request->notas_materiales,
            'subtotal' => $subtotal,
            'igv' => $igv,
            'total' => $total,
            'estado' => 'Pendiente'
        ]);

        // 3. Crear los detalles (los servicios incluidos)
        foreach ($request->servicios as $item) {
            CotizacionDetalle::create([
                'cotizacion_id' => $cotizacion->id,
                'servicio_id' => $item['id'],
                'cantidad' => $item['cantidad'],
                'precio_unitario' => $item['precio'],
                'subtotal' => ($item['cantidad'] * $item['precio'])
            ]);
        }

        return redirect()->route('cotizaciones.index')->with('success', 'Cotización generada correctamente.');
    }

    // ACTUALIZAR ESTADO (Para enlazar luego con Órdenes de Servicio)
    public function cambiarEstado(Request $request, $id)
    {
        $request->validate(['estado' => 'required|in:Pendiente,Aprobada,Rechazada']);
        $cotizacion = Cotizacion::findOrFail($id);
        $cotizacion->update(['estado' => $request->estado]);

        return redirect()->back()->with('success', 'Estado de la cotización actualizado.');
    }

    // ELIMINAR
    public function destroy($id)
    {
        $cotizacion = Cotizacion::findOrFail($id);
        $cotizacion->delete(); // Elimina en cascada los detalles gracias a la migración
        return redirect()->back()->with('success', 'Cotización eliminada.');
    }

    // EXPORTAR PDF
    public function descargarPdf($id)
    {
        $cotizacion = Cotizacion::with(['cliente', 'detalles.servicio'])->findOrFail($id);
        
        // Usamos el wrapper directo de la aplicación para evitar errores de clase no encontrada
        $pdf = app('dompdf.wrapper')->loadView('admin.cotizaciones.pdf', compact('cotizacion'));
        
        return $pdf->download("Cotizacion-ProFund-00{$cotizacion->id}.pdf");
    }
}