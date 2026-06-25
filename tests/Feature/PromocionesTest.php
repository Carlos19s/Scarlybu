<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\Promocion;
use App\Models\User;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

test('landing page and catalog show products in active promotions', function () {
    // Create categories
    $category = Category::create([
        'nombre' => 'Test Category',
        'slug' => 'test-category',
        'activa' => true,
    ]);

    // Create products
    $productInPromo = Product::create([
        'nombre' => 'Super Promo Cap',
        'slug' => 'super-promo-cap',
        'descripcion' => 'A stylish cap in promo',
        'category_id' => $category->id,
        'precio_compra' => 5.00,
        'precio_venta' => 15.00,
        'stock' => 10,
        'activo' => true,
    ]);

    $productNoPromo = Product::create([
        'nombre' => 'Regular Cap',
        'slug' => 'regular-cap',
        'descripcion' => 'A standard cap not in promo',
        'category_id' => $category->id,
        'precio_compra' => 5.00,
        'precio_venta' => 15.00,
        'stock' => 10,
        'activo' => true,
    ]);

    // Create active promo on product
    Promocion::create([
        'product_id' => $productInPromo->id,
        'precio_promocion' => 10.00,
        'fecha_inicio' => now()->subDay()->toDateString(),
        'fecha_fin' => now()->addDays(2)->toDateString(),
    ]);

    // 1. Check Homepage Promotions section
    $responseHome = $this->get(route('store.home'));
    $responseHome->assertStatus(200);
    $responseHome->assertSee('Promociones');
    $responseHome->assertSee('Super Promo Cap');

    // 2. Check Catalog Page
    $responseCatalog = $this->get(route('store.promotions'));
    $responseCatalog->assertStatus(200);
    $responseCatalog->assertSee('Ofertas Especiales');
    $responseCatalog->assertSee('Super Promo Cap');
    $responseCatalog->assertDontSee('Regular Cap');
});

test('promotions section does not render when no active promotions exist', function () {
    $response = $this->get(route('store.home'));
    $response->assertStatus(200);
    $response->assertDontSee('Ofertas Activas');

    $responseCatalog = $this->get(route('store.promotions'));
    $responseCatalog->assertStatus(200);
    $responseCatalog->assertSee('No hay ofertas disponibles en este momento');
});

// Admin Promotions CRUD Tests

test('guests are redirected to login when accessing admin promotions', function () {
    $response = $this->get(route('admin.promotions.index'));
    $response->assertRedirect(route('login'));
});

test('regular users cannot access admin promotions', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('admin.promotions.index'));
    $response->assertStatus(403);
});

test('gerente users can access admin promotions', function () {
    // Setup Spatie Role & Permission
    app()[PermissionRegistrar::class]->forgetCachedPermissions();
    Permission::firstOrCreate(['name' => 'manage_catalog']);
    $role = Role::firstOrCreate(['name' => 'gerente']);
    $role->givePermissionTo('manage_catalog');

    $user = User::factory()->create();
    $user->assignRole('gerente');
    $this->actingAs($user);

    $response = $this->get(route('admin.promotions.index'));
    $response->assertStatus(200);
});

test('gerente can create a promotion via livewire component', function () {
    app()[PermissionRegistrar::class]->forgetCachedPermissions();
    Permission::firstOrCreate(['name' => 'manage_catalog']);
    $role = Role::firstOrCreate(['name' => 'gerente']);
    $role->givePermissionTo('manage_catalog');

    $user = User::factory()->create();
    $user->assignRole('gerente');
    $this->actingAs($user);

    $category = Category::create([
        'nombre' => 'Test Category',
        'slug' => 'test-category',
        'activa' => true,
    ]);

    $product = Product::create([
        'nombre' => 'Promo Product',
        'slug' => 'promo-product',
        'category_id' => $category->id,
        'precio_compra' => 5.00,
        'precio_venta' => 15.00,
        'stock' => 10,
        'activo' => true,
    ]);

    Livewire::test('admin.promotions.index')
        ->set('product_id', $product->id)
        ->set('precio_promocion', 10.00)
        ->set('fecha_inicio', now()->toDateString())
        ->set('fecha_fin', now()->addDays(5)->toDateString())
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('promociones', [
        'product_id' => $product->id,
        'precio_promocion' => 10.00,
    ]);
});

test('gerente promotion creation fails if end date is before start date', function () {
    app()[PermissionRegistrar::class]->forgetCachedPermissions();
    Permission::firstOrCreate(['name' => 'manage_catalog']);
    $role = Role::firstOrCreate(['name' => 'gerente']);
    $role->givePermissionTo('manage_catalog');

    $user = User::factory()->create();
    $user->assignRole('gerente');
    $this->actingAs($user);

    $category = Category::create([
        'nombre' => 'Test Category',
        'slug' => 'test-category',
        'activa' => true,
    ]);

    $product = Product::create([
        'nombre' => 'Promo Product',
        'slug' => 'promo-product',
        'category_id' => $category->id,
        'precio_compra' => 5.00,
        'precio_venta' => 15.00,
        'stock' => 10,
        'activo' => true,
    ]);

    Livewire::test('admin.promotions.index')
        ->set('product_id', $product->id)
        ->set('precio_promocion', 10.00)
        ->set('fecha_inicio', now()->addDays(5)->toDateString())
        ->set('fecha_fin', now()->toDateString())
        ->call('save')
        ->assertHasErrors(['fecha_fin']);
});

test('gerente promotion creation fails if promotional price is higher than or equal to original price', function () {
    app()[PermissionRegistrar::class]->forgetCachedPermissions();
    Permission::firstOrCreate(['name' => 'manage_catalog']);
    $role = Role::firstOrCreate(['name' => 'gerente']);
    $role->givePermissionTo('manage_catalog');

    $user = User::factory()->create();
    $user->assignRole('gerente');
    $this->actingAs($user);

    $category = Category::create([
        'nombre' => 'Test Category',
        'slug' => 'test-category',
        'activa' => true,
    ]);

    $product = Product::create([
        'nombre' => 'Promo Product',
        'slug' => 'promo-product',
        'category_id' => $category->id,
        'precio_compra' => 5.00,
        'precio_venta' => 15.00,
        'stock' => 10,
        'activo' => true,
    ]);

    Livewire::test('admin.promotions.index')
        ->set('product_id', $product->id)
        ->set('precio_promocion', 20.00) // Original is 15.00, so 20.00 should fail
        ->set('fecha_inicio', now()->toDateString())
        ->set('fecha_fin', now()->addDays(5)->toDateString())
        ->call('save')
        ->assertHasErrors(['precio_promocion']);
});

test('homepage features list shows promo prices and discount percentage', function () {
    $category = Category::create([
        'nombre' => 'Test Caps',
        'slug' => 'test-caps',
        'activa' => true,
    ]);

    $product = Product::create([
        'nombre' => 'Promo Cap 123',
        'slug' => 'promo-cap-123',
        'descripcion' => 'A stylish cap in promo',
        'category_id' => $category->id,
        'precio_compra' => 5.00,
        'precio_venta' => 15.00,
        'stock' => 10,
        'activo' => true,
    ]);

    Promocion::create([
        'product_id' => $product->id,
        'precio_promocion' => 10.00,
        'fecha_inicio' => now()->subDay()->toDateString(),
        'fecha_fin' => now()->addDays(2)->toDateString(),
    ]);

    $response = $this->get(route('store.home'));
    $response->assertStatus(200);
    $response->assertSee('Promo Cap 123');
    $response->assertSee('$10.00');
    $response->assertSee('$15.00');
    $response->assertSee('-33% OFF');
});

test('category page shows product promo prices and discount percentage', function () {
    $category = Category::create([
        'nombre' => 'Test Caps',
        'slug' => 'test-caps',
        'activa' => true,
    ]);

    $product = Product::create([
        'nombre' => 'Promo Cap 123',
        'slug' => 'promo-cap-123',
        'descripcion' => 'A stylish cap in promo',
        'category_id' => $category->id,
        'precio_compra' => 5.00,
        'precio_venta' => 15.00,
        'stock' => 10,
        'activo' => true,
    ]);

    Promocion::create([
        'product_id' => $product->id,
        'precio_promocion' => 10.00,
        'fecha_inicio' => now()->subDay()->toDateString(),
        'fecha_fin' => now()->addDays(2)->toDateString(),
    ]);

    $response = $this->get(route('store.category', $category->slug));
    $response->assertStatus(200);
    $response->assertSee('Promo Cap 123');
    $response->assertSee('$10.00');
    $response->assertSee('$15.00');
    $response->assertSee('-33% OFF');
});

test('product detail page shows promo price and discount details', function () {
    $category = Category::create([
        'nombre' => 'Test Caps',
        'slug' => 'test-caps',
        'activa' => true,
    ]);

    $product = Product::create([
        'nombre' => 'Promo Cap 123',
        'slug' => 'promo-cap-123',
        'descripcion' => 'A stylish cap in promo',
        'category_id' => $category->id,
        'precio_compra' => 5.00,
        'precio_venta' => 15.00,
        'stock' => 10,
        'activo' => true,
    ]);

    Promocion::create([
        'product_id' => $product->id,
        'precio_promocion' => 10.00,
        'fecha_inicio' => now()->subDay()->toDateString(),
        'fecha_fin' => now()->addDays(2)->toDateString(),
    ]);

    $response = $this->get(route('store.product', $product->slug));
    $response->assertStatus(200);
    $response->assertSee('Promo Cap 123');
    $response->assertSee('$10.00');
    $response->assertSee('$15.00');
    $response->assertSee('-33% OFF');
});

test('adding promotional product to cart stores the promo price and details and shows them in cart page', function () {
    $category = Category::create([
        'nombre' => 'Test Caps',
        'slug' => 'test-caps',
        'activa' => true,
    ]);

    $product = Product::create([
        'nombre' => 'Promo Cap 123',
        'slug' => 'promo-cap-123',
        'category_id' => $category->id,
        'precio_compra' => 5.00,
        'precio_venta' => 15.00,
        'stock' => 10,
        'activo' => true,
    ]);

    Promocion::create([
        'product_id' => $product->id,
        'precio_promocion' => 10.00,
        'fecha_inicio' => now()->subDay()->toDateString(),
        'fecha_fin' => now()->addDays(2)->toDateString(),
    ]);

    // Test addToCart in home
    Livewire::test('store.home')
        ->call('addToCart', $product->id);

    $cart = session()->get('cart');
    expect($cart)->toBeArray();
    expect($cart[$product->id]['precio'])->toEqual(10.00);
    expect($cart[$product->id]['precio_original'])->toEqual(15.00);
    expect($cart[$product->id]['tiene_promocion'])->toBeTrue();
    expect($cart[$product->id]['porcentaje_descuento'])->toEqual(33);

    // Verify visual elements on the cart page
    $response = $this->get(route('store.cart'));
    $response->assertStatus(200);
    $response->assertSee('Promo Cap 123');
    $response->assertSee('$10.00');
    $response->assertSee('c/u');
    $response->assertSee('$15.00');
    $response->assertSee('-33% OFF');

    // Clear session cart
    session()->forget('cart');

    // Test addToCart in product-show
    Livewire::test('store.product-show', ['slug' => $product->slug])
        ->call('addToCart');

    $cart = session()->get('cart');
    expect($cart)->toBeArray();
    expect($cart[$product->id]['precio'])->toEqual(10.00);
    expect($cart[$product->id]['precio_original'])->toEqual(15.00);
    expect($cart[$product->id]['tiene_promocion'])->toBeTrue();
    expect($cart[$product->id]['porcentaje_descuento'])->toEqual(33);
});
