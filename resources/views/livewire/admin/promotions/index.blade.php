<?php

use Livewire\Volt\Component;
use App\Models\Promocion;
use App\Models\Product;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

new #[Layout('layouts.app')] #[Title('Promociones')] class extends Component {
    use WithPagination;

    public bool $showModal = false;
    public bool $isEditing = false;
    public ?Promocion $editingPromocion = null;

    public $product_id = '';
    public $precio_promocion = '';
    public string $fecha_inicio = '';
    public string $fecha_fin = '';

    public string $search = '';
    public string $statusFilter = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function with(): array
    {
        $query = Promocion::with(['product']);

        if ($this->search) {
            $query->whereHas('product', function($q) {
                $q->where('nombre', 'like', "%{$this->search}%");
            });
        }

        $today = now()->toDateString();
        if ($this->statusFilter === 'active') {
            $query->where('fecha_inicio', '<=', $today)
                  ->where('fecha_fin', '>=', $today);
        } elseif ($this->statusFilter === 'expired') {
            $query->where('fecha_fin', '<', $today);
        } elseif ($this->statusFilter === 'upcoming') {
            $query->where('fecha_inicio', '>', $today);
        }

        return [
            'promotions' => $query->latest()->paginate(10),
            'products' => Product::where('activo', true)->orderBy('nombre')->get(),
        ];
    }

    public function create(): void
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function edit(Promocion $promocion): void
    {
        $this->isEditing = true;
        $this->editingPromocion = $promocion;

        $this->product_id = $promocion->product_id;
        $this->precio_promocion = $promocion->precio_promocion;
        $this->fecha_inicio = $promocion->fecha_inicio ? $promocion->fecha_inicio->format('Y-m-d') : '';
        $this->fecha_fin = $promocion->fecha_fin ? $promocion->fecha_fin->format('Y-m-d') : '';

        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate([
            'product_id' => 'required|exists:products,id',
            'precio_promocion' => 'required|numeric|min:0.01',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);

        // Check if promotional price is lower than original price
        $product = Product::findOrFail($this->product_id);
        if ($this->precio_promocion >= $product->precio_venta) {
            $this->addError('precio_promocion', 'El precio de oferta debe ser menor al precio original ($' . number_format($product->precio_venta, 2) . ').');
            return;
        }

        $data = [
            'product_id' => $this->product_id,
            'precio_promocion' => $this->precio_promocion,
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin,
        ];

        if ($this->isEditing) {
            $this->editingPromocion->update($data);
        } else {
            // Check if product already has an active promotion during this timeframe (optional safety check)
            Promocion::create($data);
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function delete(Promocion $promocion): void
    {
        $promocion->delete();
    }

    private function resetForm(): void
    {
        $this->reset([
            'product_id', 'precio_promocion', 'fecha_inicio', 'fecha_fin', 'editingPromocion'
        ]);
    }
}; ?>

<div class="space-y-6">
    <!-- Page Header -->
    <div class="admin-page-header">
        <div>
            <h1 class="admin-page-title">Gestión de Promociones</h1>
            <p class="admin-page-subtitle">Configura los precios especiales y descuentos temporales de tus productos</p>
        </div>
        <button wire:click="create"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-sm transition-colors shadow-lg shadow-emerald-600/20">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Crear Promoción
        </button>
    </div>

    <!-- Search & Filter Toolbar -->
    <div class="admin-toolbar">
        <div class="relative flex-1">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar por nombre de producto..."
                   class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm transition-colors">
        </div>
        <select wire:model.live="statusFilter"
                class="px-4 py-2.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 text-slate-900 dark:text-slate-100 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 min-w-[180px]">
            <option value="">Todos los estados</option>
            <option value="active">Vigentes (Hoy)</option>
            <option value="upcoming">Próximas</option>
            <option value="expired">Expiradas</option>
        </select>
    </div>

    <!-- Table List -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 font-semibold border-b border-slate-200 dark:border-slate-800">
                        <th class="px-6 py-4">Producto</th>
                        <th class="px-6 py-4">Precio Original</th>
                        <th class="px-6 py-4">Precio Oferta</th>
                        <th class="px-6 py-4">Descuento</th>
                        <th class="px-6 py-4">Vigencia</th>
                        <th class="px-6 py-4">Estado</th>
                        <th class="px-6 py-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800/50">
                    @forelse($promotions as $promo)
                        @php
                            $product = $promo->product;
                            $descuentoPorcentaje = $product && $product->precio_venta > 0 
                                ? round((1 - ($promo->precio_promocion / $product->precio_venta)) * 100) 
                                : 0;
                            
                            $today = now()->toDateString();
                            $startDate = $promo->fecha_inicio->toDateString();
                            $endDate = $promo->fecha_fin->toDateString();
                            
                            if ($today >= $startDate && $today <= $endDate) {
                                $statusLabel = 'Vigente';
                                $statusClass = 'bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-400';
                            } elseif ($today < $startDate) {
                                $statusLabel = 'Próxima';
                                $statusClass = 'bg-blue-50 dark:bg-blue-950/30 text-blue-700 dark:text-blue-400';
                            } else {
                                $statusLabel = 'Expirada';
                                $statusClass = 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400';
                            }
                        @endphp
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if($product && $product->imagen)
                                        <img src="{{ $product->imagen_url }}" alt="{{ $product->nombre }}"
                                             class="w-10 h-10 object-cover rounded-lg border border-slate-200 dark:border-slate-800">
                                    @else
                                        <div class="w-10 h-10 bg-slate-100 dark:bg-slate-800 rounded-lg flex items-center justify-center text-slate-400">
                                            📦
                                        </div>
                                    @endif
                                    <div>
                                        <span class="font-semibold text-slate-950 dark:text-white block">{{ $product?->nombre ?? 'Producto Eliminado' }}</span>
                                        <span class="text-xs text-slate-500 dark:text-slate-400">{{ $product?->category?->nombre ?? 'Sin categoría' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-400 font-medium">
                                ${{ number_format($product?->precio_venta ?? 0, 2) }}
                            </td>
                            <td class="px-6 py-4 text-rose-600 dark:text-rose-400 font-bold">
                                ${{ number_format($promo->precio_promocion, 2) }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex px-2 py-0.5 rounded font-extrabold text-xs text-rose-600 dark:text-rose-400 bg-rose-500/10">
                                    -{{ $descuentoPorcentaje }}% OFF
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs font-medium">
                                <span class="text-slate-700 dark:text-slate-300 block">Desde: {{ $promo->fecha_inicio->format('d/m/Y') }}</span>
                                <span class="text-slate-500 dark:text-slate-400 block">Hasta: {{ $promo->fecha_fin->format('d/m/Y') }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ $statusClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <button wire:click="edit({{ $promo->id }})"
                                            class="inline-flex items-center justify-center p-1.5 rounded-lg text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-colors">
                                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <button wire:click="delete({{ $promo->id }})" wire:confirm="¿Seguro que deseas eliminar esta promoción?"
                                            class="inline-flex items-center justify-center p-1.5 rounded-lg text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400 dark:text-slate-500">
                                <div class="flex flex-col items-center gap-3">
                                    <svg class="w-12 h-12 text-slate-300 dark:text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                    <p class="font-medium text-base">No hay promociones registradas</p>
                                    <p class="text-xs">Crea una oferta para habilitar precios especiales en la tienda</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($promotions->hasPages())
            <div class="p-4 border-t border-slate-200 dark:border-slate-800">
                {{ $promotions->links() }}
            </div>
        @endif
    </div>

    <!-- Promotions Modal -->
    <flux:modal wire:model="showModal" class="md:w-3/4 lg:w-3/5">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ $isEditing ? 'Editar Promoción' : 'Crear Promoción' }}</flux:heading>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Ingresa el producto, su precio de oferta y el rango de vigencia</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
                <!-- Form Fields -->
                <form wire:submit="save" class="lg:col-span-3 space-y-4">
                    <flux:select wire:model.live="product_id" label="Producto en Promoción" required>
                        <option value="">Selecciona un producto...</option>
                        @foreach($products as $prod)
                            <option value="{{ $prod->id }}">{{ $prod->nombre }} (${{ number_format($prod->precio_venta, 2) }})</option>
                        @endforeach
                    </flux:select>
                    
                    <flux:input wire:model.live="precio_promocion" type="number" step="0.01" label="Precio de Promoción / Oferta (USD)" required />

                    <div class="grid grid-cols-2 gap-4">
                        <flux:input wire:model="fecha_inicio" type="date" label="Fecha de Inicio" required />
                        <flux:input wire:model="fecha_fin" type="date" label="Fecha de Fin" required />
                    </div>

                    <div class="flex justify-end gap-2 pt-4 border-t border-slate-150 dark:border-slate-850">
                        <flux:button wire:click="$set('showModal', false)" variant="ghost">Cancelar</flux:button>
                        <flux:button type="submit" variant="primary" class="!bg-emerald-600 hover:!bg-emerald-700">Guardar Promoción</flux:button>
                    </div>
                </form>

                <!-- Temu Style Card Preview -->
                <div class="lg:col-span-2 space-y-3">
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Vista Previa (Tarjeta Temu)</span>
                    
                    @php
                        $selectedProduct = $product_id ? \App\Models\Product::find($product_id) : null;
                        $previewPrice = floatval($precio_promocion);
                        $previewOriginal = $selectedProduct ? floatval($selectedProduct->precio_venta) : 0;
                        $previewDiscount = $previewOriginal > 0 && $previewPrice > 0 && $previewPrice < $previewOriginal
                            ? round((1 - ($previewPrice / $previewOriginal)) * 100)
                            : 0;
                    @endphp

                    <div class="flex items-center justify-center p-4 bg-slate-50 dark:bg-slate-950 rounded-2xl border border-slate-200 dark:border-slate-850">
                        <div class="w-[180px] bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-md">
                            <div class="aspect-square bg-slate-100 dark:bg-slate-800 relative">
                                @if($selectedProduct && $selectedProduct->imagen)
                                     <img src="{{ $selectedProduct->imagen_url }}" class="w-full h-full object-contain">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-400 text-3xl">
                                        🧢
                                    </div>
                                @endif
                                <span class="absolute top-2 left-2 px-1.5 py-0.5 rounded bg-rose-600 text-[8px] font-bold text-white uppercase">
                                    Oferta
                                </span>
                            </div>
                            <div class="p-3">
                                <span class="text-[9px] font-semibold text-emerald-500 uppercase">{{ $selectedProduct?->category?->nombre ?? 'Categoría' }}</span>
                                <h4 class="text-xs font-bold text-slate-800 dark:text-slate-100 truncate mt-0.5">{{ $selectedProduct?->nombre ?? 'Nombre del Producto' }}</h4>
                                
                                <div class="mt-2 flex items-baseline gap-1.5">
                                    <span class="text-sm font-black text-rose-600">${{ number_format($previewPrice ?: 0, 2) }}</span>
                                    <span class="text-[10px] text-slate-400 line-through">${{ number_format($previewOriginal, 2) }}</span>
                                </div>
                                
                                <div class="mt-1">
                                    <span class="text-[9px] font-bold text-rose-600 bg-rose-500/10 px-1.5 py-0.5 rounded">
                                        -{{ $previewDiscount }}% OFF
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </flux:modal>
</div>
