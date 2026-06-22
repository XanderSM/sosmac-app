<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Credenciales del Administrador
        User::create([
            'name' => 'Administrador Principal',
            'email' => 'admin@sosmac.com',
            'dni' => '12345678',
            'password' => Hash::make('admin123'),
            'rol' => 'admin',
            'estado' => 'Activo',
        ]);

        // 2. Credenciales del Técnico Fumigador
        User::create([
            'name' => 'Carlos Mendoza (Técnico)',
            'email' => 'tecnico@sosmac.com',
            'dni' => '87654321',
            'password' => Hash::make('tecnico123'),
            'rol' => 'tecnico',
            'estado' => 'Disponible', // Nace como disponible según tus requerimientos
        ]);
    }
}