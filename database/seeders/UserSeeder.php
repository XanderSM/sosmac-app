<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Credenciales del Administrador
        User::firstOrCreate(
            ['email' => 'admin@sosmac.com'],
            [
                'name' => 'Administrador Principal',
                'dni' => '12345678',
                'password' => Hash::make('admin123'),
                'rol' => 'admin',
                'estado' => 'Activo',
            ]
        );

        // 2. Credenciales del Técnico Fumigador
        User::firstOrCreate(
            ['email' => 'tecnico@sosmac.com'],
            [
                'name' => 'Carlos Mendoza (Técnico)',
                'dni' => '87654321',
                'password' => Hash::make('tecnico123'),
                'rol' => 'tecnico',
                'estado' => 'Disponible', // Nace como disponible según tus requerimientos
            ]
        );
    }
}
