<?php

use Livewire\Volt\Component;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Str;

new #[Layout('layouts.store')] #[Title('Carrito de Compras - Scarlybu')] class extends Component {
    public string $cliente_nombre = '';
    public string $cliente_documento = '';
    public string $cliente_telefono = '';
    public string $cliente_correo = '';
    public string $cliente_direccion = '';
    public string $notas = '';

    public bool $showCheckout = false;

    public function getCartItemsProperty(): array
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return [];
        }

        $products = Product::whereIn('id', array_keys($cart))
            ->with(['promociones'])
            ->get()
            ->keyBy('id');

        $items = [];
        foreach ($cart as $productId => $item) {
            $product = $products->get($productId);
            if ($product) {
                $items[$productId] = [
                    'nombre' => $product->nombre,
                    'precio' => $product->precio_actual,
                    'precio_original' => $product->precio_venta,
                    'tiene_promocion' => $product->tiene_promocion,
                    'porcentaje_descuento' => $product->porcentaje_descuento,
                    'imagen' => $product->imagen,
                    'cantidad' => $item['cantidad'],
                ];
            } else {
                $items[$productId] = $item;
            }
        }

        return $items;
    }

    public function getCartProperty(): array
    {
        return $this->cart_items;
    }

    public function getTotalProperty(): float
    {
        $total = 0;
        foreach ($this->cart_items as $item) {
            $total += $item['precio'] * $item['cantidad'];
        }
        return $total;
    }

    public function increment(int $productId)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$productId])) {
            $product = Product::find($productId);
            // Validar que no sobrepase el stock disponible
            if (! $product || $cart[$productId]['cantidad'] >= $product->stock) {
                return;
            }

            $cart[$productId]['cantidad']++;
            session()->put('cart', $cart);
            $this->dispatch('cart-updated');
        }
    }

    public function decrement(int $productId)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$productId])) {

            if ($cart[$productId]['cantidad'] > 1) {
                $cart[$productId]['cantidad']--;
            } else {
                unset($cart[$productId]);
            }
            session()->put('cart', $cart);
            $this->dispatch('cart-updated');
        }
    }

    public function removeItem(int $productId)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
            $this->dispatch('cart-updated');
        }
    }

    public function clearCart()
    {
        $cart = session()->get('cart', []);

        session()->forget('cart');
        $this->dispatch('cart-updated');
    }

    public function checkout()
    {
        $this->showCheckout = true;
    }

    public function placeOrder()
    {
        $this->validate([
            'cliente_nombre' => 'required|string|max:255',
            'cliente_documento' => 'required|string|max:20',
            'cliente_telefono' => 'required|string|max:20',
            'cliente_correo' => 'required|email|max:255',
            'cliente_direccion' => 'required|string|max:500',
        ]);

        $cart = $this->cart;

        if (empty($cart)) {
            return;
        }

        // Require authentication to place an order
        if (!auth()->check()) {
            return $this->redirect(route('login'), navigate: true);
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['precio'] * $item['cantidad'];
        }

        // Verify stock for all items
        foreach ($cart as $productId => $item) {
            $product = Product::find($productId);
            if (!$product || $product->stock < $item['cantidad']) {
                session()->flash('error', 'Lo sentimos, el producto "' . $item['nombre'] . '" ya no tiene stock suficiente (' . ($product ? $product->stock : 0) . ' disponibles).');
                return;
            }
        }

        $order = Order::create([
            'numero_pedido' => 'SC-' . strtoupper(uniqid()),
            'user_id' => auth()->id(),
            'cliente_nombre' => $this->cliente_nombre,
            'cliente_documento' => $this->cliente_documento,
            'cliente_telefono' => $this->cliente_telefono,
            'cliente_correo' => $this->cliente_correo,
            'cliente_direccion' => $this->cliente_direccion,
            'notas' => $this->notas,
            'subtotal' => round($total / 1.15, 2),
            'iva' => round($total - ($total / 1.15), 2),
            'total' => $total,
            'estado' => 'no_revisado',
            'metodo_pago' => 'pendiente',
        ]);

        foreach ($cart as $productId => $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $productId,
                'cantidad' => $item['cantidad'],
                'precio_unitario' => $item['precio'],
                'iva_porcentaje' => 15.00,
                'subtotal' => $item['precio'] * $item['cantidad'],
            ]);
            
            // Decrement stock now that the order is confirmed
            $product = Product::find($productId);
            if ($product) {
                $product->decrement('stock', $item['cantidad']);
            }
        }

        session()->forget('cart');

        return $this->redirect(route('store.checkout-success', ['order' => $order->id]), navigate: true);
    }
}; ?>

<div>
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-8">🛒 Tu Carrito</h1>

        @if(count($this->cart) > 0)
            <div class="space-y-4 mb-8">
                @foreach($this->cart as $productId => $item)
                    <div class="flex items-center gap-4 p-4 bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-700 shadow-sm">
                        {{-- Image --}}
                        <div class="w-20 h-20 rounded-xl overflow-hidden bg-zinc-100 dark:bg-zinc-800 shrink-0">
                            @if($item['imagen'])
                            <img src="{{ str_starts_with($item['imagen'], 'http') ? $item['imagen'] : asset('storage/' . $item['imagen']) }}" alt="{{ $item['nombre'] }}" class="w-full h-full object-contain p-2">    
                            @else
                                <div class="w-full h-full flex items-center justify-center text-zinc-300">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                        </div>

                        {{-- Details --}}
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-zinc-800 dark:text-zinc-100 truncate">{{ $item['nombre'] }}</h3>
                            @if(isset($item['tiene_promocion']) && $item['tiene_promocion'])
                                <div class="flex flex-wrap items-center gap-1.5 mt-0.5">
                                    <span class="text-sm font-bold text-rose-600 dark:text-rose-500">${{ number_format($item['precio'], 2) }} <span class="text-xs font-normal text-zinc-500">c/u</span></span>
                                    <span class="text-xs text-zinc-400 line-through">${{ number_format($item['precio_original'], 2) }}</span>
                                    <span class="text-[9px] font-black text-rose-600 dark:text-rose-400 bg-rose-500/10 px-1.5 py-0.5 rounded">
                                        -{{ $item['porcentaje_descuento'] }}% OFF
                                    </span>
                                </div>
                            @else
                                <p class="text-sm text-zinc-500">${{ number_format($item['precio'], 2) }} c/u</p>
                            @endif
                        </div>

                        {{-- Quantity Controls --}}
                        <div class="flex items-center border border-zinc-300 dark:border-zinc-600 rounded-lg overflow-hidden">
                            <button wire:click="decrement({{ $productId }})" class="px-3 py-2 text-zinc-500 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors text-sm">−</button>
                            <span class="px-3 py-2 font-bold text-sm text-zinc-800 dark:text-zinc-200 min-w-[2.5rem] text-center">{{ $item['cantidad'] }}</span>
                            <button wire:click="increment({{ $productId }})" class="px-3 py-2 text-zinc-500 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors text-sm">+</button>
                        </div>

                        {{-- Subtotal --}}
                        <div class="text-right min-w-[80px]">
                            <span class="font-bold text-zinc-900 dark:text-white">${{ number_format($item['precio'] * $item['cantidad'], 2) }}</span>
                        </div>

                        {{-- Remove --}}
                        <button wire:click="removeItem({{ $productId }})" class="p-2 text-zinc-400 hover:text-red-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                @endforeach
            </div>

            {{-- Summary --}}
            <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-700 p-6 shadow-sm mb-8">
                <div class="space-y-3">
                    @php
                        $subtotalSinIva = round($this->total / 1.15, 2);
                        $iva = round($this->total - $subtotalSinIva, 2);
                    @endphp
                    <div class="flex justify-between text-sm text-zinc-500">
                        <span>Subtotal (sin IVA)</span>
                        <span>${{ number_format($subtotalSinIva, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm text-zinc-500">
                        <span>IVA (15%)</span>
                        <span>${{ number_format($iva, 2) }}</span>
                    </div>
                    <div class="border-t border-zinc-200 dark:border-zinc-700 pt-3 flex justify-between text-lg font-bold text-zinc-900 dark:text-white">
                        <span>Total</span>
                        <span>${{ number_format($this->total, 2) }}</span>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            @if(!$showCheckout)
                <div class="flex gap-4">
                    <a href="{{ route('store.home') }}" class="flex-1 py-3 text-center border border-zinc-300 dark:border-zinc-600 rounded-xl text-zinc-600 dark:text-zinc-400 hover:bg-zinc-50 dark:hover:bg-zinc-800 font-medium transition-colors" wire:navigate>
                        ← Seguir Comprando
                    </a>
                    <button wire:click="checkout" class="flex-1 py-3 bg-rose-500 text-white font-bold rounded-xl hover:bg-rose-600 shadow-lg hover:shadow-xl transition-all">
                        Confirmar Pedido →
                    </button>
                </div>
            @else
                @if(!auth()->check())
                    <!-- Not Logged In State -->
                    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-3xl p-8 shadow-sm text-center">
                        <div class="w-16 h-16 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-zinc-900 dark:text-white mb-2">Inicia sesión para continuar</h3>
                        <p class="text-zinc-500 dark:text-zinc-400 mb-6">Para confirmar tu pedido y generar la nota de venta, necesitas acceder a tu cuenta.</p>
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-6 py-3 bg-rose-500 text-white font-bold rounded-xl shadow-lg hover:bg-rose-600 hover:shadow-xl transition-all" wire:navigate>
                            Iniciar Sesión / Registrarse
                        </a>
                    </div>
                @else
                    {{-- Checkout Form --}}
                    <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-700 p-6 shadow-sm">
                        <h2 class="text-xl font-bold text-zinc-900 dark:text-white mb-6">📋 Datos de Envío</h2>

                        @if (session()->has('error'))
                            <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 border border-red-200 dark:border-red-800/50 rounded-xl font-medium text-sm">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form wire:submit="placeOrder" class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Nombre Completo *</label>
                                    <input wire:model="cliente_nombre" type="text" required class="w-full rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 px-4 py-2.5 text-sm text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-rose-500 focus:border-transparent outline-none transition-all">
                                    @error('cliente_nombre') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Cédula / RUC *</label>
                                    <input wire:model="cliente_documento" type="text" required class="w-full rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 px-4 py-2.5 text-sm text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-rose-500 focus:border-transparent outline-none transition-all">
                                    @error('cliente_documento') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Teléfono *</label>
                                    <input wire:model="cliente_telefono" type="tel" required class="w-full rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 px-4 py-2.5 text-sm text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-rose-500 focus:border-transparent outline-none transition-all">
                                    @error('cliente_telefono') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Correo Electrónico *</label>
                                    <input wire:model="cliente_correo" type="email" required class="w-full rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 px-4 py-2.5 text-sm text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-rose-500 focus:border-transparent outline-none transition-all">
                                    @error('cliente_correo') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Dirección de Entrega *</label>
                                <textarea wire:model="cliente_direccion" rows="2" required class="w-full rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 px-4 py-2.5 text-sm text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-rose-500 focus:border-transparent outline-none transition-all resize-none"></textarea>
                                @error('cliente_direccion') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Notas (opcional)</label>
                                <textarea wire:model="notas" rows="2" class="w-full rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 px-4 py-2.5 text-sm text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-rose-500 focus:border-transparent outline-none transition-all resize-none" placeholder="Instrucciones de entrega, color, talla..."></textarea>
                            </div>

                            <div class="flex gap-4 pt-4">
                                <button type="button" wire:click="$set('showCheckout', false)" class="flex-1 py-3 border border-zinc-300 dark:border-zinc-600 rounded-xl text-zinc-600 dark:text-zinc-400 hover:bg-zinc-50 dark:hover:bg-zinc-800 font-medium transition-colors">
                                    ← Volver
                                </button>
                                <button type="submit" class="flex-1 py-3 bg-emerald-500 text-white font-bold rounded-xl hover:bg-emerald-600 shadow-lg hover:shadow-xl transition-all">
                                    ✅ Confirmar y Pedir
                                </button>
                            </div>
                        </form>
                    </div>
                @endif
            @endif

        @else
            {{-- Empty Cart --}}
            <div class="text-center py-20">
                <div class="text-6xl mb-4">🛒</div>
                <h2 class="text-2xl font-bold text-zinc-700 dark:text-zinc-300 mb-2">Tu carrito está vacío</h2>
                <p class="text-zinc-500 dark:text-zinc-400 mb-6">¡Explora nuestra tienda y encuentra lo que te encanta!</p>
                <a href="{{ route('store.home') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-rose-500 text-white font-bold rounded-xl shadow-lg hover:bg-rose-600 hover:shadow-xl transition-all" wire:navigate>
                    Ir a la Tienda →
                </a>
            </div>
        @endif
    </div>
</div>
