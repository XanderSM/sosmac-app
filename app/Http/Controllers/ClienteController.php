<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClienteController extends Controller
{
    // LEER: Mostrar la lista de clientes con BUSCADOR
    public function index(Request $request)
    {
        $buscar = $request->get('buscar');
        
        $clientes = Cliente::orderBy('created_at', 'desc')
            ->when($buscar, function ($query, $buscar) {
                return $query->where('nombre_razon_social', 'like', "%{$buscar}%")
                             ->orWhere('documento', 'like', "%{$buscar}%");
            })
            ->get();
            
        return view('admin.clientes.index', compact('clientes', 'buscar'));
    }

    // CREAR: Guardar un nuevo cliente (CUS 01)
    public function store(Request $request)
    {
        $request->validate([
            'tipo_documento' => 'required|in:DNI,RUC',
            'documento' => [
                'required',
                'unique:clientes,documento', 
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->tipo_documento === 'DNI' && strlen($value) !== 8) $fail('El DNI debe tener exactamente 8 dígitos.');
                    if ($request->tipo_documento === 'RUC' && strlen($value) !== 11) $fail('El RUC debe tener exactamente 11 dígitos.');
                },
            ],
            'nombre_razon_social' => 'required|string|max:255',
            'tipo_cliente' => 'required|in:Persona Natural,Empresa',
        ]);

        Cliente::create($request->all());

        return redirect()->back()->with('success', 'Cliente registrado correctamente.');
    }

    // ACTUALIZAR: Modificar datos de un cliente
    public function update(Request $request, $id)
    {
        $request->validate([
            'tipo_documento' => 'required|in:DNI,RUC',
            'documento' => [
                'required',
                Rule::unique('clientes')->ignore($id), // Ignora su propio ID para que deje editar
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->tipo_documento === 'DNI' && strlen($value) !== 8) $fail('El DNI debe tener exactamente 8 dígitos.');
                    if ($request->tipo_documento === 'RUC' && strlen($value) !== 11) $fail('El RUC debe tener exactamente 11 dígitos.');
                },
            ],
            'nombre_razon_social' => 'required|string|max:255',
            'tipo_cliente' => 'required|in:Persona Natural,Empresa',
            'estado' => 'required|boolean'
        ]);

        $cliente = Cliente::findOrFail($id);
        $cliente->update($request->all());

        return redirect()->back()->with('success', 'Datos del cliente actualizados.');
    }

    // ELIMINAR: Borrar un cliente
    public function destroy($id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->delete();

        return redirect()->back()->with('success', 'Cliente eliminado del sistema.');
    }
}