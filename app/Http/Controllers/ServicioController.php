<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use Illuminate\Http\Request;

class ServicioController extends Controller
{
    // LEER: Mostrar la lista de servicios con BUSCADOR
    public function index(Request $request)
    {
        $buscar = $request->get('buscar');
        
        $servicios = Servicio::orderBy('nombre', 'asc')
            ->when($buscar, function ($query, $buscar) {
                return $query->where('nombre', 'like', "%{$buscar}%")
                             ->orWhere('descripcion', 'like', "%{$buscar}%");
            })
            ->get();
            
        return view('admin.servicios.index', compact('servicios', 'buscar'));
    }

    // CREAR: Guardar un nuevo servicio
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio_base' => 'required|numeric|min:0', // RN: No permite precios negativos
        ], [
            'precio_base.min' => 'El precio base no puede ser un valor negativo.'
        ]);

        Servicio::create($request->all());

        return redirect()->back()->with('success', 'Servicio registrado en el catálogo.');
    }

    // ACTUALIZAR: Modificar datos de un servicio
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio_base' => 'required|numeric|min:0',
            'estado' => 'required|boolean'
        ]);

        $servicio = Servicio::findOrFail($id);
        $servicio->update($request->all());

        return redirect()->back()->with('success', 'Catálogo actualizado correctamente.');
    }

    // ELIMINAR: Borrar un servicio
    public function destroy($id)
    {
        try {
            $servicio = \App\Models\Servicio::findOrFail($id);
            $servicio->delete();
            
            return redirect()->back()->with('success', 'Servicio eliminado correctamente.');
            
        } catch (\Illuminate\Database\QueryException $e) {
            // El código 23000 es el de violación de llave foránea en MySQL
            if ($e->getCode() == "23000") {
                return redirect()->back()->withErrors('No puedes eliminar este servicio porque ya forma parte del historial de una cotización. Si ya no lo ofreces, edítalo y cambia su estado a Inactivo.');
            }
            
            return redirect()->back()->withErrors('Ocurrió un error en la base de datos al intentar eliminar el registro.');
        }
    }
}