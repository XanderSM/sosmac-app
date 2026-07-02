<?php

namespace App\Http\Controllers;

use App\Models\OrdenServicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TecnicoController extends Controller
{
    public function index()
    {
        // Traemos las órdenes que pertenecen ÚNICAMENTE al técnico que ha iniciado sesión
        $ordenes = OrdenServicio::with(['cotizacion.cliente', 'cotizacion.detalles.servicio'])
            ->where('tecnico_id', Auth::id())
            ->orderBy('fecha_programada', 'asc')
            ->orderBy('hora_programada', 'asc')
            ->get();

        $productos = \App\Models\Producto::where('stock', '>', 0)->get();

        // Enviamos la data a la vista "portal"
        return view('tecnico.portal', compact('ordenes', 'productos'));
    }

    public function actualizarEstado(Request $request, $id)
    {
        // Validamos que el técnico solo modifique SUS propias órdenes
        $orden = OrdenServicio::where('tecnico_id', Auth::id())->findOrFail($id);

        $estadoAnterior = $orden->estado;

        // Verificamos si el formulario manda el estado 'Completada'
        if ($request->input('estado') === 'Completada') {
            
            // Anexamos el comentario del técnico sin borrar el texto que ya existía
            $nuevoComentario = "\n[REPORTE TÉCNICO " . now()->format('d/m/Y') . "]: " . $request->input('comentarios_adicionales');
            
            $orden->update([
                'estado' => 'Completada',
                'comentarios_adicionales' => ($orden->comentarios_adicionales ?? '') . $nuevoComentario,
                'fecha_servicio_ejecutado' => now()->toDateString()
            ]);
            
        } else {
            // Si solo fuera un botón de "Iniciar ruta"
            $orden->update(['estado' => 'En Ruta']);
        }

        // Lógica de inventario (solo si acaba de pasar a Completada)
        if ($orden->estado === 'Completada' && $estadoAnterior !== 'Completada') {
            if ($request->filled('producto_id') && $request->filled('cantidad_usada')) {
                $producto = \App\Models\Producto::find($request->producto_id);

                if ($producto && $producto->stock >= $request->cantidad_usada) {
                    $producto->stock -= $request->cantidad_usada;
                    $producto->save();

                    $notaInventario = "\n[SISTEMA]: Se utilizaron " . $request->cantidad_usada . " " . $producto->unidad_medida . " de " . $producto->nombre;
                    // Guardamos la nota del sistema
                    $orden->comentarios_adicionales = $orden->comentarios_adicionales . $notaInventario;
                    $orden->save();
                }
            }
        }

        return redirect()->back()->with('success', 'El estado del servicio ha sido actualizado y finalizado con éxito.');
    }
}