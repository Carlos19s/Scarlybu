<?php

use App\Models\AuditLog;
use App\Models\AuditReport;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

beforeEach(function () {
    app()[PermissionRegistrar::class]->forgetCachedPermissions();

    // Create permissions
    Permission::firstOrCreate(['name' => 'view_audit_logs']);
    Permission::firstOrCreate(['name' => 'manage_audit_reports']);
    Permission::firstOrCreate(['name' => 'review_audit_reports']);

    // Create roles
    $this->roleAuditor = Role::firstOrCreate(['name' => 'auditor']);
    $this->roleAuditor->givePermissionTo(['view_audit_logs', 'manage_audit_reports']);

    $this->roleGerente = Role::firstOrCreate(['name' => 'gerente']);
    $this->roleGerente->givePermissionTo(['review_audit_reports']);

    $this->roleVendedor = Role::firstOrCreate(['name' => 'vendedor']);

    Role::firstOrCreate(['name' => 'cliente']);

    // Create users
    $this->auditor = User::factory()->create(['role' => 'auditor']);
    $this->auditor->assignRole('auditor');

    $this->gerente = User::factory()->create(['role' => 'gerente']);
    $this->gerente->assignRole('gerente');

    $this->vendedor = User::factory()->create(['role' => 'vendedor']);
    $this->vendedor->assignRole('vendedor');
});

test('creating a model generates an audit log entry', function () {
    $this->actingAs($this->gerente);

    $category = Category::create([
        'nombre' => 'Audit Category',
        'slug' => 'audit-category',
        'activa' => true,
    ]);

    $this->assertDatabaseHas('audit_logs', [
        'event' => 'created',
        'model_type' => Category::class,
        'model_id' => $category->id,
        'user_id' => $this->gerente->id,
    ]);

    $product = Product::create([
        'nombre' => 'Audit Product',
        'slug' => 'audit-product',
        'category_id' => $category->id,
        'precio_compra' => 10.00,
        'precio_venta' => 20.00,
        'stock' => 50,
        'activo' => true,
    ]);

    $this->assertDatabaseHas('audit_logs', [
        'event' => 'created',
        'model_type' => Product::class,
        'model_id' => $product->id,
        'user_id' => $this->gerente->id,
    ]);
});

test('unauthorized users cannot access system logs', function () {
    // Guest gets redirected to login
    $response = $this->get(route('admin.audit.logs'));
    $response->assertRedirect(route('login'));

    // Vendedor user gets 403
    $this->actingAs($this->vendedor);
    $response = $this->get(route('admin.audit.logs'));
    $response->assertStatus(403);

    // Gerente user gets 403
    $this->actingAs($this->gerente);
    $response = $this->get(route('admin.audit.logs'));
    $response->assertStatus(403);
});

test('auditor users can access system logs and see events list', function () {
    $this->actingAs($this->auditor);

    $response = $this->get(route('admin.audit.logs'));
    $response->assertStatus(200);
});

test('unauthorized users cannot access reports list', function () {
    // Vendedor user gets 403
    $this->actingAs($this->vendedor);
    $response = $this->get(route('admin.audit.reports'));
    $response->assertStatus(403);
});

test('auditor can generate a report via livewire component', function () {
    $this->actingAs($this->auditor);

    Livewire::test('admin.audit.reports')
        ->set('title', 'Weekly Audit Report')
        ->set('startDate', now()->subDays(7)->toDateString())
        ->set('endDate', now()->toDateString())
        ->set('description', 'Test observation summary')
        ->call('generateReport')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('audit_reports', [
        'title' => 'Weekly Audit Report',
        'generated_by' => $this->auditor->id,
        'status' => 'pending',
    ]);
});

test('gerente can review and sign a report', function () {
    $report = AuditReport::create([
        'title' => 'Monthly Change Report',
        'description' => 'Review description',
        'start_date' => now()->subMonth(),
        'end_date' => now(),
        'generated_by' => $this->auditor->id,
        'status' => 'pending',
    ]);

    $this->actingAs($this->gerente);

    Livewire::test('admin.audit.reports')
        ->call('viewDetails', $report)
        ->set('comments', 'Everything looks in order. Signed.')
        ->call('saveReview')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('audit_reports', [
        'id' => $report->id,
        'status' => 'reviewed',
        'comments' => 'Everything looks in order. Signed.',
        'reviewed_by' => $this->gerente->id,
    ]);
});

test('client actions do not generate audit logs except order confirmation', function () {
    $client = User::factory()->create(['role' => 'cliente']);
    $client->assignRole('cliente');

    $this->actingAs($client);

    // 1. Client creates a Category -> should NOT generate audit log
    $category = Category::create([
        'nombre' => 'Client Category',
        'slug' => 'client-category',
        'activa' => true,
    ]);

    $this->assertDatabaseMissing('audit_logs', [
        'model_type' => Category::class,
        'model_id' => $category->id,
    ]);

    // 2. Client confirms an order -> should generate audit log
    $order = Order::create([
        'numero_pedido' => 'SC-TEST-CLIENT',
        'user_id' => $client->id,
        'cliente_nombre' => 'Test Client',
        'cliente_documento' => '1234567890',
        'cliente_telefono' => '0999999999',
        'cliente_correo' => 'client@example.com',
        'cliente_direccion' => 'Calle Falsa 123',
        'total' => 100.00,
        'estado' => 'no_revisado',
    ]);

    $this->assertDatabaseHas('audit_logs', [
        'event' => 'created',
        'model_type' => Order::class,
        'model_id' => $order->id,
        'user_id' => $client->id,
    ]);

    $log = AuditLog::where('model_type', Order::class)
        ->where('model_id', $order->id)
        ->first();
    expect($log->description)->toContain('confirmado por');
});
