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
        $adminRole = Role::create(['name' => 'Administrador', 'editable' => 0, 'guard_name' => 'api']);
        
        /*
            |--------------------------------------------------------------------------
            | Zone permissions
            |--------------------------------------------------------------------------
        */
        Permission::create([
            'name' => 'zone.list',
            'display_name' => 'Listar Zonas',
            'group' => 'Zonas',
            'route' => '/zone',
            'guard_name' => 'api'
        ])->syncRoles([$adminRole]);

        Permission::create([
            'name' => 'zone.create',
            'display_name' => 'Crear Zona',
            'group' => 'Zonas',
            'route' => '/zone',
            'guard_name' => 'api'
        ])->syncRoles([$adminRole]);

        Permission::create([
            'name' => 'zone.update',
            'display_name' => 'Actualizar Zona',
            'group' => 'Zonas',
            'route' => '/zone',
            'guard_name' => 'api'
        ])->syncRoles([$adminRole]);
        
        Permission::create([
            'name' => 'zone.get',
            'display_name' => 'Consultar Zona',
            'group' => 'Zonas',
            'route' => '/zone',
            'guard_name' => 'api'
        ])->syncRoles([$adminRole]);

        Permission::create([
            'name' => 'zone.delete',
            'display_name' => 'Eliminar Zona',
            'group' => 'Zonas',
            'route' => '/zone',
            'guard_name' => 'api'
        ])->syncRoles([$adminRole]);

        /*
            |--------------------------------------------------------------------------
            | Role permissions
            |--------------------------------------------------------------------------
        */
        Permission::create([
            'name' => 'role.list',
            'display_name' => 'Listar Roles',
            'group' => 'Roles',
            'route' => '/role',
            'guard_name' => 'api'
        ])->syncRoles([$adminRole]);

        Permission::create([
            'name' => 'role.create',
            'display_name' => 'Crear Rol',
            'group' => 'Roles',
            'route' => '/role',
            'guard_name' => 'api'
        ])->syncRoles([$adminRole]);

        Permission::create([
            'name' => 'role.update',
            'display_name' => 'Actualizar Rol',
            'group' => 'Roles',
            'route' => '/role',
            'guard_name' => 'api'
        ])->syncRoles([$adminRole]);
        
        Permission::create([
            'name' => 'role.get',
            'display_name' => 'Consultar Rol',
            'group' => 'Roles',
            'route' => '/role',
            'guard_name' => 'api'
        ])->syncRoles([$adminRole]);

        Permission::create([
            'name' => 'role.delete',
            'display_name' => 'Eliminar Rol',
            'group' => 'Roles',
            'route' => '/role',
            'guard_name' => 'api'
        ])->syncRoles([$adminRole]);

        /*
            |--------------------------------------------------------------------------
            | Yard permissions
            |--------------------------------------------------------------------------
        */
        Permission::create([
            'name' => 'yard.list',
            'display_name' => 'Listar Patios',
            'group' => 'Patios',
            'route' => '/yard',
            'guard_name' => 'api'
        ])->syncRoles([$adminRole]);

        Permission::create([
            'name' => 'yard.create',
            'display_name' => 'Crear Patio',
            'group' => 'Patios',
            'route' => '/yard',
            'guard_name' => 'api'
        ])->syncRoles([$adminRole]);

        Permission::create([
            'name' => 'yard.update',
            'display_name' => 'Actualizar Patio',
            'group' => 'Patios',
            'route' => '/yard',
            'guard_name' => 'api'
        ])->syncRoles([$adminRole]);
        
        Permission::create([
            'name' => 'yard.get',
            'display_name' => 'Consultar Patio',
            'group' => 'Patios',
            'route' => '/yard',
            'guard_name' => 'api'
        ])->syncRoles([$adminRole]);

        Permission::create([
            'name' => 'yard.delete',
            'display_name' => 'Eliminar Patio',
            'group' => 'Patios',
            'route' => '/yard',
            'guard_name' => 'api'
        ])->syncRoles([$adminRole]);

        /*
            |--------------------------------------------------------------------------
            | User permissions
            |--------------------------------------------------------------------------
        */
        Permission::create([
            'name' => 'user.list',
            'display_name' => 'Listar Usuarios',
            'group' => 'Usuarios',
            'route' => '/user',
            'guard_name' => 'api'
        ])->syncRoles([$adminRole]);

        Permission::create([
            'name' => 'user.create',
            'display_name' => 'Crear Usuario',
            'group' => 'Usuarios',
            'route' => '/user',
            'guard_name' => 'api'
        ])->syncRoles([$adminRole]);

        Permission::create([
            'name' => 'user.update',
            'display_name' => 'Actualizar Usuario',
            'group' => 'Usuarios',
            'route' => '/user',
            'guard_name' => 'api'
        ])->syncRoles([$adminRole]);
        
        Permission::create([
            'name' => 'user.get',
            'display_name' => 'Consultar Usuario',
            'group' => 'Usuarios',
            'route' => '/user',
            'guard_name' => 'api'
        ])->syncRoles([$adminRole]);

        Permission::create([
            'name' => 'user.delete',
            'display_name' => 'Eliminar Usuario',
            'group' => 'Usuarios',
            'route' => '/user',
            'guard_name' => 'api'
        ])->syncRoles([$adminRole]);

        Permission::create([
            'name' => 'user.updateProfile',
            'display_name' => 'Actualizar perfil',
            'group' => 'Usuarios',
            'route' => '/user',
            'guard_name' => 'api'
        ])->syncRoles([$adminRole]);

        /*
            |--------------------------------------------------------------------------
            | Material permissions
            |--------------------------------------------------------------------------
        */
        Permission::create([
            'name' => 'material.list',
            'display_name' => 'Listar Materiales',
            'group' => 'Materiales',
            'route' => '/material',
            'guard_name' => 'api'
        ])->syncRoles([$adminRole]);

        Permission::create([
            'name' => 'material.create',
            'display_name' => 'Crear Material',
            'group' => 'Materiales',
            'route' => '/material',
            'guard_name' => 'api'
        ])->syncRoles([$adminRole]);

        Permission::create([
            'name' => 'material.update',
            'display_name' => 'Actualizar Material',
            'group' => 'Materiales',
            'route' => '/material',
            'guard_name' => 'api'
        ])->syncRoles([$adminRole]);
        
        Permission::create([
            'name' => 'material.get',
            'display_name' => 'Consultar Material',
            'group' => 'Materiales',
            'route' => '/material',
            'guard_name' => 'api'
        ])->syncRoles([$adminRole]);

        Permission::create([
            'name' => 'material.delete',
            'display_name' => 'Eliminar Material',
            'group' => 'Materiales',
            'route' => '/material',
            'guard_name' => 'api'
        ])->syncRoles([$adminRole]);

        /*
            |--------------------------------------------------------------------------
            | Third permissions
            |--------------------------------------------------------------------------
        */
        Permission::create([
            'name' => 'third.list',
            'display_name' => 'Listar Terceros',
            'group' => 'Terceros',
            'route' => '/third',
            'guard_name' => 'api'
        ])->syncRoles([$adminRole]);

        Permission::create([
            'name' => 'third.createInBatch',
            'display_name' => 'Crear Terceros En Lote',
            'group' => 'Terceros',
            'route' => '/third',
            'guard_name' => 'api'
        ])->syncRoles([$adminRole]);

        Permission::create([
            'name' => 'third.create',
            'display_name' => 'Crear Tercero',
            'group' => 'Terceros',
            'route' => '/third',
            'guard_name' => 'api'
        ])->syncRoles([$adminRole]);

        Permission::create([
            'name' => 'third.update',
            'display_name' => 'Actualizar Tercero',
            'group' => 'Terceros',
            'route' => '/third',
            'guard_name' => 'api'
        ])->syncRoles([$adminRole]);
        
        Permission::create([
            'name' => 'third.get',
            'display_name' => 'Consultar Tercero',
            'group' => 'Terceros',
            'route' => '/third',
            'guard_name' => 'api'
        ])->syncRoles([$adminRole]);

        Permission::create([
            'name' => 'third.delete',
            'display_name' => 'Eliminar Tercero',
            'group' => 'Terceros',
            'route' => '/third',
            'guard_name' => 'api'
        ])->syncRoles([$adminRole]);

        /*
            |--------------------------------------------------------------------------
            | Adjustments permissions
            |--------------------------------------------------------------------------
        */
        Permission::create([
            'name' => 'adjustment.list',
            'display_name' => 'Listar Ajustes',
            'group' => 'Ajustes',
            'route' => '/adjustment',
            'guard_name' => 'api'
        ])->syncRoles([$adminRole]);

        Permission::create([
            'name' => 'adjustment.create',
            'display_name' => 'Crear Ajuste',
            'group' => 'Ajustes',
            'route' => '/adjustment',
            'guard_name' => 'api'
        ])->syncRoles([$adminRole]);

        Permission::create([
            'name' => 'adjustment.update',
            'display_name' => 'Actualizar Ajuste',
            'group' => 'Ajustes',
            'route' => '/adjustment',
            'guard_name' => 'api'
        ])->syncRoles([$adminRole]);
        
        Permission::create([
            'name' => 'adjustment.get',
            'display_name' => 'Consultar Ajuste',
            'group' => 'Ajustes',
            'route' => '/adjustment',
            'guard_name' => 'api'
        ])->syncRoles([$adminRole]);

        Permission::create([
            'name' => 'adjustment.delete',
            'display_name' => 'Eliminar Ajuste',
            'group' => 'Ajustes',
            'route' => '/adjustment',
            'guard_name' => 'api'
        ])->syncRoles([$adminRole]);

        /*
            |--------------------------------------------------------------------------
            | Rate permissions
            |--------------------------------------------------------------------------
        */
        Permission::create([
            'name' => 'rate.list',
            'display_name' => 'Listar Tarifas',
            'group' => 'Tarifas',
            'route' => '/rate',
            'guard_name' => 'api'
        ])->syncRoles([$adminRole]);

        Permission::create([
            'name' => 'rate.create',
            'display_name' => 'Crear Tarifa',
            'group' => 'Tarifas',
            'route' => '/rate',
            'guard_name' => 'api'
        ])->syncRoles([$adminRole]);

        Permission::create([
            'name' => 'rate.update',
            'display_name' => 'Actualizar Tarifa',
            'group' => 'Tarifas',
            'route' => '/rate',
            'guard_name' => 'api'
        ])->syncRoles([$adminRole]);
        
        Permission::create([
            'name' => 'rate.get',
            'display_name' => 'Consultar Tarifa',
            'group' => 'Tarifas',
            'route' => '/rate',
            'guard_name' => 'api'
        ])->syncRoles([$adminRole]);

        Permission::create([
            'name' => 'rate.delete',
            'display_name' => 'Eliminar Tarifa',
            'group' => 'Tarifas',
            'route' => '/rate',
            'guard_name' => 'api'
        ])->syncRoles([$adminRole]);

        /*
            |--------------------------------------------------------------------------
            | Ticket permissions
            |--------------------------------------------------------------------------
        */
        Permission::create([
            'name' => 'ticket.list',
            'display_name' => 'Listar Tiquetes',
            'group' => 'Tiquetes',
            'route' => '/ticket',
            'guard_name' => 'api'
        ])->syncRoles([$adminRole]);

        Permission::create([
            'name' => 'ticket.create',
            'display_name' => 'Crear Tiquete',
            'group' => 'Tiquetes',
            'route' => '/ticket',
            'guard_name' => 'api'
        ])->syncRoles([$adminRole]);

        Permission::create([
            'name' => 'ticket.update',
            'display_name' => 'Actualizar Tiquete',
            'group' => 'Tiquetes',
            'route' => '/ticket',
            'guard_name' => 'api'
        ])->syncRoles([$adminRole]);
        
        Permission::create([
            'name' => 'ticket.get',
            'display_name' => 'Consultar Tiquete',
            'group' => 'Tiquetes',
            'route' => '/ticket',
            'guard_name' => 'api'
        ])->syncRoles([$adminRole]);

        Permission::create([
            'name' => 'ticket.delete',
            'display_name' => 'Eliminar Tiquete',
            'group' => 'Tiquetes',
            'route' => '/ticket',
            'guard_name' => 'api'
        ])->syncRoles([$adminRole]);

        /*
            |--------------------------------------------------------------------------
            | Local Ticket permissions
            |--------------------------------------------------------------------------
        */
        Permission::create([
            'name' => 'localTicket.list',
            'display_name' => 'Listar Tiquetes',
            'group' => 'Tiquetes (Offline)',
            'route' => '/localTicket',
            'guard_name' => 'api'
        ])->syncRoles([$adminRole]);

        Permission::create([
            'name' => 'localTicket.create',
            'display_name' => 'Crear Tiquete',
            'group' => 'Tiquetes (Offline)',
            'route' => '/localTicket',
            'guard_name' => 'api'
        ])->syncRoles([$adminRole]);

        Permission::create([
            'name' => 'localTicket.update',
            'display_name' => 'Actualizar Tiquete',
            'group' => 'Tiquetes (Offline)',
            'route' => '/localTicket',
            'guard_name' => 'api'
        ])->syncRoles([$adminRole]);
        
        Permission::create([
            'name' => 'localTicket.get',
            'display_name' => 'Consultar Tiquete',
            'group' => 'Tiquetes (Offline)',
            'route' => '/localTicket',
            'guard_name' => 'api'
        ])->syncRoles([$adminRole]);

        Permission::create([
            'name' => 'localTicket.delete',
            'display_name' => 'Eliminar Tiquete',
            'group' => 'Tiquetes (Offline)',
            'route' => '/localTicket',
            'guard_name' => 'api'
        ])->syncRoles([$adminRole]);

        /*
            |--------------------------------------------------------------------------
            | Synchronize permissions
            |--------------------------------------------------------------------------
        */
        Permission::create([
            'name' => 'synchronization.synchronize',
            'display_name' => 'Sincronizar',
            'group' => 'Sincronización',
            'route' => '/synchronization',
            'guard_name' => 'api',
            'menu' => 0
        ])->syncRoles([$adminRole]);

        /*
            |--------------------------------------------------------------------------
            | Material settlement  permissions
            |--------------------------------------------------------------------------
        */
        Permission::create([
            'name' => 'materialSettlement.list',
            'display_name' => 'Listar Liquidaciones',
            'group' => 'Liquidación (Material)',
            'route' => '/materialSettlement',
            'guard_name' => 'api'
        ])->syncRoles([$adminRole]);

        Permission::create([
            'name' => 'materialSettlement.get',
            'display_name' => 'Consultar Liquidación',
            'group' => 'Liquidación (Material)',
            'route' => '/materialSettlement',
            'guard_name' => 'api'
        ])->syncRoles([$adminRole]);

        Permission::create([
            'name' => 'materialSettlement.print',
            'display_name' => 'Imprimir Liquidación',
            'group' => 'Liquidación (Material)',
            'route' => '/materialSettlement',
            'guard_name' => 'api'
        ])->syncRoles([$adminRole]);

        Permission::create([
            'name' => 'materialSettlement.settle',
            'display_name' => 'Liquidar',
            'group' => 'Liquidación (Material)',
            'route' => '/materialSettlement',
            'guard_name' => 'api'
        ])->syncRoles([$adminRole]);

        Permission::create([
            'name' => 'materialSettlement.addInformation',
            'display_name' => 'Agregar Información',
            'group' => 'Liquidación (Material)',
            'route' => '/materialSettlement',
            'guard_name' => 'api'
        ])->syncRoles([$adminRole]);

        /*
            |--------------------------------------------------------------------------
            | Freight settlement  permissions
            |--------------------------------------------------------------------------
        */
        Permission::create([
            'name' => 'freightSettlement.list',
            'display_name' => 'Listar Liquidaciones',
            'group' => 'Liquidación (Flete)',
            'route' => '/freightSettlement',
            'guard_name' => 'api'
        ])->syncRoles([$adminRole]);

        Permission::create([
            'name' => 'freightSettlement.get',
            'display_name' => 'Consultar Liquidación',
            'group' => 'Liquidación (Flete)',
            'route' => '/freightSettlement',
            'guard_name' => 'api'
        ])->syncRoles([$adminRole]);

        Permission::create([
            'name' => 'freightSettlement.print',
            'display_name' => 'Imprimir Liquidación',
            'group' => 'Liquidación (Flete)',
            'route' => '/freightSettlement',
            'guard_name' => 'api'
        ])->syncRoles([$adminRole]);

        Permission::create([
            'name' => 'freightSettlement.settle',
            'display_name' => 'Liquidar',
            'group' => 'Liquidación (Flete)',
            'route' => '/freightSettlement',
            'guard_name' => 'api'
        ])->syncRoles([$adminRole]);

        Permission::create([
            'name' => 'freightSettlement.addInformation',
            'display_name' => 'Agregar Información',
            'group' => 'Liquidación (Flete)',
            'route' => '/freightSettlement',
            'guard_name' => 'api'
        ])->syncRoles([$adminRole]);
    }
}
