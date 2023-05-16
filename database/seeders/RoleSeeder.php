<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::create(['name' => 'Admin', 'editable' => 0]);
        
        Permission::create(['name' => 'zone.list', 'display_name' => 'Listar Zonas'])->syncRoles([$adminRole]);
        Permission::create(['name' => 'zone.create', 'display_name' => 'Crear Zona'])->syncRoles([$adminRole]);
        Permission::create(['name' => 'zone.update', 'display_name' => 'Actualizar Zona'])->syncRoles([$adminRole]);
        Permission::create(['name' => 'zone.get', 'display_name' => 'Obtener Zona'])->syncRoles([$adminRole]);
        Permission::create(['name' => 'zone.delete', 'display_name' => 'Eliminar Zona'])->syncRoles([$adminRole]);
    }
}
