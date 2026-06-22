<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    // Mostrar el inventario con BUSCADOR
    public function index(Request $request)
    {
        $buscar = $request->get('buscar');
        
        $productos = Producto::orderBy('nombre', 'asc')
            ->when($buscar, function ($query, $buscar) {
                return $query->where('nombre', 'like', "%{$buscar}%")
                             ->orWhere('tipo', 'like', "%{$buscar}%");
            })
            ->get();
            
        return view('admin.inventario.index', compact('productos', 'buscar'));
    }

    // CREAR: Guardar un nuevo producto
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|string|max:100',
            'unidad_medida' => 'required|string|max:50',
            'stock' => 'required|integer|min:0', // Bloqueo de negativos
            'stock_minimo' => 'required|integer|min:0',
        ], [
            'stock.min' => 'El stock no puede ser un valor negativo.',
            'stock_minimo.min' => 'El stock mínimo no puede ser negativo.'
        ]);

        Producto::create($request->all());

        return redirect()->back()->with('success', 'Producto registrado correctamente.');
    }

    // ACTUALIZAR: Modificar un producto existente
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|string|max:100',
            'unidad_medida' => 'required|string|max:50',
            'stock' => 'required|integer|min:0', // Bloqueo de negativos
            'stock_minimo' => 'required|integer|min:0',
            'estado' => 'required|boolean'
        ]);

        $producto = Producto::findOrFail($id);
        $producto->update($request->all());

        return redirect()->back()->with('success', 'Producto actualizado correctamente.');
    }

    // ELIMINAR: Borrar un producto
    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);
        $producto->delete();

        return redirect()->back()->with('success', 'Producto eliminado del sistema.');
    }
}