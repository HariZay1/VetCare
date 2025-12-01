<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        // Resetear caché de roles y permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear permisos usando firstOrCreate para evitar duplicados
        $permissions = [
            'gestionar_propietarios',
            'gestionar_mascotas',
            'gestionar_citas',
            'gestionar_veterinarios',
            'gestionar_tratamientos',
            'ver_reportes',
            'exportar_datos',
            'gestionar_usuarios',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        // Crear roles usando firstOrCreate
        $adminRole = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web'
        ]);
        
        $recepcionRole = Role::firstOrCreate([
            'name' => 'recepcion', 
            'guard_name' => 'web'
        ]);
        
        $veterinarioRole = Role::firstOrCreate([
            'name' => 'veterinario',
            'guard_name' => 'web'
        ]);
        
        $clienteRole = Role::firstOrCreate([
            'name' => 'cliente',
            'guard_name' => 'web'
        ]);

        // Asignar permisos a roles
        $adminRole->syncPermissions(Permission::all());

        $recepcionRole->syncPermissions([
            'gestionar_propietarios',
            'gestionar_mascotas', 
            'gestionar_citas',
            'ver_reportes',
            'exportar_datos',
        ]);

        $veterinarioRole->syncPermissions([
            'gestionar_citas',
            'gestionar_tratamientos',
        ]);
 
    }
}