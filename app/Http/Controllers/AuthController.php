<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // 1. Mostrar la pantalla de Login
    public function showLogin()
    {
        return view('auth.login');
    }

    // 2. Procesar el intento de ingreso
    public function login(Request $request)
    {
        // Validar que envíen datos
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Intentar iniciar sesión
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // REDIRECCIÓN INTELIGENTE SEGÚN EL ROL
            if ($user->rol === 'admin') {
                return redirect()->intended('/admin/dashboard');
            } elseif ($user->rol === 'tecnico') {
                return redirect()->intended('/tecnico/portal');
            }
        }

        // Si falla, regresar con error
        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    // 3. Cerrar sesión
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}