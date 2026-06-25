<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Volt::route('/', 'store.home')->name('store.home');
Route::get('/home', fn () => redirect('/'))->name('home');
Volt::route('/categoria/{slug}', 'store.category-show')->name('store.category');
Volt::route('/producto/{slug}', 'store.product-show')->name('store.product');
Volt::route('/carrito', 'store.cart')->name('store.cart');
Volt::route('/promociones', 'store.promotions')->name('store.promotions');
Volt::route('/pedido-confirmado/{order}', 'store.checkout-success')->name('store.checkout-success');

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;

Route::get('/pedido/{order}/pdf', function (Order $order) {
    if (auth()->id() !== $order->user_id && ! auth()->user()->hasAnyRole(['admin_sistema', 'vendedor', 'gerente'])) {
        abort(403);
    }

    return Pdf::loadView('pdf.nota-pedido', ['order' => $order])
        ->download('Nota-Pedido-'.$order->numero_pedido.'.pdf');
})->middleware(['auth', 'verified'])->name('store.order.pdf');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Volt::route('/mis-notas-de-pedido', 'store.orders')->name('store.orders');
});

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Volt::route('/categories', 'admin.categories.index')->name('categories.index')->middleware('can:manage_catalog');
    Volt::route('/products', 'admin.products.index')->name('products.index')->middleware('can:manage_catalog');
    Volt::route('/promotions', 'admin.promotions.index')->name('promotions.index')->middleware('can:manage_catalog');
    Volt::route('/orders', 'admin.orders.index')->name('orders.index')->middleware('can:manage_orders');
    Volt::route('/users', 'admin.users.index')->name('users.index')->middleware('can:manage_users');
});

require __DIR__.'/settings.php';
