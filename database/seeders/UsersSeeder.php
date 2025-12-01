<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        // Usuario Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@vetcare.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
            ]
        );
        
        // Asignar rol solo si no lo tiene
        if (!$admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }

        // Usuario Recepción
        $recepcion = User::firstOrCreate(
            ['email' => 'recepcion@vetcare.com'],
            [
                'name' => 'María Pérez',
                'password' => Hash::make('recepcion123'),
                'email_verified_at' => now(),
            ]
        );
        
        if (!$recepcion->hasRole('recepcion')) {
            $recepcion->assignRole('recepcion');
        }

        // Usuario Veterinario
        $vet = User::firstOrCreate(
            ['email' => 'veterinario@vetcare.com'],
            [
                'name' => 'Dr. Carlos Rodríguez',
                'password' => Hash::make('veterinario123'),
                'email_verified_at' => now(),
            ]
        );
        
        if (!$vet->hasRole('veterinario')) {
            $vet->assignRole('veterinario');
        }

        // Usuario Cliente
        $cliente = User::firstOrCreate(
            ['email' => 'cliente@vetcare.com'],
            [
                'name' => 'Juan López',
                'password' => Hash::make('cliente123'),
                'email_verified_at' => now(),
            ]
        );
        
        if (!$cliente->hasRole('cliente')) {
            $cliente->assignRole('cliente');
        }
    }
}