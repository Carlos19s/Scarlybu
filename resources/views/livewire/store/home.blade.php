<?php

use Livewire\Volt\Component;
use App\Models\Product;
use App\Models\Category;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

new #[Layout('layouts.store')] #[Title('Scarlybu - Tu Tienda de Moda')] class extends Component {
    public function addToCart(int $productId)
    {
        $product = Product::findOrFail($productId);
        $cart = session()->get('cart', []);

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
        return [
            'categories' => Category::whereNull('parent_id')->where('activa', true)->with('children')->get(),
            'featuredProducts' => Product::where('activo', true)->with('category')->latest()->take(8)->get(),
        ];
    }
}; ?>

<div>
    {{-- Hero Banner --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-emerald-600 via-teal-500 to-cyan-500">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.4\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        </div>
        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-20 md:py-28 text-center text-white">
            <h1 class="text-4xl md:text-6xl font-black tracking-tight mb-4 drop-shadow-lg">
                Bienvenido a <span class="text-amber-200">Scarlybu</span>
            </h1>
            <p class="text-lg md:text-xl max-w-2xl mx-auto opacity-90 mb-8">
                Descubre nuestra colección de gorras, accesorios, cosméticos, ropa y zapatos. ¡Todo lo que necesitas en un solo lugar!
            </p>
            <a href="#categorias" class="inline-flex items-center gap-2 px-6 py-3 bg-white text-emerald-600 font-bold rounded-full shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">
                Explorar Categorías
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
            </a>
        </div>
    </section>

    {{-- Categories Grid --}}
    <section id="categorias" class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-16">
        <h2 class="text-2xl md:text-3xl font-bold text-center mb-2 text-slate-900 dark:text-slate-100">Nuestras Categorías</h2>
        <p class="text-center text-slate-500 dark:text-slate-400 mb-10">Encuentra exactamente lo que buscas</p>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
            @php
                $icons = [
                    'gorras' => '🧢',
                    'accesorios' => '💍',
                    'cosmeticos' => '💄',
                    'ropa' => '👗',
                    'zapatos' => '👟',
                ];
                $gradients = [
                    'gorras' => 'from-slate-700 to-slate-900',
                    'accesorios' => 'from-amber-500 to-orange-600',
                    'cosmeticos' => 'from-pink-500 to-rose-600',
                    'ropa' => 'from-emerald-500 to-teal-600',
                    'zapatos' => 'from-blue-500 to-indigo-600',
                ];
            @endphp
            @foreach($categories as $category)
                <a href="{{ route('store.category', $category->slug) }}"
                   class="group relative flex flex-col items-center justify-center p-6 rounded-2xl bg-gradient-to-br {{ $gradients[$category->slug] ?? 'from-slate-500 to-slate-700' }} text-white shadow-md hover:shadow-xl hover:scale-105 transition-all duration-300"
                   wire:navigate>
                    <span class="text-4xl mb-2 group-hover:scale-110 transition-transform">{{ $icons[$category->slug] ?? '📦' }}</span>
                    <span class="font-bold text-sm">{{ $category->nombre }}</span>
                    <span class="text-xs opacity-80">{{ $category->getAllProductsCount() }} productos</span>
                </a>
            @endforeach
        </div>
    </section>

    {{-- Featured Products --}}
    <section class="bg-slate-50 dark:bg-slate-900/50 py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl md:text-3xl font-bold text-center mb-2 text-slate-900 dark:text-slate-100">Productos Destacados</h2>
            <p class="text-center text-slate-500 dark:text-slate-400 mb-10">Lo más nuevo de nuestra tienda</p>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
                @forelse($featuredProducts as $product)
                    <div class="group bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                        <a href="{{ route('store.product', $product->slug) }}" wire:navigate>
                            <div class="aspect-square overflow-hidden bg-slate-100 dark:bg-slate-800">
                                @if($product->imagen)
                                    <img src="{{ asset('storage/' . $product->imagen) }}" alt="{{ $product->nombre }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-300 dark:text-slate-600">
                                        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif
                            </div>
                        </a>
                        <div class="p-4">
                            <span class="text-xs font-medium text-emerald-500 dark:text-emerald-400">{{ $product->category?->nombre }}</span>
                            <a href="{{ route('store.product', $product->slug) }}" wire:navigate>
                                <h3 class="font-semibold text-sm mt-1 text-slate-800 dark:text-slate-100 group-hover:text-emerald-500 transition-colors truncate">{{ $product->nombre }}</h3>
                            </a>
                            <div class="flex items-center justify-between mt-3">
                                <div>
                                    <span class="text-lg font-bold text-slate-900 dark:text-white">${{ number_format($product->precio_venta, 2) }}</span>
                                    <span class="text-[10px] text-slate-400 block">IVA incluido</span>
                                </div>
                                <button wire:click="addToCart({{ $product->id }})" class="p-2 rounded-full bg-emerald-500 text-white hover:bg-emerald-600 shadow-md hover:shadow-lg transition-all hover:scale-110 active:scale-95">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12 text-slate-500">
                        <p class="text-lg">Aún no hay productos disponibles.</p>
                        <p class="text-sm mt-1">¡Vuelve pronto!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
</div>
