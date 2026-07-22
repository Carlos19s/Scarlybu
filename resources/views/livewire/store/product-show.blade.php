<?php

use Livewire\Volt\Component;
use App\Models\Product;
use Livewire\Attributes\Layout;

new #[Layout('layouts.store')] class extends Component {
    public Product $product;
    public int $cantidad = 1;

    public function mount(string $slug)
    {
        $today = now()->toDateString();
        $this->product = Product::where('slug', $slug)
            ->where('activo', true)
            ->with(['category', 'promociones' => fn ($q) => $q
                ->where('fecha_inicio', '<=', $today)
                ->where('fecha_fin', '>=', $today)
            ])
            ->firstOrFail();
    }

    public function increment()
    {
        if ($this->cantidad < $this->product->stock) {
            $this->cantidad++;
        }
    }

    public function decrement()
    {
        if ($this->cantidad > 1) {
            $this->cantidad--;
        }
    }

    public function addToCart()
    {
        if ($this->product->stock <= 0 || $this->cantidad > $this->product->stock) {
            return;
        }

        $cart = session()->get('cart', []);
        $id = $this->product->id;
        $currentInCart = isset($cart[$id]) ? $cart[$id]['cantidad'] : 0;

        // Check total won't exceed stock
        if (($currentInCart + $this->cantidad) > $this->product->stock) {
            return;
        }

        // Stock no se descuenta aquí

        if (isset($cart[$id])) {
            $cart[$id]['cantidad'] += $this->cantidad;
        } else {
            $cart[$id] = [
                'nombre' => $this->product->nombre,
                'precio' => $this->product->precio_actual,
                'precio_original' => $this->product->precio_venta,
                'tiene_promocion' => $this->product->tiene_promocion,
                'porcentaje_descuento' => $this->product->porcentaje_descuento,
                'imagen' => $this->product->imagen,
                'cantidad' => $this->cantidad,
            ];
        }

        session()->put('cart', $cart);
        $this->cantidad = 1;
        return $this->redirect(route('store.cart'), navigate: true);
    }

    public function with(): array
    {
        return [
            'relatedProducts' => Product::where('category_id', $this->product->category_id)
                ->where('id', '!=', $this->product->id)
                ->where('activo', true)
                ->with(['promociones' => fn ($q) => $q
                    ->where('fecha_inicio', '<=', now()->toDateString())
                    ->where('fecha_fin', '>=', now()->toDateString())
                ])
                ->inRandomOrder()
                ->take(4)
                ->get(),
        ];
    }
}; ?>

<div>
    {{-- Breadcrumb --}}
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pt-6">
        <nav class="flex items-center text-sm text-[#b8bac1]">
            <a href="{{ route('store.home') }}" class="hover:text-white transition-colors" wire:navigate>Inicio</a>
            <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            @if($product->category)
                <a href="{{ route('store.category', $product->category->slug) }}" class="hover:text-white transition-colors" wire:navigate>{{ $product->category->nombre }}</a>
                <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            @endif
            <span class="text-[#f2f2f7] truncate max-w-[200px]">{{ $product->nombre }}</span>
        </nav>
    </div>

    {{-- Product Detail --}}
    <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
            {{-- Image --}}
            <div class="aspect-square rounded-2xl overflow-hidden bg-[#1f2128] border border-white/10 relative">
                @if($product->imagen)
                    <img src="{{ $product->imagen_url }}" alt="{{ $product->nombre }}" class="w-full h-full object-contain p-4">   
                @else
                    <div class="w-full h-full flex items-center justify-center text-[#555]">
                        <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                @endif
                @if($product->tiene_promocion)
                    <span class="absolute top-4 left-4 px-3 py-1 rounded-lg text-xs font-black text-white uppercase tracking-wider shadow-md animate-pulse" style="background:linear-gradient(135deg,#ff6b81,#e53e3e);">
                        Oferta
                    </span>
                @endif
            </div>

            {{-- Info --}}
            <div class="flex flex-col justify-center">
                @if($product->category)
                    <span class="text-sm font-medium text-[#55d6ff] mb-2">{{ $product->category->nombre }}</span>
                @endif
                <h1 class="text-3xl md:text-4xl font-bold text-[#f2f2f7] mb-4">{{ $product->nombre }}</h1>

                <div class="mb-6">
                    @if($product->tiene_promocion)
                        <div class="flex items-baseline gap-3">
                            <span class="text-3xl font-black text-[#ff6b81]">${{ number_format($product->precio_actual, 2) }}</span>
                            <span class="text-lg text-[#666] line-through">${{ number_format($product->precio_venta, 2) }}</span>
                            <span class="text-xs font-bold text-[#ff6b81] bg-[#ff6b81]/10 px-2.5 py-1 rounded">
                                -{{ $product->porcentaje_descuento }}% OFF
                            </span>
                        </div>
                    @else
                        <div class="flex items-baseline gap-3">
                            <span class="text-3xl font-black text-[#f2f2f7]">${{ number_format($product->precio_venta, 2) }}</span>
                        </div>
                    @endif
                    <span class="text-xs text-[#b8bac1] block mt-1">IVA incluido (15%)</span>
                </div>

                @if($product->descripcion)
                    <p class="text-[#b8bac1] mb-6 leading-relaxed">{{ $product->descripcion }}</p>
                @endif

                {{-- Stock Status --}}
                <div class="mb-6">
                    @if($product->stock > $product->stock_minimo)
                        <span class="inline-flex items-center gap-1.5 text-sm text-emerald-400 font-medium">
                            <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                            En stock ({{ $product->stock }} disponibles)
                        </span>
                    @elseif($product->stock > 0)
                        <span class="inline-flex items-center gap-1.5 text-sm text-amber-400 font-medium">
                            <span class="w-2 h-2 bg-amber-500 rounded-full animate-pulse"></span>
                            ¡Últimas {{ $product->stock }} unidades!
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 text-sm text-red-400 font-medium">
                            <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                            Agotado
                        </span>
                    @endif
                </div>

                {{-- Quantity + Add to Cart --}}
                @if($product->stock > 0)
                    <div class="flex items-center gap-4 mb-6">
                        <div class="flex items-center border border-white/10 rounded-xl overflow-hidden bg-[#1f2128]">
                            <button wire:click="decrement" class="px-4 py-3 text-[#b8bac1] hover:bg-white/5 transition-colors">−</button>
                            <span class="px-4 py-3 font-bold text-[#f2f2f7] min-w-[3rem] text-center">{{ $cantidad }}</span>
                            <button wire:click="increment" class="px-4 py-3 text-[#b8bac1] hover:bg-white/5 transition-colors">+</button>
                        </div>
                        <button wire:click="addToCart" class="flex-1 py-3 px-6 text-[#0d1117] font-bold rounded-xl shadow-lg transition-all active:scale-[0.98]" style="background:linear-gradient(135deg,#55d6ff,#38b2ac);">
                            Agregar al Carrito
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- Related Products --}}
    @if($relatedProducts->count() > 0)
    <section class="py-12" style="background:#121318; border-top:1px solid rgba(255,255,255,0.06);">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <h2 class="text-xl font-bold mb-6 text-[#f2f2f7]">También te puede interesar</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @foreach($relatedProducts as $related)
                    <a href="{{ route('store.product', $related->slug) }}" class="group rounded-2xl border border-white/10 overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 relative" style="background:#1f2128;" wire:navigate>
                        <div class="aspect-square overflow-hidden relative" style="background:#292b33;">
                            @if($related->imagen)
                             <img src="{{ $related->imagen_url }}" alt="{{ $related->nombre }}" class="w-full h-full object-contain p-2 group-hover:scale-110 transition-transform duration-500">    
                            @else
                                <div class="w-full h-full flex items-center justify-center text-[#555]"><svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div>
                            @endif
                            @if($related->tiene_promocion)
                                <span class="absolute top-2 left-2 px-1.5 py-0.5 rounded text-[8px] font-bold text-white uppercase tracking-wider shadow-sm" style="background:linear-gradient(135deg,#ff6b81,#e53e3e);">
                                    Oferta
                                </span>
                            @endif
                        </div>
                        <div class="p-3">
                            <h3 class="text-sm font-semibold truncate text-[#f2f2f7]">{{ $related->nombre }}</h3>
                            @if($related->tiene_promocion)
                                <div class="flex items-baseline gap-1.5 mt-1">
                                    <span class="text-sm font-black text-[#ff6b81]">${{ number_format($related->precio_actual, 2) }}</span>
                                    <span class="text-xs text-[#666] line-through">${{ number_format($related->precio_venta, 2) }}</span>
                                </div>
                            @else
                                <span class="text-sm font-bold text-[#55d6ff] mt-1 block">${{ number_format($related->precio_venta, 2) }}</span>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif
</div>
