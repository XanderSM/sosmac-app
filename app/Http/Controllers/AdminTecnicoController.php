<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminTecnicoController extends Controller
{
    // Mostrar la lista de técnicos
    public function index()
    {
        $tecnicos = User::where('rol', 'tecnico')->get();
        return view('admin.tecnicos.index', compact('tecnicos'));
    }

    // Guardar un nuevo técnico en la BD
    public function store(Request $request)
    {
        // Validaciones estrictas con mensajes en español
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ], [
            'email.unique' => 'Este correo ya está registrado en el sistema. Usa otro.',
            'password.min' => 'Por seguridad, la contraseña debe tener al menos 8 caracteres.'
        ]);

        // Creación del usuario con rol 'tecnico'
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => 'tecnico',
        ]);

        return redirect()->back()->with('success', 'Técnico registrado correctamente.');
    }

    // Eliminar un técnico
    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Técnico eliminado del sistema.');
    }
}