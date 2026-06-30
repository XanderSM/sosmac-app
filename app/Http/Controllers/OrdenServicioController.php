<?php

namespace App\Http\Controllers;

use App\Models\OrdenServicio;
use App\Models\Cotizacion;
use App\Models\User;
use Illuminate\Http\Request;

class OrdenServicioController extends Controller
{
    public function index()
    {
        // Traemos las órdenes con su cotización, cliente y técnico asignado
        $ordenes = OrdenServicio::with(['cotizacion.cliente', 'tecnico'])
            ->orderBy('id', 'desc')
            ->get();

        // Para el modal de Nueva Orden: Solo cotizaciones APROBADAS que NO tengan orden aún
        $cotizaciones = Cotizacion::where('estado', 'Aprobada')->doesntHave('orden')->get();
        
        // Traemos a los técnicos para asignarlos (Asumiendo que tienes un campo 'rol' o similar)
        $tecnicos = User::where('rol', 'tecnico')->get();

        return view('admin.ordenes.index', compact('ordenes', 'cotizaciones', 'tecnicos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cotizacion_id' => 'required|exists:cotizaciones,id',
            'tecnico_id' => 'required|exists:users,id',
            'fecha_programada' => 'required|date',
            'hora_programada' => 'required',
            'observaciones_admin' => 'nullable|string'
        ]);

        OrdenServicio::create([
            'cotizacion_id' => $request->cotizacion_id,
            'tecnico_id' => $request->tecnico_id,
            'fecha_programada' => $request->fecha_programada,
            'hora_programada' => $request->hora_programada,
            'estado' => 'Pendiente',
            'observaciones_admin' => $request->observaciones_admin
        ]);

        return redirect()->back()->with('success', 'Orden de Servicio programada correctamente.');
    }

    public function update(Request $request, $id)
    {
        $orden = OrdenServicio::findOrFail($id);
        
        $request->validate([
            'tecnico_id' => 'required|exists:users,id',
            'fecha_programada' => 'required|date',
            'hora_programada' => 'required',
            'estado' => 'required|in:Pendiente,En Ruta,Completada'
        ]);

        $orden->update($request->all());

        return redirect()->back()->with('success', 'Orden actualizada.');
    }

    public function destroy($id)
    {
        OrdenServicio::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Orden eliminada.');
    }

    // Generar PDF de la Orden de Servicio
    public function descargarOrdenPdf($id)
    {
        $orden = OrdenServicio::with(['cotizacion.cliente', 'cotizacion.detalles.servicio', 'tecnico'])->findOrFail($id);
        
        $pdf = app('dompdf.wrapper')->loadView('admin.ordenes.orden_pdf', compact('orden'));
        return $pdf->download("Orden-Servicio-E001-000{$orden->id}.pdf");
    }

    // Generar PDF del Comprobante (Boleta o Factura Inteligente)
    public function descargarComprobantePdf($id)
    {
        $orden = OrdenServicio::with(['cotizacion.cliente', 'cotizacion.detalles.servicio'])->findOrFail($id);
        
        // Lógica inteligente: Si es Empresa (RUC) es Factura, si es Persona (DNI) es Boleta
        $tipo_comprobante = ($orden->cotizacion->cliente->tipo_documento == 'RUC') ? 'FACTURA' : 'BOLETA DE VENTA';
        
        $pdf = app('dompdf.wrapper')->loadView('admin.ordenes.comprobante_pdf', compact('orden', 'tipo_comprobante'));
        return $pdf->download("Comprobante-E001-000{$orden->id}.pdf");
    }
}