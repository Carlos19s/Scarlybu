<?php

use Livewire\Volt\Component;
use App\Models\Product;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

new #[Layout('layouts.store')] #[Title('Ofertas y Promociones - Scarlybu')] class extends Component {
    use WithPagination;

    public function addToCart(int $productId)
    {
        $product = Product::findOrFail($productId);

        if ($product->stock <= 0) {
            return;
        }

        $cart = session()->get('cart', []);
        $currentInCart = isset($cart[$productId]) ? $cart[$productId]['cantidad'] : 0;

        if ($currentInCart >= $product->stock) {
            return;
        }

        // Stock no se descuenta aquí

        if (isset($cart[$productId])) {
            $cart[$productId]['cantidad']++;
        } else {
            $cart[$productId] = [
                'nombre' => $product->nombre,
                'precio' => $product->precio_venta,
                'imagen' => $product->imagen,
                'cantidad' => 1,
            ];
        }

        session()->put('cart', $cart);
        $this->dispatch('cart-updated');
    }

    public function with(): array
    {
        $today = now()->toDateString();
        $promotions = \App\Models\Promocion::where('fecha_inicio', '<=', $today)
            ->where('fecha_fin', '>=', $today)
            ->with(['product.category'])
            ->latest()
            ->paginate(12);

        return [
            'promotions' => $promotions,
        ];
    }
}; ?>

<div>
    {{-- Header --}}
    <section class="bg-gradient-to-r from-rose-600 via-pink-600 to-amber-500 text-white py-14 shadow-lg">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <nav class="flex items-center text-sm text-white/80 mb-4">
                <a href="{{ route('store.home') }}" class="hover:text-white transition-colors" wire:navigate>Inicio</a>
                <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                <span class="text-white font-semibold">Promociones</span>
            </nav>
            <div class="flex items-center gap-3">
                <span class="text-4xl md:text-5xl font-black">⚡</span>
                <div>
                    <h1 class="text-3xl md:text-4xl font-black tracking-tight uppercase">Ofertas Especiales</h1>
                    <p class="mt-2 text-rose-50/90 max-w-xl text-sm sm:text-base font-medium">
                        Productos seleccionados con descuentos increíbles por tiempo limitado. ¡Compra ahora antes de que se agoten!
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Products Grid --}}
    <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
            @forelse($promotions as $promo)
                @php
                    $product = $promo->product;
                    $descuentoPorcentaje = $product->precio_venta > 0 
                        ? round((1 - ($promo->precio_promocion / $product->precio_venta)) * 100) 
                        : 0;
                @endphp
                <div class="group bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                    <a href="{{ route('store.product', $product->slug) }}" wire:navigate>
                        <div class="aspect-square overflow-hidden bg-slate-100 dark:bg-slate-800 relative">
                            @if($product->imagen)
                             <img src="{{ $product->imagen_url }}" alt="{{ $product->nombre }}" class="w-full h-full object-contain p-2 group-hover:scale-110 transition-transform duration-500">    
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-300 dark:text-slate-600">
                                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                            <!-- Promo Badge -->
                            <span class="absolute top-2.5 left-2.5 px-2 py-0.5 rounded-md bg-rose-600 text-[10px] font-black text-white uppercase tracking-wider shadow-sm animate-pulse">
                                Oferta
                            </span>
                        </div>
                    </a>
                    <div class="p-4">
                        <span class="text-xs font-medium text-emerald-500 dark:text-emerald-400">{{ $product->category?->nombre }}</span>
                        <a href="{{ route('store.product', $product->slug) }}" wire:navigate>
                            <h3 class="font-semibold text-sm mt-1 text-slate-800 dark:text-slate-100 group-hover:text-emerald-500 transition-colors truncate">{{ $product->nombre }}</h3>
                        </a>
                        
                        <!-- Temu-style Pricing -->
                        <div class="mt-3 flex flex-wrap items-baseline gap-1.5">
                            <span class="text-lg font-black text-rose-600 dark:text-rose-500">${{ number_format($promo->precio_promocion, 2) }}</span>
                            <span class="text-xs text-slate-400 dark:text-slate-500 line-through">${{ number_format($product->precio_venta, 2) }}</span>
                        </div>
                        
                        <!-- Discount Badge and Add to Cart -->
                        <div class="mt-1 flex items-center justify-between">
                            <span class="text-[10px] sm:text-xs font-bold text-rose-600 dark:text-rose-500 bg-rose-500/10 px-1.5 py-0.5 rounded">
                                -{{ $descuentoPorcentaje }}% OFF
                            </span>
                            @if($product->stock > 0)
                                <button wire:click="addToCart({{ $product->id }})" class="p-2 rounded-full bg-emerald-500 text-white hover:bg-emerald-600 shadow-md hover:shadow-lg transition-all hover:scale-110 active:scale-95">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                                </button>
                            @else
                                <span class="px-2 py-1 rounded-full text-[10px] font-bold text-red-400 bg-red-500/10">Agotado</span>
                            @endif
                        </div>
                        
                        @if($product->stock <= $product->stock_minimo && $product->stock > 0)
                            <span class="text-xs text-amber-500 font-medium mt-2 block">¡Últimas unidades!</span>
                        @elseif($product->stock <= 0)
                            <span class="text-xs text-red-500 font-medium mt-2 block">Agotado</span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-16 text-slate-500 dark:text-slate-400">
                    <p class="text-lg">No hay ofertas disponibles en este momento.</p>
                    <a href="{{ route('store.home') }}" class="inline-block mt-4 text-emerald-500 hover:text-emerald-600 font-medium" wire:navigate>← Volver al inicio</a>
                </div>
            @endforelse
        </div>

        @if($promotions->hasPages())
            <div class="mt-10">
                {{ $promotions->links() }}
            </div>
        @endif
    </section>
</div>
