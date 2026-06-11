<?php

use Livewire\Volt\Component;
use App\Models\Product;
use Livewire\Attributes\Layout;

new #[Layout('layouts.store')] class extends Component {
    public Product $product;
    public int $cantidad = 1;

    public function mount(string $slug)
    {
        $this->product = Product::where('slug', $slug)->where('activo', true)->with('category')->firstOrFail();
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
        $cart = session()->get('cart', []);
        $id = $this->product->id;

        if (isset($cart[$id])) {
            $cart[$id]['cantidad'] += $this->cantidad;
        } else {
            $cart[$id] = [
                'nombre' => $this->product->nombre,
                'precio' => $this->product->precio_venta,
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
                ->inRandomOrder()
                ->take(4)
                ->get(),
        ];
    }
}; ?>

<div>
    {{-- Breadcrumb --}}
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pt-6">
        <nav class="flex items-center text-sm text-zinc-400">
            <a href="{{ route('store.home') }}" class="hover:text-zinc-700 dark:hover:text-white transition-colors" wire:navigate>Inicio</a>
            <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            @if($product->category)
                <a href="{{ route('store.category', $product->category->slug) }}" class="hover:text-zinc-700 dark:hover:text-white transition-colors" wire:navigate>{{ $product->category->nombre }}</a>
                <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            @endif
            <span class="text-zinc-700 dark:text-zinc-200 truncate max-w-[200px]">{{ $product->nombre }}</span>
        </nav>
    </div>

    {{-- Product Detail --}}
    <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
            {{-- Image --}}
            <div class="aspect-square rounded-2xl overflow-hidden bg-zinc-100 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700">
                @if($product->imagen)
                    <img src="{{ asset('storage/' . $product->imagen) }}" alt="{{ $product->nombre }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-zinc-300 dark:text-zinc-600">
                        <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                @endif
            </div>

            {{-- Info --}}
            <div class="flex flex-col justify-center">
                @if($product->category)
                    <span class="text-sm font-medium text-rose-500 dark:text-rose-400 mb-2">{{ $product->category->nombre }}</span>
                @endif
                <h1 class="text-3xl md:text-4xl font-bold text-zinc-900 dark:text-white mb-4">{{ $product->nombre }}</h1>

                <div class="flex items-baseline gap-3 mb-6">
                    <span class="text-3xl font-black text-zinc-900 dark:text-white">${{ number_format($product->precio_venta, 2) }}</span>
                    <span class="text-sm text-zinc-400">IVA incluido (15%)</span>
                </div>

                @if($product->descripcion)
                    <p class="text-zinc-600 dark:text-zinc-400 mb-6 leading-relaxed">{{ $product->descripcion }}</p>
                @endif

                {{-- Stock Status --}}
                <div class="mb-6">
                    @if($product->stock > $product->stock_minimo)
                        <span class="inline-flex items-center gap-1.5 text-sm text-emerald-600 dark:text-emerald-400 font-medium">
                            <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                            En stock ({{ $product->stock }} disponibles)
                        </span>
                    @elseif($product->stock > 0)
                        <span class="inline-flex items-center gap-1.5 text-sm text-amber-600 dark:text-amber-400 font-medium">
                            <span class="w-2 h-2 bg-amber-500 rounded-full animate-pulse"></span>
                            ¡Últimas {{ $product->stock }} unidades!
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 text-sm text-red-600 dark:text-red-400 font-medium">
                            <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                            Agotado
                        </span>
                    @endif
                </div>

                {{-- Quantity + Add to Cart --}}
                @if($product->stock > 0)
                    <div class="flex items-center gap-4 mb-6">
                        <div class="flex items-center border border-zinc-300 dark:border-zinc-600 rounded-xl overflow-hidden">
                            <button wire:click="decrement" class="px-4 py-3 text-zinc-500 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors">−</button>
                            <span class="px-4 py-3 font-bold text-zinc-800 dark:text-zinc-200 min-w-[3rem] text-center">{{ $cantidad }}</span>
                            <button wire:click="increment" class="px-4 py-3 text-zinc-500 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors">+</button>
                        </div>
                        <button wire:click="addToCart" class="flex-1 py-3 px-6 bg-rose-500 text-white font-bold rounded-xl hover:bg-rose-600 shadow-lg hover:shadow-xl transition-all active:scale-[0.98]">
                            Agregar al Carrito
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- Related Products --}}
    @if($relatedProducts->count() > 0)
    <section class="bg-zinc-50 dark:bg-zinc-800/50 py-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <h2 class="text-xl font-bold mb-6">También te puede interesar</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @foreach($relatedProducts as $related)
                    <a href="{{ route('store.product', $related->slug) }}" class="group bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-700 overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300" wire:navigate>
                        <div class="aspect-square overflow-hidden bg-zinc-100 dark:bg-zinc-800">
                            @if($related->imagen)
                                <img src="{{ asset('storage/' . $related->imagen) }}" alt="{{ $related->nombre }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-zinc-300"><svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div>
                            @endif
                        </div>
                        <div class="p-3">
                            <h3 class="text-sm font-semibold truncate">{{ $related->nombre }}</h3>
                            <span class="text-sm font-bold text-rose-500">${{ number_format($related->precio_venta, 2) }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif
</div>
