<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion;
use App\Models\OrdenServicio;
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
        $productos = \App\Models\Producto::where('stock', '>', 0)->get();

        // Traemos a los técnicos para asignarlos (Asumiendo que tienes un campo 'rol' o similar)
        $tecnicos = User::where('rol', 'tecnico')->get();

        return view('admin.ordenes.index', compact('ordenes', 'cotizaciones', 'tecnicos', 'productos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cotizacion_id' => 'required|exists:cotizaciones,id',
            'tecnico_id' => 'required|exists:users,id',
            'fecha_programada' => 'required|date',
            'hora_programada' => 'required',
            'trabajos_autorizados_por' => 'nullable|string|max:255',
            'pedido_recibido_por' => 'nullable|string|max:255',
            'trabajo_descripcion' => 'nullable|string',
            'comentarios_adicionales' => 'nullable|string',
            'descuento' => 'nullable|numeric|min:0',
            'producto_id' => 'nullable|exists:productos,id',
            'cantidad_usada' => 'nullable|numeric|min:1',
        ]);

        $orden = OrdenServicio::create([
            ...$validated,
            'estado' => 'Pendiente',
            'descuento' => $validated['descuento'] ?? 0,
        ]);

        if ($request->filled('producto_id') && $request->filled('cantidad_usada')) {
            $producto = \App\Models\Producto::find($request->producto_id);
            if ($producto) {
                $notaInventario = "\n[SISTEMA]: Se utilizaron " . $request->cantidad_usada . " " . $producto->unidad_medida . " de " . $producto->nombre;
                if (!str_contains($orden->comentarios_adicionales ?? '', $notaInventario)) {
                    $orden->comentarios_adicionales = ($orden->comentarios_adicionales ?? '') . $notaInventario;
                    $orden->save();
                }
            }
        }

        return redirect()->back()->with('success', 'Orden de Servicio programada correctamente.');
    }

    public function update(Request $request, $id)
    {
        $orden = OrdenServicio::findOrFail($id);
        
        // Guardamos el estado actual ANTES de actualizar para evitar descontar doble
        $estadoAnterior = $orden->estado;

        $validated = $request->validate([
            'tecnico_id' => 'required|exists:users,id',
            'fecha_programada' => 'required|date',
            'hora_programada' => 'required',
            'estado' => 'required|in:Pendiente,En Ruta,Completada',
            'trabajos_autorizados_por' => 'nullable|string|max:255',
            'pedido_recibido_por' => 'nullable|string|max:255',
            'trabajo_descripcion' => 'nullable|string',
            'comentarios_adicionales' => 'nullable|string',
            'descuento' => 'nullable|numeric|min:0',
            'fecha_servicio_ejecutado' => 'nullable|date',
            // Añadimos validación para los productos que vienen del formulario (opcional)
            'productos_usados' => 'nullable|array', 
            'producto_id' => 'nullable|exists:productos,id',
            'cantidad_usada' => 'nullable|numeric|min:1',
        ]);

        if ($validated['estado'] === 'Completada' && empty($validated['fecha_servicio_ejecutado'])) {
            $validated['fecha_servicio_ejecutado'] = now()->toDateString();
        }

        $orden->update([
            'tecnico_id' => $validated['tecnico_id'],
            'fecha_programada' => $validated['fecha_programada'],
            'hora_programada' => $validated['hora_programada'],
            'estado' => $validated['estado'],
            'trabajos_autorizados_por' => $validated['trabajos_autorizados_por'],
            'pedido_recibido_por' => $validated['pedido_recibido_por'],
            'trabajo_descripcion' => $validated['trabajo_descripcion'],
            'comentarios_adicionales' => $validated['comentarios_adicionales'],
            'descuento' => $validated['descuento'] ?? 0,
            'fecha_servicio_ejecutado' => $validated['fecha_servicio_ejecutado'],
        ]);

       // === MAGIA DEL INVENTARIO (Ruta Rápida) ===
        if ($orden->estado === 'Completada' && $estadoAnterior !== 'Completada') {
            if ($request->filled('producto_id') && $request->filled('cantidad_usada')) {
                $producto = \App\Models\Producto::find($request->producto_id);
                
                if ($producto && $producto->stock >= $request->cantidad_usada) {
                    // 1. Descontamos el stock de la base de datos
                    $producto->stock -= $request->cantidad_usada;
                    $producto->save();
                    
                    // 2. Dejamos una nota automática en los comentarios para que salga en el PDF
                    $notaInventario = "\n[SISTEMA]: Se utilizaron " . $request->cantidad_usada . " " . $producto->unidad_medida . " de " . $producto->nombre;
                    $orden->comentarios_adicionales = $orden->comentarios_adicionales . $notaInventario;
                    $orden->save();
                }
            }
        }

        return redirect()->back()->with('success', 'Orden actualizada y stock modificado exitosamente.');
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

        $pdf = app('dompdf.wrapper')
            ->loadView('admin.ordenes.comprobante_pdf', compact('orden', 'tipo_comprobante'))
            ->setOptions(['isRemoteEnabled' => true]);

        return $pdf->download("Comprobante-E001-000{$orden->id}.pdf");
    }
}
