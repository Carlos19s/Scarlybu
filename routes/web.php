<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CatalogoController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\Gerente\ProductoController as GerenteProducto;
use App\Http\Controllers\Gerente\CategoriaController as GerenteCategoria;
use App\Http\Controllers\Vendedor\PedidoController as VendedorPedido;
Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});
// ── PÚBLICO ──────────────────────────────────────────────────
Route::get('/', [CatalogoController::class, 'index'])->name('catalogo.index');

// ── AUTENTICADO ──────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/pedidos/confirmacion/{order}', [PedidoController::class, 'confirmacion'])->name('pedidos.confirmacion');
    Route::get('/mis-pedidos',                  [PedidoController::class, 'historial'])->name('pedidos.historial');
});

// ── GERENTE ──────────────────────────────────────────────────
Route::middleware('auth')->prefix('gerente')->name('gerente.')->group(function () {
    Route::resource('productos',  GerenteProducto::class);
});

// ── VENDEDOR ─────────────────────────────────────────────────
Route::middleware('auth')->prefix('vendedor')->name('vendedor.')->group(function () {
    Route::get('/pedidos',                   [VendedorPedido::class, 'index'])->name('pedidos.index');
    Route::get('/pedidos/{pedido}',          [VendedorPedido::class, 'show'])->name('pedidos.show');
    Route::post('/pedidos/{pedido}/estado',  [VendedorPedido::class, 'cambiarEstado'])->name('pedidos.estado');
    Route::get('/pedidos/{pedido}/whatsapp', [VendedorPedido::class, 'whatsapp'])->name('pedidos.whatsapp');
});
require __DIR__.'/settings.php';
