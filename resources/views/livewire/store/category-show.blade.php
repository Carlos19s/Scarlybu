<?php

use Livewire\Volt\Component;
use App\Models\Category;
use App\Models\Product;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

new #[Layout('layouts.store')] class extends Component {
    use WithPagination;

    public Category $category;

    public function mount(string $slug)
    {
        $this->category = Category::where('slug', $slug)
            ->where('activa', true)
            ->with(['parent.children', 'children'])
            ->firstOrFail();
    }

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
                'precio' => $product->precio_actual,
                'precio_original' => $product->precio_venta,
                'tiene_promocion' => $product->tiene_promocion,
                'porcentaje_descuento' => $product->porcentaje_descuento,
                'imagen' => $product->imagen,
                'cantidad' => 1,
            ];
        }

        session()->put('cart', $cart);
        $this->dispatch('cart-updated');
    }

    public function with(): array
    {
        return [
            'products' => Product::whereIn('category_id', $this->category->getAllCategoryIds())
                ->where('activo', true)
                ->with(['category', 'promociones' => fn ($q) => $q
                    ->where('fecha_inicio', '<=', now()->toDateString())
                    ->where('fecha_fin', '>=', now()->toDateString())
                ])
                ->latest()
                ->paginate(12),
        ];
    }
}; ?>

<div>
    {{-- Category Header --}}
    <section class="bg-gradient-to-r from-slate-900 to-slate-800 text-white py-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <nav class="flex items-center text-sm text-slate-400 mb-4">
                <a href="{{ route('store.home') }}" class="hover:text-white transition-colors" wire:navigate>Inicio</a>
                <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                <span class="text-white">{{ $category->nombre }}</span>
            </nav>
            <h1 class="text-3xl md:text-4xl font-bold">{{ $category->nombre }}</h1>
            @if($category->descripcion)
                <p class="mt-2 text-slate-300 max-w-xl">{{ $category->descripcion }}</p>
            @endif

            {{-- Subcategories / Sibling Categories Filter --}}
            @php
                $relatedCategories = $category->parent_id 
                    ? $category->parent->children()->where('activa', true)->get()
                    : $category->children()->where('activa', true)->get();
            @endphp

            @if($relatedCategories->count() > 0)
                <div class="mt-6 flex flex-wrap gap-2">
                    @if($category->parent_id)
                        <a href="{{ route('store.category', $category->parent->slug) }}" class="px-4 py-1.5 rounded-full text-sm font-medium border border-slate-600 text-slate-300 hover:bg-slate-700 hover:text-white transition-colors" wire:navigate>
                            Todas las {{ $category->parent->nombre }}
                        </a>
                    @endif
                    
                    @foreach($relatedCategories as $relCat)
                        <a href="{{ route('store.category', $relCat->slug) }}" class="px-4 py-1.5 rounded-full text-sm font-medium transition-colors {{ $category->id === $relCat->id ? 'bg-emerald-500 text-white border border-emerald-500' : 'bg-slate-800 border border-slate-700 text-slate-300 hover:bg-slate-700 hover:text-white' }}" wire:navigate>
                            {{ $relCat->nombre }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- Products Grid --}}
    <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
            @forelse($products as $product)
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
                            @if($product->tiene_promocion)
                                <span class="absolute top-2 left-2 px-1.5 py-0.5 rounded bg-rose-600 text-[8px] font-bold text-white uppercase tracking-wider shadow-sm">
                                    Oferta
                                </span>
                            @endif
                        </div>
                    </a>
                    <div class="p-4">
                        <a href="{{ route('store.product', $product->slug) }}" wire:navigate>
                            <h3 class="font-semibold text-sm text-slate-800 dark:text-slate-100 group-hover:text-emerald-500 transition-colors truncate">{{ $product->nombre }}</h3>
                        </a>
                        <div class="flex items-center justify-between mt-3">
                            <div>
                                @if($product->tiene_promocion)
                                    <div class="flex flex-col">
                                        <div class="flex items-baseline gap-1.5">
                                            <span class="text-base font-black text-rose-600 dark:text-rose-500">${{ number_format($product->precio_actual, 2) }}</span>
                                            <span class="text-xs text-slate-400 dark:text-slate-500 line-through">${{ number_format($product->precio_venta, 2) }}</span>
                                        </div>
                                        <span class="text-[9px] font-bold text-rose-600 dark:text-rose-400 bg-rose-500/10 px-1.5 py-0.5 rounded self-start mt-0.5">
                                            -{{ $product->porcentaje_descuento }}% OFF
                                        </span>
                                    </div>
                                @else
                                    <span class="text-lg font-bold text-slate-900 dark:text-white">${{ number_format($product->precio_venta, 2) }}</span>
                                    <span class="text-[10px] text-slate-400 block">IVA incluido</span>
                                @endif
                            </div>
                            @if($product->stock > 0)
                                <button wire:click="addToCart({{ $product->id }})" class="p-2 rounded-full bg-emerald-500 text-white hover:bg-emerald-600 shadow-md hover:shadow-lg transition-all hover:scale-110 active:scale-95">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                                </button>
                            @else
                                <span class="px-2 py-1 rounded-full text-[10px] font-bold text-red-400 bg-red-500/10">Agotado</span>
                            @endif
                        </div>
                        @if($product->stock <= $product->stock_minimo && $product->stock > 0)
                            <span class="text-xs text-amber-500 font-medium mt-1 block">¡Últimas unidades!</span>
                        @elseif($product->stock <= 0)
                            <span class="text-xs text-red-500 font-medium mt-1 block">Agotado</span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-16 text-slate-500">
                    <p class="text-lg">No hay productos en esta categoría aún.</p>
                    <a href="{{ route('store.home') }}" class="inline-block mt-4 text-emerald-500 hover:text-emerald-600 font-medium" wire:navigate>← Volver al inicio</a>
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $products->links() }}
        </div>
    </section>
</div>
