<?php

use Livewire\Volt\Component;
use App\Models\Product;
use App\Models\Category;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Str;
use Cloudinary\Cloudinary;

new #[Layout('layouts.app')] #[Title('Productos')] class extends Component {
    use WithPagination;
    use WithFileUploads;

    public bool $showModal = false;
    public bool $isEditing = false;
    public ?Product $editingProduct = null;

    public string $nombre = '';
    public string $slug = '';
    public string $descripcion = '';
    public $category_id = '';
    public $precio_compra = 0;
    public $precio_venta = 0;
    public $iva_porcentaje = 15.00;
    public $stock = 0;
    public $stock_minimo = 5;
    public bool $activo = true;

    public $imagen_upload;

    public string $search = '';
    public string $categoryFilter = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter(): void
    {
        $this->resetPage();
    }

    public function with(): array
    {
        return [
            'products' => Product::with('category')
                ->when($this->search, fn($q) => $q->where('nombre', 'like', "%{$this->search}%"))
                ->when($this->categoryFilter, fn($q) => $q->where('category_id', $this->categoryFilter))
                ->latest()
                ->paginate(9),
            'categories' => Category::all(),
        ];
    }

    public function create(): void
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function edit(Product $product): void
    {
        $this->isEditing = true;
        $this->editingProduct = $product;

        $this->nombre = $product->nombre;
        $this->slug = $product->slug;
        $this->descripcion = $product->descripcion ?? '';
        $this->category_id = $product->category_id;
        $this->precio_compra = $product->precio_compra;
        $this->precio_venta = $product->precio_venta;
        $this->iva_porcentaje = $product->iva_porcentaje;
        $this->stock = $product->stock;
        $this->stock_minimo = $product->stock_minimo;
        $this->activo = $product->activo;
        $this->imagen_upload = null;

        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate([
            'nombre' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'precio_compra' => 'required|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'imagen_upload' => 'nullable|image|max:2048',
        ]);

        if (empty($this->slug)) {
            $this->slug = Str::slug($this->nombre);
        }

        $data = [
            'nombre' => $this->nombre,
            'slug' => $this->slug,
            'descripcion' => $this->descripcion,
            'category_id' => $this->category_id,
            'precio_compra' => $this->precio_compra,
            'precio_venta' => $this->precio_venta,
            'iva_porcentaje' => 15.00,
            'stock' => $this->stock,
            'stock_minimo' => $this->stock_minimo,
            'activo' => $this->activo,
        ];

                        if ($this->imagen_upload) {
            // Subida limpia usando el contenedor de servicios nativo del paquete
            $uploadedFile = app(\CloudinaryLabs\CloudinaryLaravel\CloudinaryEngine::class)
                ->uploadFile($this->imagen_upload->getRealPath(), [
                    'folder' => 'productos'
                ]);

            // Almacena la URL pública directa en la base de datos
            $data['imagen'] = $uploadedFile->getSecurePath();
        }



        if ($this->isEditing) {
            $this->editingProduct->update($data);
        } else {
            Product::create($data);
        }

        $this->showModal = false;
        $this->resetForm();
    }


    public function delete(Product $product): void
    {
        $product->delete();
    }

    private function resetForm(): void
    {
        $this->reset(['nombre', 'slug', 'descripcion', 'category_id', 'precio_compra', 'precio_venta', 'stock', 'stock_minimo', 'editingProduct', 'imagen_upload']);
        $this->activo = true;
        $this->stock_minimo = 5;
        $this->iva_porcentaje = 15.00;
    }
}; ?>

<div class="space-y-6">
    <!-- Page Header -->
    <div class="admin-page-header">
        <div>
            <h1 class="admin-page-title">Productos</h1>
            <p class="admin-page-subtitle">Gestiona el inventario de tu tienda</p>
        </div>
        <button wire:click="create"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-sm transition-colors shadow-lg shadow-emerald-600/20">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Nuevo Producto
        </button>
    </div>

    <!-- Search & Filter -->
    <div class="admin-toolbar">
        <div class="relative flex-1">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar productos..."
                   class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm transition-colors">
        </div>
        <select wire:model.live="categoryFilter"
                class="px-4 py-2.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 text-slate-900 dark:text-slate-100 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 min-w-[180px]">
            <option value="">Todas las categorías</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->nombre }}</option>
            @endforeach
        </select>
    </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($products as $index => $product)
            <div class="admin-card animate-fade-in-up stagger-{{ ($index % 6) + 1 }}">
                <!-- Image -->
                <div class="relative overflow-hidden">
                    @if($product->imagen)
                        <img src="{{ $product->imagen}}" alt="{{ $product->nombre }}"
                             class="w-full h-44 object-cover transition-transform duration-500 hover:scale-105">
@else

                        <div class="w-full h-44 bg-gradient-to-br from-slate-100 to-slate-200 dark:from-slate-800 dark:to-slate-700 flex items-center justify-center">
                            <svg class="w-12 h-12 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                    @endif
                    @if(!$product->activo)
                        <div class="absolute inset-0 bg-black/50 flex items-center justify-center backdrop-blur-[1px]">
                            <span class="badge-red text-xs">Inactivo</span>
                        </div>
                    @endif
                    <!-- Stock badge -->
                    <div class="absolute top-3 right-3">
                        @if($product->stock <= $product->stock_minimo)
                            <span class="badge-red">Stock: {{ $product->stock }}</span>
                        @else
                            <span class="badge-emerald">Stock: {{ $product->stock }}</span>
                        @endif
                    </div>
                </div>

                <!-- Content -->
                <div class="p-4">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <h3 class="font-semibold text-slate-900 dark:text-slate-100 truncate">{{ $product->nombre }}</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $product->category?->nombre ?? 'Sin Categoría' }}</p>
                        </div>
                    </div>
                    <div class="mt-3 flex items-baseline gap-1.5">
                        <span class="text-xl font-bold text-emerald-600 dark:text-emerald-400">${{ number_format($product->precio_venta, 2) }}</span>
                        <span class="text-xs text-slate-400">IVA incl.</span>
                    </div>

                    <!-- Actions -->
                    <div class="mt-4 flex items-center gap-2 pt-3 border-t border-slate-100 dark:border-slate-700/50">
                        <button wire:click="edit({{ $product->id }})"
                                class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg text-sm font-medium text-emerald-600 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Editar
                        </button>
                        <button wire:click="delete({{ $product->id }})" wire:confirm="¿Seguro que deseas eliminar este producto?"
                                class="inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg text-sm font-medium text-red-500 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full flex flex-col items-center justify-center py-16 gap-3 text-slate-400">
                <svg class="w-14 h-14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                <p class="font-medium text-lg">No se encontraron productos</p>
                <p class="text-sm">Intenta con otro filtro o crea un nuevo producto</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $products->links() }}
    </div>

    <!-- Product Modal -->
    <flux:modal wire:model="showModal" class="md:w-3/4 lg:w-1/2">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ $isEditing ? 'Editar Producto' : 'Nuevo Producto' }}</flux:heading>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ $isEditing ? 'Modifica los datos del producto' : 'Completa los datos del nuevo producto' }}</p>
            </div>

            <form wire:submit="save" class="space-y-4">
                <flux:input wire:model="nombre" label="Nombre" required />
                <flux:input wire:model="slug" label="Slug (opcional)" placeholder="Se generará automáticamente" />

                <flux:select wire:model="category_id" label="Categoría" required>
                    <option value="">Selecciona una categoría...</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->nombre }}</option>
                    @endforeach
                </flux:select>

                <div class="grid grid-cols-2 gap-4">
                    <flux:input wire:model="precio_compra" type="number" step="0.01" label="Precio Compra" required />
                    <div>
                        <flux:input wire:model="precio_venta" type="number" step="0.01" label="Precio Venta" required />
                        <span class="text-xs text-slate-500 mt-1 block">Este precio ya incluye el 15% de IVA.</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <flux:input wire:model="stock" type="number" label="Stock Actual" required />
                    <flux:input wire:model="stock_minimo" type="number" label="Stock Mínimo" required />
                </div>

                <flux:textarea wire:model="descripcion" label="Descripción" />

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Imagen del Producto</label>
                    <input type="file" wire:model="imagen_upload" accept="image/*"
                           class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 dark:file:bg-emerald-900/30 dark:file:text-emerald-400 dark:hover:file:bg-emerald-900/50 border border-slate-200 dark:border-slate-700 rounded-lg p-2">
                    @if ($imagen_upload)
                        <div class="mt-2">
                            <span class="text-xs text-slate-500 block mb-1">Vista previa:</span>
                            <img src="{{ $imagen_upload->temporaryUrl() }}" class="w-20 h-20 object-cover rounded-lg border border-slate-200 dark:border-slate-700">
                        </div>
                    @elseif($isEditing && $editingProduct?->imagen)
                        <div class="mt-2">
                            <span class="text-xs text-slate-500 block mb-1">Imagen actual:</span>
                            <img src="{{ $editingProduct->imagen }}" class="w-20 h-20 object-cover rounded-lg border border-slate-200 dark:border-slate-700">
                        </div>
                    @endif
                </div>

                <flux:switch wire:model="activo" label="Producto Activo" />

                <div class="flex justify-end gap-2 pt-2">
                    <flux:button wire:click="$set('showModal', false)" variant="ghost">Cancelar</flux:button>
                    <flux:button type="submit" variant="primary" class="!bg-emerald-600 hover:!bg-emerald-700">Guardar</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</div>