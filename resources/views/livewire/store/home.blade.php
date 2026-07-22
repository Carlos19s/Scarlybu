<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\Category;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Cache;

new #[Layout('layouts.store')] #[Title('Scarlybu - Tu Tienda de Moda')] class extends Component {
    use WithPagination;

    public string $search = '';
    public string $sortBy = 'newest';
    public ?float $priceMin = null;
    public ?float $priceMax = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingSortBy(): void
    {
        $this->resetPage();
    }

    public function updatingPriceMin(): void
    {
        $this->resetPage();
    }

    public function updatingPriceMax(): void
    {
        $this->resetPage();
    }

    public function addToCart(int $productId): void
    {
        $product = Product::findOrFail($productId);

        if ($product->stock <= 0) {
            return;
        }

        $cart = session()->get('cart', []);
        $currentInCart = isset($cart[$productId]) ? $cart[$productId]['cantidad'] : 0;

        // Can't add more than available stock
        if ($currentInCart >= $product->stock) {
            return;
        }

        // No descontamos stock aquí, sólo al crear la nota de pedido

        if (isset($cart[$productId])) {
            $cart[$productId]['cantidad']++;
        } else {
            $cart[$productId] = [
                'nombre'               => $product->nombre,
                'precio'               => $product->precio_actual,
                'precio_original'      => $product->precio_venta,
                'tiene_promocion'      => $product->tiene_promocion,
                'porcentaje_descuento' => $product->porcentaje_descuento,
                'imagen'               => $product->imagen,
                'cantidad'             => 1,
            ];
        }

        session()->put('cart', $cart);
        $this->dispatch('cart-updated');
    }

    public function with(): array
    {
        $today = now()->toDateString();
        
        $activePromos = Cache::remember("home_active_promos_{$today}", 3600, function () use ($today) {
            return \App\Models\Promocion::where('fecha_inicio', '<=', $today)
                ->where('fecha_fin', '>=', $today)
                ->with(['product.category'])
                ->get();
        });

        $query = Product::where('activo', true)->with(['category', 'promociones' => fn ($q) => $q
            ->where('fecha_inicio', '<=', $today)
            ->where('fecha_fin', '>=', $today)
        ]);

        if ($this->search !== '') {
            $query->where('nombre', 'like', '%' . $this->search . '%');
        }

        if ($this->priceMin !== null) {
            $query->where('precio_venta', '>=', $this->priceMin);
        }

        if ($this->priceMax !== null) {
            $query->where('precio_venta', '<=', $this->priceMax);
        }

        $query->when($this->sortBy === 'price_asc', fn ($q) => $q->orderBy('precio_venta'))
            ->when($this->sortBy === 'price_desc', fn ($q) => $q->orderByDesc('precio_venta'))
            ->when($this->sortBy === 'name', fn ($q) => $q->orderBy('nombre'))
            ->when($this->sortBy === 'newest', fn ($q) => $q->latest());

        $categories = Cache::remember('home_categories_activa', 3600, function () {
            return Category::whereNull('parent_id')->where('activa', true)->get();
        });

        return [
            'categories'    => $categories,
            'allProducts'   => $query->paginate(12),
            'activePromos'  => $activePromos,
        ];
    }
}; ?>

<div>
    {{-- ═══════════════════════════════════════════════════ --}}
    {{-- SECCIÓN 1: HERO --}}
    {{-- ═══════════════════════════════════════════════════ --}}
    <section class="relative overflow-hidden" style="min-height: 420px; background: linear-gradient(135deg, #16171d 0%, #1a1c24 60%, #0d1117 100%);">
        {{-- Glow fondo --}}
        <div class="absolute inset-0 pointer-events-none">
            <div style="position:absolute;top:-10%;left:50%;transform:translateX(-50%);width:700px;height:400px;background:radial-gradient(ellipse at center, rgba(85,214,255,0.13) 0%, transparent 70%);"></div>
            <div style="position:absolute;bottom:0;right:0;width:400px;height:300px;background:radial-gradient(ellipse at bottom right, rgba(255,107,129,0.09) 0%, transparent 70%);"></div>
        </div>

        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-20 md:py-28">
            <div class="flex flex-col md:flex-row items-center gap-10">
                {{-- Texto --}}
                <div class="flex-1 text-center md:text-left">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold mb-5"
                         style="background:rgba(85,214,255,0.12);border:1px solid rgba(85,214,255,0.25);color:#55d6ff;">
                        ✦ Moda Urbana Premium
                    </div>
                    <h1 class="text-5xl md:text-6xl font-black tracking-tight mb-4 leading-tight" style="color:#f2f2f7;">
                        Tu estilo,<br>
                        <span style="background:linear-gradient(90deg,#55d6ff,#ff6b81);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">sin límites</span>
                    </h1>
                    <p class="text-base md:text-lg mb-8 max-w-lg" style="color:#b8bac1;">
                        Gorras, ropa, accesorios, cosméticos. Todo lo que necesitas para lucir increíble, en un solo lugar.
                    </p>
                    <div class="flex flex-wrap gap-3 justify-center md:justify-start">
                        <a href="#productos"
                           class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl font-bold text-sm transition-all hover:scale-105 hover:shadow-lg"
                           style="background:linear-gradient(135deg,#55d6ff,#38b2ac);color:#0d1117;box-shadow:0 8px 25px rgba(85,214,255,0.3);">
                            Ver Productos
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </a>
                        <a href="#categorias"
                           class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl font-bold text-sm transition-all hover:scale-105"
                           style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);color:#f2f2f7;">
                            Categorías
                        </a>
                    </div>
                </div>

                {{-- Logo / decorativo --}}
                <div class="flex-shrink-0 hidden md:flex items-center justify-center">
                    <div class="relative">
                        <div class="absolute inset-0 rounded-3xl blur-2xl opacity-40" style="background:linear-gradient(135deg,rgba(85,214,255,0.4),rgba(255,107,129,0.3));"></div>
                        <div class="relative p-8 rounded-3xl" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);">
                            <img src="{{ asset('img/Scarlybu.png') }}" alt="Scarlybu Logo" class="w-40 h-40 object-contain drop-shadow-2xl" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════ --}}
    {{-- SECCIÓN 2: PROMOCIONES --}}
    {{-- ═══════════════════════════════════════════════════ --}}
    @if($activePromos->isNotEmpty())
        <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 mt-14">
            {{-- Header --}}
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-2 px-3 py-1 rounded-full text-xs font-black uppercase tracking-widest"
                         style="background:rgba(255,107,129,0.12);border:1px solid rgba(255,107,129,0.25);color:#ff6b81;">
                        <span class="animate-pulse">⚡</span> Ofertas Activas
                    </div>
                    <h2 class="text-xl md:text-2xl font-extrabold tracking-tight" style="color:#f2f2f7;">Promociones</h2>
                </div>
                <a href="{{ route('store.promotions') }}"
                   class="flex items-center gap-1 text-sm font-semibold transition-colors hover:gap-2"
                   style="color:#55d6ff;" wire:navigate>
                    Ver todas
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                </a>
            </div>

            {{-- Carrusel Horizontal --}}
            <div class="flex gap-4 overflow-x-auto pb-4" style="scrollbar-width:thin;scrollbar-color:rgba(255,255,255,0.1) transparent;">
                @foreach($activePromos as $promo)
                    @php
                        $product = $promo->product;
                        $descPct = $product->precio_venta > 0
                            ? round((1 - ($promo->precio_promocion / $product->precio_venta)) * 100)
                            : 0;
                    @endphp
                    <div class="flex-shrink-0 w-[200px] sm:w-[220px] group rounded-2xl overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl"
                         style="background:#1f2128;border:1px solid rgba(255,255,255,0.07);">
                        <a href="{{ route('store.product', $product->slug) }}" wire:navigate>
                            <div class="relative aspect-square overflow-hidden" style="background:#292b33;">
                                @if($product->imagen)
                                    <img src="{{ $product->imagen_url }}"
                                         alt="{{ $product->nombre }}"
                                         loading="lazy"
                                         class="w-full h-full object-contain p-2 group-hover:scale-110 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full flex items-center justify-center" style="color:#555;">
                                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                @endif
                                <span class="absolute top-2 left-2 px-2 py-0.5 rounded-lg text-[10px] font-black uppercase tracking-wider animate-pulse"
                                      style="background:linear-gradient(135deg,#ff6b81,#e53e3e);color:white;box-shadow:0 2px 10px rgba(255,107,129,0.4);">
                                    -{{ $descPct }}% OFF
                                </span>
                            </div>
                        </a>
                        <div class="p-3.5">
                            <span class="text-[10px] font-medium uppercase tracking-wide" style="color:#55d6ff;">{{ $product->category?->nombre }}</span>
                            <a href="{{ route('store.product', $product->slug) }}" wire:navigate>
                                <h3 class="text-sm font-semibold mt-0.5 truncate" style="color:#f2f2f7;">{{ $product->nombre }}</h3>
                            </a>
                            <div class="mt-2 flex items-end gap-1.5">
                                <span class="text-lg font-black" style="color:#ff6b81;">${{ number_format($promo->precio_promocion, 2) }}</span>
                                <span class="text-xs line-through mb-0.5" style="color:#666;">${{ number_format($product->precio_venta, 2) }}</span>
                            </div>
                            @if($product->stock > 0)
                                <button wire:click="addToCart({{ $product->id }})"
                                        class="mt-2 w-full py-1.5 rounded-xl text-xs font-bold transition-all hover:scale-[1.02] active:scale-95"
                                        style="background:linear-gradient(135deg,#55d6ff,#38b2ac);color:#0d1117;">
                                    + Agregar al carrito
                                </button>
                            @else
                                <span class="mt-2 block w-full py-1.5 rounded-xl text-xs font-bold text-center" style="background:rgba(255,255,255,0.06);color:#888;">Agotado</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- ═══════════════════════════════════════════════════ --}}
    {{-- SECCIÓN 3: NUESTRO LOCAL (Carrusel de fotos) --}}
    {{-- ═══════════════════════════════════════════════════ --}}
    <section class="mt-16 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold mb-3"
                 style="background:rgba(85,214,255,0.10);border:1px solid rgba(85,214,255,0.2);color:#55d6ff;">
                📍 Visítanos
            </div>
            <h2 class="text-2xl md:text-3xl font-extrabold" style="color:#f2f2f7;">Nuestro Local</h2>
            <p class="mt-1 text-sm" style="color:#b8bac1;">Un espacio diseñado para que disfrutes de tu experiencia de compra</p>
        </div>

        <div x-data="{
            current: 0,
            images: [
                '{{ asset('img/Negocio/Negocio1.jpg') }}',
                '{{ asset('img/Negocio/Negocio3.jpg') }}',
                '{{ asset('img/Negocio/Negocio4.jpg') }}',
                '{{ asset('img/Negocio/Negocio5.jpg') }}',
                '{{ asset('img/Negocio/Negocio6.jpg') }}',
                '{{ asset('img/Negocio/Negocio7.jpg') }}',
            ],
            autoPlay() {
                setInterval(() => { this.current = (this.current + 1) % this.images.length; }, 4000);
            }
        }" x-init="autoPlay()" class="relative rounded-3xl overflow-hidden" style="height:420px;background:#1f2128;">
            {{-- Imágenes --}}
            <template x-for="(img, idx) in images" :key="idx">
                <div x-show="current === idx"
                     x-transition:enter="transition duration-700 ease-in-out"
                     x-transition:enter-start="opacity-0 scale-105"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition duration-500 ease-in-out"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute inset-0">
                    <img :src="img" :alt="'Foto local ' + (idx+1)" loading="lazy" class="w-full h-full object-cover">
                    <div class="absolute inset-0" style="background:linear-gradient(to top, rgba(13,17,23,0.6) 0%, transparent 50%);"></div>
                </div>
            </template>

            {{-- Prev/Next --}}
            <button @click="current = (current - 1 + images.length) % images.length"
                    class="absolute left-4 top-1/2 -translate-y-1/2 p-2.5 rounded-full transition-all hover:scale-110"
                    style="background:rgba(22,23,29,0.7);border:1px solid rgba(255,255,255,0.1);color:#f2f2f7;">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
            </button>
            <button @click="current = (current + 1) % images.length"
                    class="absolute right-4 top-1/2 -translate-y-1/2 p-2.5 rounded-full transition-all hover:scale-110"
                    style="background:rgba(22,23,29,0.7);border:1px solid rgba(255,255,255,0.1);color:#f2f2f7;">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
            </button>

            {{-- Dots --}}
            <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2">
                <template x-for="(img, idx) in images" :key="idx">
                    <button @click="current = idx"
                            :class="current === idx ? 'w-6 opacity-100' : 'w-2 opacity-50'"
                            class="h-2 rounded-full transition-all duration-300"
                            style="background:#55d6ff;"></button>
                </template>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════ --}}
    {{-- SECCIÓN 4: COMPRA POR ESTILO (Modelos + Categorías) --}}
    {{-- ═══════════════════════════════════════════════════ --}}
    <section id="categorias" class="mt-16 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold mb-3"
                 style="background:rgba(255,107,129,0.10);border:1px solid rgba(255,107,129,0.2);color:#ff6b81;">
                ✦ Inspiración
            </div>
            <h2 class="text-2xl md:text-3xl font-extrabold" style="color:#f2f2f7;">Compra por Estilo</h2>
            <p class="mt-1 text-sm" style="color:#b8bac1;">Encuentra el look que va contigo</p>
        </div>

        {{-- Grid de modelos --}}
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-8">
            @php
                $modelImages = [
                    ['img' => 'Modelo1.jpg',  'label' => 'Look Urbano'],
                    ['img' => 'Modelo4.jpg',  'label' => 'Moda Moderna'],
                    ['img' => 'Modelo12.jpg', 'label' => 'Premium Style'],
                ];
            @endphp
            @foreach($modelImages as $m)
                <div class="group relative rounded-2xl overflow-hidden cursor-pointer" style="aspect-ratio: 3/4; background:#1f2128;">
                    <img src="{{ asset('img/Modelos/' . $m['img']) }}"
                         alt="{{ $m['label'] }}"
                         loading="lazy"
                         class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                         style="background:linear-gradient(to top, rgba(13,17,23,0.85) 0%, transparent 60%);"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-4 translate-y-4 group-hover:translate-y-0 opacity-0 group-hover:opacity-100 transition-all duration-300">
                        <span class="text-sm font-bold" style="color:#f2f2f7;">{{ $m['label'] }}</span>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Categorías en grid --}}
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
            @foreach($categories as $category)
                <a href="{{ route('store.category', $category->slug) }}" wire:navigate
                   class="group relative flex flex-col items-start p-6 rounded-2xl transition-all duration-300 hover:-translate-y-1 hover:shadow-xl text-left min-h-[140px]"
                   style="background:rgba(31,33,40,0.8);border:1px solid rgba(255,255,255,0.07);">
                    <span class="font-bold text-base mb-1.5 transition-colors group-hover:text-[#55d6ff]" style="color:#f2f2f7;">{{ $category->nombre }}</span>
                    <span class="text-xs leading-relaxed" style="color:#b8bac1;">{{ $category->descripcion }}</span>
                    <div class="absolute bottom-0 left-0 right-0 h-0.5 rounded-b-2xl opacity-0 group-hover:opacity-100 transition-opacity"
                         style="background:linear-gradient(90deg,#55d6ff,#ff6b81);"></div>
                </a>
            @endforeach
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════ --}}
    {{-- SECCIÓN 5: TODOS LOS PRODUCTOS --}}
    {{-- ═══════════════════════════════════════════════════ --}}
    <section id="productos" class="mt-16 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pb-16">
        {{-- Header + Filtros --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h2 class="text-2xl md:text-3xl font-extrabold" style="color:#f2f2f7;">Todos los Productos</h2>
                <p class="text-sm mt-0.5" style="color:#b8bac1;">Mostrando {{ $allProducts->count() }} de {{ $allProducts->total() }} productos en el catálogo</p>
            </div>

            {{-- Barra de búsqueda y filtros --}}
            <div class="flex flex-wrap items-center gap-3">
                {{-- Buscador --}}
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4" style="color:#b8bac1;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                    <input type="text"
                           wire:model.live.debounce.350ms="search"
                           placeholder="Buscar productos..."
                           class="pl-9 pr-4 py-2 rounded-xl text-sm outline-none transition-all w-52"
                           style="background:#1f2128;border:1px solid rgba(255,255,255,0.1);color:#f2f2f7;">
                </div>

                {{-- Ordenar --}}
                <select wire:model.live="sortBy"
                        class="px-3 py-2 rounded-xl text-sm outline-none transition-all"
                        style="background:#1f2128;border:1px solid rgba(255,255,255,0.1);color:#f2f2f7;">
                    <option value="newest">Más nuevos</option>
                    <option value="price_asc">Precio: menor a mayor</option>
                    <option value="price_desc">Precio: mayor a menor</option>
                    <option value="name">Nombre A-Z</option>
                </select>

                {{-- Precio mín --}}
                <input type="number"
                       wire:model.live.debounce.500ms="priceMin"
                       placeholder="$ Min"
                       min="0"
                       class="w-24 px-3 py-2 rounded-xl text-sm outline-none"
                       style="background:#1f2128;border:1px solid rgba(255,255,255,0.1);color:#f2f2f7;">

                {{-- Precio máx --}}
                <input type="number"
                       wire:model.live.debounce.500ms="priceMax"
                       placeholder="$ Max"
                       min="0"
                       class="w-24 px-3 py-2 rounded-xl text-sm outline-none"
                       style="background:#1f2128;border:1px solid rgba(255,255,255,0.1);color:#f2f2f7;">
            </div>
        </div>

        {{-- Grid de Productos --}}
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
            @forelse($allProducts as $product)
                <div class="group rounded-2xl overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl"
                     style="background:#1f2128;border:1px solid rgba(255,255,255,0.07);">
                    <a href="{{ route('store.product', $product->slug) }}" wire:navigate>
                        <div class="relative overflow-hidden" style="aspect-ratio:1;background:#292b33;">
                            @if($product->imagen)
                                <img src="{{ $product->imagen_url }}"
                                     alt="{{ $product->nombre }}"
                                     loading="lazy"
                                     class="w-full h-full object-contain p-2 group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center" style="color:#555;">
                                    <svg class="w-14 h-14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            @endif
                            {{-- Badge de oferta --}}
                            @if($product->tiene_promocion)
                                <span class="absolute top-2 left-2 px-2 py-0.5 rounded-lg text-[10px] font-black uppercase tracking-wider"
                                      style="background:linear-gradient(135deg,#ff6b81,#e53e3e);color:white;box-shadow:0 2px 8px rgba(255,107,129,0.35);">
                                    -{{ $product->porcentaje_descuento }}% OFF
                                </span>
                            @endif
                        </div>
                    </a>
                    <div class="p-4">
                        <span class="text-[10px] font-semibold uppercase tracking-wide" style="color:#55d6ff;">{{ $product->category?->nombre }}</span>
                        <a href="{{ route('store.product', $product->slug) }}" wire:navigate>
                            <h3 class="text-sm font-semibold mt-0.5 truncate group-hover:opacity-80 transition-opacity" style="color:#f2f2f7;">{{ $product->nombre }}</h3>
                        </a>
                        <div class="flex items-center justify-between mt-3">
                            <div>
                                @if($product->tiene_promocion)
                                    <div class="flex flex-col">
                                        <div class="flex items-baseline gap-1.5">
                                            <span class="text-base font-black" style="color:#ff6b81;">${{ number_format($product->precio_actual, 2) }}</span>
                                            <span class="text-xs line-through" style="color:#555;">${{ number_format($product->precio_venta, 2) }}</span>
                                        </div>
                                        <span class="text-[9px] font-bold px-1.5 py-0.5 rounded self-start mt-0.5"
                                              style="color:#ff6b81;background:rgba(255,107,129,0.12);">
                                            -{{ $product->porcentaje_descuento }}% OFF
                                        </span>
                                    </div>
                                @else
                                    <span class="text-base font-bold" style="color:#f2f2f7;">${{ number_format($product->precio_venta, 2) }}</span>
                                @endif
                            </div>
                            @if($product->stock > 0)
                                <button wire:click="addToCart({{ $product->id }})"
                                        class="p-2 rounded-xl transition-all hover:scale-110 active:scale-95"
                                        style="background:linear-gradient(135deg,#55d6ff,#38b2ac);color:#0d1117;box-shadow:0 4px 15px rgba(85,214,255,0.25);">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                                </button>
                            @else
                                <span class="p-2 rounded-xl text-[10px] font-bold" style="background:rgba(255,255,255,0.06);color:#888;">Agotado</span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-16">
                    <div class="text-5xl mb-4">🔍</div>
                    <p class="text-lg font-semibold" style="color:#f2f2f7;">No se encontraron productos</p>
                    <p class="text-sm mt-1" style="color:#b8bac1;">Intenta con otros filtros o términos de búsqueda.</p>
                    <button wire:click="$set('search', '')" class="mt-4 px-4 py-2 rounded-xl text-sm font-medium" style="background:#1f2128;color:#55d6ff;border:1px solid rgba(85,214,255,0.2);">
                        Limpiar filtros
                    </button>
                </div>
            @endforelse
        </div>

        {{-- Paginación --}}
        @if($allProducts->hasPages())
            <div class="mt-10">
                {{ $allProducts->links(data: ['scrollTo' => false]) }}
            </div>
        @endif
    </section>

    {{-- ═══════════════════════════════════════════════════ --}}
    {{-- SECCIÓN 6: CONTACTO Y REDES SOCIALES --}}
    {{-- ═══════════════════════════════════════════════════ --}}
    <section class="mt-4 py-16" style="background:#121318; border-top:1px solid rgba(255,255,255,0.06);">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-10 text-left">
                <span class="text-xs font-semibold uppercase tracking-widest block mb-2" style="color:#b8bac1;">Contacto</span>
                <h2 class="text-4xl font-extrabold mb-4" style="color:#f2f2f7;">¿Tienes dudas?</h2>
                <p class="text-sm max-w-2xl" style="color:#b8bac1;">En Scarlybu estamos listos para ayudarte. Contáctanos y te mostramos la mejor selección.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                {{-- Tarjeta Dirección --}}
                <div class="rounded-3xl p-6 flex flex-col gap-4" style="background:#1f2128; border:1px solid rgba(255,255,255,0.07);">
                    <h3 class="text-lg font-bold text-[#f2f2f7]">Dirección</h3>
                    <div class="rounded-2xl overflow-hidden" style="height:320px; border:1px solid rgba(255,255,255,0.07);">
                        <iframe
                            src="https://maps.google.com/maps?ll=0.334174,-78.133038&z=18&t=m&hl=es-419&gl=US&mapclient=embed&q=0%C2%B020%2703.7%22N%2078%C2%B007%2759.5%22W%200.334371%2C%20-78.133185@0.334371,-78.133185&output=embed"
                            width="100%"
                            height="100%"
                            style="border:0; filter:invert(90%) hue-rotate(180deg);"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                    <p class="text-sm text-[#b8bac1] mt-2">Ubicados en Av.Eugenio Espejo & Reinaldo Chavez</p>
                </div>

                {{-- Tarjeta Contacto + Redes --}}
                <div class="rounded-3xl p-6 flex flex-col justify-between" style="background:#1f2128; border:1px solid rgba(255,255,255,0.07);">
                    <div>
                        <h3 class="text-lg font-bold text-[#f2f2f7] mb-2">Contacto</h3>
                        <p class="text-sm text-[#b8bac1] mb-5">Contáctanos por WhatsApp para atención personalizada:</p>
                        <a href="https://wa.me/593991329846" target="_blank"
                           class="inline-flex items-center gap-2 px-6 py-3 rounded-full text-white font-bold text-sm bg-[#25d366] hover:bg-[#20ba5a] transition-all hover:scale-105 active:scale-95 shadow-lg shadow-[#25d366]/20">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                            WhatsApp
                        </a>
                    </div>

                    <div class="mt-8">
                        <h3 class="text-lg font-bold text-[#f2f2f7] mb-2">Redes Sociales</h3>
                        <p class="text-sm text-[#b8bac1] mb-5">Síguenos en nuestras redes para estar al día:</p>
                        <div class="flex flex-wrap gap-3">
                            {{-- Facebook --}}
                            <a href="https://www.facebook.com/rolo.romo.7" target="_blank"
                               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full text-white font-bold text-xs bg-[#1877f2] hover:bg-[#156cd4] transition-all hover:scale-105 active:scale-95 shadow-md shadow-[#1877f2]/10">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                                Facebook
                            </a>

                            {{-- TikTok --}}
                            <a href="https://www.tiktok.com/@rolo.romo" target="_blank"
                               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full text-white font-bold text-xs bg-[#000000] hover:bg-[#222222] transition-all hover:scale-105 active:scale-95 shadow-md border border-white/10">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.69a8.25 8.25 0 004.84 1.56V6.79a4.85 4.85 0 01-1.07-.1z"/>
                                </svg>
                                TikTok
                            </a>

                            {{-- Instagram --}}
                            <a href="https://www.instagram.com/rolo.romo.7" target="_blank"
                               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full text-white font-bold text-xs bg-gradient-to-tr from-[#f9ce34] via-[#ee2a7b] to-[#6228d7] hover:opacity-90 transition-all hover:scale-105 active:scale-95 shadow-md shadow-[#ee2a7b]/10">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                </svg>
                                Instagram
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
