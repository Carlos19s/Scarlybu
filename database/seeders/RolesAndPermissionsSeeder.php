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
            'view_audit_logs',  // Auditor: Ver bitacora/log
            'manage_audit_reports', // Auditor: Generar reportes
            'review_audit_reports', // Gerente: Revisar reportes
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign created permissions
        $roleGerente = Role::firstOrCreate(['name' => 'gerente']);
        $roleGerente->givePermissionTo(['manage_catalog', 'manage_inventory', 'review_audit_reports']);

        $roleVendedor = Role::firstOrCreate(['name' => 'vendedor']);
        $roleVendedor->givePermissionTo(['manage_orders', 'view_customers']);

        $roleCliente = Role::firstOrCreate(['name' => 'cliente']);
        // Cliente uses public platform, no special admin permissions needed

        $roleAdmin = Role::firstOrCreate(['name' => 'admin_sistema']);
        $roleAdmin->givePermissionTo(['manage_users']);

        $roleAuditor = Role::firstOrCreate(['name' => 'auditor']);
        $roleAuditor->givePermissionTo(['view_audit_logs', 'manage_audit_reports']);

        // Create sample users for each role if they don't exist
        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role' => 'admin_sistema',
                'role_name' => 'admin_sistema',
            ],
            [
                'name' => 'Gerente User',
                'email' => 'gerente@example.com',
                'password' => Hash::make('password'),
                'role' => 'gerente',
                'role_name' => 'gerente',
            ],
            [
                'name' => 'Vendedor User',
                'email' => 'vendedor@example.com',
                'password' => Hash::make('password'),
                'role' => 'vendedor',
                'role_name' => 'vendedor',
            ],
            [
                'name' => 'Cliente User',
                'email' => 'cliente@example.com',
                'password' => Hash::make('password'),
                'role' => 'cliente',
                'role_name' => 'cliente',
            ],
            [
                'name' => 'Auditor User',
                'email' => 'auditor@example.com',
                'password' => Hash::make('password'),
                'role' => 'auditor',
                'role_name' => 'auditor',
            ],
        ];

        foreach ($users as $userData) {
            $roleName = $userData['role_name'];
            unset($userData['role_name']);

            $user = User::where('email', $userData['email'])->first();
            if ($user) {
                $user->update($userData);
            } else {
                $user = User::create($userData);
            }

            // Assign role
            if (! $user->hasRole($roleName)) {
                $user->assignRole($roleName);
            }
        }
    }
}
