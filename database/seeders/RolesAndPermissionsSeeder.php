<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'manage_catalog',   // Gerente: Productos y Categorias
            'manage_inventory', // Gerente: Inventario y Costos
            'manage_orders',    // Vendedor: Pedidos
            'view_customers',   // Vendedor: Historial de compras
            'manage_users',     // Admin: Control de Acceso
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign created permissions
        $roleGerente = Role::firstOrCreate(['name' => 'gerente']);
        $roleGerente->givePermissionTo(['manage_catalog', 'manage_inventory']);

        $roleVendedor = Role::firstOrCreate(['name' => 'vendedor']);
        $roleVendedor->givePermissionTo(['manage_orders', 'view_customers']);

        $roleCliente = Role::firstOrCreate(['name' => 'cliente']);
        // Cliente uses public platform, no special admin permissions needed

        $roleAdmin = Role::firstOrCreate(['name' => 'admin_sistema']);
        $roleAdmin->givePermissionTo(['manage_users']);

        // Create sample users for each role if they don't exist
        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role_name' => 'admin_sistema',
            ],
            [
                'name' => 'Gerente User',
                'email' => 'gerente@example.com',
                'password' => Hash::make('password'),
                'role_name' => 'gerente',
            ],
            [
                'name' => 'Vendedor User',
                'email' => 'vendedor@example.com',
                'password' => Hash::make('password'),
                'role_name' => 'vendedor',
            ],
            [
                'name' => 'Cliente User',
                'email' => 'cliente@example.com',
                'password' => Hash::make('password'),
                'role_name' => 'cliente',
            ],
        ];

        foreach ($users as $userData) {
            $roleName = $userData['role_name'];
            unset($userData['role_name']);

            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                $userData
            );

            // Assign role
            if (! $user->hasRole($roleName)) {
                $user->assignRole($roleName);
            }
        }
    }
}
