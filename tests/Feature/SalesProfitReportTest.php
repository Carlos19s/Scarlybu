<?php

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

beforeEach(function () {
    app()[PermissionRegistrar::class]->forgetCachedPermissions();

    // Create permission and roles
    Permission::firstOrCreate(['name' => 'manage_inventory']);
    $roleGerente = Role::firstOrCreate(['name' => 'gerente']);
    $roleGerente->givePermissionTo('manage_inventory');

    Role::firstOrCreate(['name' => 'cliente']);

    $this->gerente = User::factory()->create(['role' => 'gerente']);
    $this->gerente->assignRole('gerente');

    $this->cliente = User::factory()->create(['role' => 'cliente']);
    $this->cliente->assignRole('cliente');

    // Create test categories & products
    $this->category = Category::create([
        'nombre' => 'Report Category',
        'slug' => 'report-category',
        'activa' => true,
    ]);

    $this->productA = Product::create([
        'nombre' => 'Product Cost 10 Sell 23',
        'slug' => 'prod-a',
        'category_id' => $this->category->id,
        'precio_compra' => 10.00,
        'precio_venta' => 23.00, // includes 15% IVA. Net = 23 / 1.15 = 20.00. Profit = 20 - 10 = 10.00
        'stock' => 50,
        'activo' => true,
    ]);

    $this->productB = Product::create([
        'nombre' => 'Product Cost 5 Sell 11.5',
        'slug' => 'prod-b',
        'category_id' => $this->category->id,
        'precio_compra' => 5.00,
        'precio_venta' => 11.50, // includes 15% IVA. Net = 11.5 / 1.15 = 10.00. Profit = 10 - 5 = 5.00
        'stock' => 50,
        'activo' => true,
    ]);
});

test('unauthorized users cannot access sales and profit report', function () {
    // Guest redirects to login
    $response = $this->get(route('admin.reports.sales-profit'));
    $response->assertRedirect(route('login'));

    // Client gets 403
    $this->actingAs($this->cliente);
    $response = $this->get(route('admin.reports.sales-profit'));
    $response->assertStatus(403);
});

test('gerente user can access sales and profit report and see correct values', function () {
    $this->actingAs($this->gerente);

    // Create an order
    $order = Order::create([
        'numero_pedido' => 'SC-REPORTE-TEST',
        'user_id' => $this->cliente->id,
        'cliente_nombre' => 'Client Test',
        'cliente_documento' => '12345',
        'cliente_telefono' => '12345',
        'cliente_correo' => 'test@example.com',
        'cliente_direccion' => 'Direccion',
        'total' => 34.50, // 23.00 + 11.50
        'estado' => 'no_revisado',
    ]);

    // Add items
    OrderItem::create([
        'order_id' => $order->id,
        'product_id' => $this->productA->id,
        'cantidad' => 1,
        'precio_unitario' => 23.00,
        'iva_porcentaje' => 15.00,
        'subtotal' => 23.00,
    ]);

    OrderItem::create([
        'order_id' => $order->id,
        'product_id' => $this->productB->id,
        'cantidad' => 1,
        'precio_unitario' => 11.50,
        'iva_porcentaje' => 15.00,
        'subtotal' => 11.50,
    ]);

    // Total gross: 34.50
    // Total net: 23/1.15 + 11.5/1.15 = 20.00 + 10.00 = 30.00
    // Total cost: 10 + 5 = 15.00
    // Profit: 30 - 15 = 15.00
    // Margin: 15 / 30 = 50.00%

    Livewire::test('admin.reports.sales-profit')
        ->assertSet('periodType', 'daily')
        ->assertSee('34.50') // gross
        ->assertSee('30.00') // net
        ->assertSee('15.00') // cost and profit
        ->assertSee('50.00%') // margin
        ->call('exportCsv')
        ->assertFileDownloaded();
});
