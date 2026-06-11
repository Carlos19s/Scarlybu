<?php

use Livewire\Volt\Component;
use App\Models\Product;
use App\Models\Category;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Str;

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

    public function with(): array
    {
        return [
            'products' => Product::with('category')->latest()->paginate(10),
            'categories' => Category::all(),
        ];
    }

    public function create()
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function edit(Product $product)
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

    public function save()
    {
        $this->validate([
            'nombre' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'precio_compra' => 'required|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'imagen_upload' => 'nullable|image|max:2048', // Max 2MB image
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
            'iva_porcentaje' => 15.00, // Fijado al 15% como requerimiento
            'stock' => $this->stock,
            'stock_minimo' => $this->stock_minimo,
            'activo' => $this->activo,
        ];

        if ($this->imagen_upload) {
            $data['imagen'] = $this->imagen_upload->store('products', 'public');
        }

        if ($this->isEditing) {
            $this->editingProduct->update($data);
        } else {
            Product::create($data);
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function delete(Product $product)
    {
        $product->delete();
    }

    private function resetForm()
    {
        $this->reset(['nombre', 'slug', 'descripcion', 'category_id', 'precio_compra', 'precio_venta', 'stock', 'stock_minimo', 'editingProduct', 'imagen_upload']);
        $this->activo = true;
        $this->stock_minimo = 5;
        $this->iva_porcentaje = 15.00;
    }
}; ?>

<div class="flex flex-col gap-6">
    <div class="flex items-center justify-between">
        <flux:heading size="xl" level="1">Productos</flux:heading>
        <flux:button wire:click="create" variant="primary" icon="plus">Nuevo Producto</flux:button>
    </div>

    <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 overflow-hidden">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-neutral-200 bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-800">
                <tr>
                    <th class="p-4 font-medium">Imagen</th>
                    <th class="p-4 font-medium">Nombre</th>
                    <th class="p-4 font-medium">Categoría</th>
                    <th class="p-4 font-medium">Precio Venta (con IVA)</th>
                    <th class="p-4 font-medium">Stock</th>
                    <th class="p-4 font-medium text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                @forelse($products as $product)
                    <tr class="bg-white dark:bg-neutral-900">
                        <td class="p-4">
                            @if($product->imagen)
                                <img src="{{ asset('storage/' . $product->imagen) }}" alt="{{ $product->nombre }}" class="w-10 h-10 object-cover rounded-lg border border-neutral-200 dark:border-neutral-700">
                            @else
                                <div class="w-10 h-10 bg-neutral-100 dark:bg-neutral-800 rounded-lg flex items-center justify-center text-neutral-400 border border-neutral-200 dark:border-neutral-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                        </td>
                        <td class="p-4 font-medium">{{ $product->nombre }}</td>
                        <td class="p-4">{{ $product->category?->nombre ?? 'Sin Categoría' }}</td>
                        <td class="p-4">
                            ${{ number_format($product->precio_venta, 2) }}
                            <span class="text-xs text-neutral-500 block">IVA incluido (15%)</span>
                        </td>
                        <td class="p-4">
                            @if($product->stock <= $product->stock_minimo)
                                <flux:badge color="red" class="font-bold">{{ $product->stock }} (Bajo)</flux:badge>
                            @else
                                <flux:badge color="green">{{ $product->stock }}</flux:badge>
                            @endif
                        </td>
                        <td class="p-4 text-right">
                            <flux:button wire:click="edit({{ $product->id }})" variant="ghost" size="sm">Editar</flux:button>
                            <flux:button wire:click="delete({{ $product->id }})" wire:confirm="¿Seguro que deseas eliminar este producto?" variant="ghost" size="sm" color="red">Eliminar</flux:button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-neutral-500">No hay productos registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div>
        {{ $products->links() }}
    </div>

    <flux:modal wire:model="showModal" class="md:w-3/4 lg:w-1/2">
        <div class="space-y-6">
            <flux:heading size="lg">{{ $isEditing ? 'Editar Producto' : 'Nuevo Producto' }}</flux:heading>

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
                        <span class="text-xs text-neutral-500 mt-1 block">Este precio ya incluye el 15% de IVA.</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <flux:input wire:model="stock" type="number" label="Stock Actual" required />
                    <flux:input wire:model="stock_minimo" type="number" label="Stock Mínimo" required />
                </div>

                <flux:textarea wire:model="descripcion" label="Descripción" />
                
                <div>
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Imagen del Producto</label>
                    <input type="file" wire:model="imagen_upload" accept="image/*" class="block w-full text-sm text-neutral-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-neutral-100 file:text-neutral-700 hover:file:bg-neutral-200 dark:file:bg-neutral-800 dark:file:text-neutral-300 dark:hover:file:bg-neutral-700 border border-neutral-200 dark:border-neutral-700 rounded-lg p-2">
                    @if ($imagen_upload)
                        <div class="mt-2">
                            <span class="text-xs text-neutral-500 block mb-1">Vista previa:</span>
                            <img src="{{ $imagen_upload->temporaryUrl() }}" class="w-20 h-20 object-cover rounded-lg border border-neutral-200 dark:border-neutral-700">
                        </div>
                    @elseif($isEditing && $editingProduct?->imagen)
                        <div class="mt-2">
                            <span class="text-xs text-neutral-500 block mb-1">Imagen actual:</span>
                            <img src="{{ asset('storage/' . $editingProduct->imagen) }}" class="w-20 h-20 object-cover rounded-lg border border-neutral-200 dark:border-neutral-700">
                        </div>
                    @endif
                </div>

                <flux:switch wire:model="activo" label="Producto Activo" />

                <div class="flex justify-end gap-2 mt-4">
                    <flux:button wire:click="$set('showModal', false)" variant="ghost">Cancelar</flux:button>
                    <flux:button type="submit" variant="primary">Guardar</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</div>