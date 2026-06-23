<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion;
use App\Models\CotizacionDetalle;
use App\Models\Cliente;
use App\Models\Servicio;
use Illuminate\Http\Request;

class CotizacionController extends Controller
{
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

    public function create()
    {
        return redirect()->route('cotizaciones.index'); // La creación es vía Modal en el Index
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'titulo_proyecto' => 'required|string',
            'direccion_proyecto' => 'required|string',
            'servicios' => 'required|array|min:1',
            'servicios.*.id' => 'required|exists:servicios,id',
            'servicios.*.aplic' => 'required|numeric|min:1',
            'servicios.*.serv' => 'required|numeric|min:1',
            'servicios.*.precio' => 'required|numeric|min:0'
        ]);

        $subtotal = 0;
        foreach ($request->servicios as $item) {
            $cantidadTotal = $item['aplic'] * $item['serv'];
            $subtotal += ($cantidadTotal * $item['precio']);
        }
        
        $igv = $subtotal * 0.18;
        $total = $subtotal + $igv;

        $cotizacion = Cotizacion::create([
            'cliente_id' => $request->cliente_id,
            'titulo_proyecto' => $request->titulo_proyecto,
            'direccion_proyecto' => $request->direccion_proyecto,
            'notas_areas' => $request->notas_areas ?? null,
            'notas_materiales' => $request->notas_materiales ?? null,
            'subtotal' => $subtotal,
            'igv' => $igv,
            'total' => $total,
            'estado' => 'Pendiente'
        ]);

        foreach ($request->servicios as $item) {
            $cantidadTotal = $item['aplic'] * $item['serv'];
            CotizacionDetalle::create([
                'cotizacion_id' => $cotizacion->id,
                'servicio_id' => $item['id'],
                'cantidad' => $cantidadTotal,
                'precio_unitario' => $item['precio'],
                'subtotal' => ($cantidadTotal * $item['precio'])
            ]);
        }

        return redirect()->route('cotizaciones.index')->with('success', 'Cotización generada correctamente.');
    }

    public function cambiarEstado(Request $request, $id)
    {
        $request->validate(['estado' => 'required|in:Pendiente,Aprobada,Rechazada']);
        $cotizacion = Cotizacion::findOrFail($id);
        $cotizacion->update(['estado' => $request->estado]);
        return redirect()->back()->with('success', 'Estado actualizado.');
    }

    public function destroy($id)
    {
        $cotizacion = Cotizacion::findOrFail($id);
        
        if ($cotizacion->estado !== 'Rechazada') {
            return redirect()->back()->withErrors('Solo puedes eliminar cotizaciones Rechazadas.');
        }

        $cotizacion->delete();
        return redirect()->back()->with('success', 'Cotización eliminada.');
    }

    public function descargarPdf($id)
    {
        $cotizacion = Cotizacion::with(['cliente', 'detalles.servicio'])->findOrFail($id);
        $pdf = app('dompdf.wrapper')->loadView('admin.cotizaciones.pdf', compact('cotizacion'));
        return $pdf->download("Cotizacion-SOSMAC-00{$cotizacion->id}.pdf");
    }
}