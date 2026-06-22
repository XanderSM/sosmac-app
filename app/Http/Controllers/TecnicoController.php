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

        // Enviamos la data a la vista "portal"
        return view('tecnico.portal', compact('ordenes'));
    }

    public function actualizarEstado(Request $request, $id)
    {
        // Validamos que el técnico solo modifique SUS propias órdenes
        $orden = OrdenServicio::where('tecnico_id', Auth::id())->findOrFail($id);
        
        // Si el formulario envía observaciones, significa que está finalizando el trabajo
        if ($request->has('observaciones_tecnicas')) {
            $orden->update([
                'estado' => 'Completada',
                'observaciones_tecnicas' => $request->observaciones_tecnicas
            ]);
        } else {
            // Si no hay observaciones, solo está iniciando la ruta
            $orden->update(['estado' => 'En Ruta']);
        }

        return redirect()->back()->with('success', 'El estado del servicio ha sido actualizado.');
    }
}