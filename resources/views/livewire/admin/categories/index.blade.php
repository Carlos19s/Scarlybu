<?php

use Livewire\Volt\Component;
use App\Models\Category;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Str;

new #[Layout('layouts.app')] #[Title('Categorías')] class extends Component {
    use WithPagination;

    public bool $showModal = false;
    public bool $isEditing = false;
    public ?Category $editingCategory = null;

    public string $nombre = '';
    public string $slug = '';
    public string $descripcion = '';
    public $parent_id = '';
    public bool $activa = true;

    public function with(): array
    {
        return [
            'categories' => Category::with('parent')->latest()->paginate(10),
            'parentCategories' => Category::whereNull('parent_id')->get(),
        ];
    }

    public function create()
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function edit(Category $category)
    {
        $this->isEditing = true;
        $this->editingCategory = $category;
        
        $this->nombre = $category->nombre;
        $this->slug = $category->slug;
        $this->descripcion = $category->descripcion ?? '';
        $this->parent_id = $category->parent_id;
        $this->activa = $category->activa;

        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'nombre' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        if (empty($this->slug)) {
            $this->slug = Str::slug($this->nombre);
        }

        $data = [
            'nombre' => $this->nombre,
            'slug' => $this->slug,
            'descripcion' => $this->descripcion,
            'parent_id' => $this->parent_id ?: null,
            'activa' => $this->activa,
        ];

        if ($this->isEditing) {
            $this->editingCategory->update($data);
        } else {
            Category::create($data);
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function delete(Category $category)
    {
        $category->delete();
    }

    private function resetForm()
    {
        $this->reset(['nombre', 'slug', 'descripcion', 'parent_id', 'editingCategory']);
        $this->activa = true;
    }
}; ?>

<div class="flex flex-col gap-6">
    <div class="flex items-center justify-between">
        <flux:heading size="xl" level="1">Categorías</flux:heading>
        <flux:button wire:click="create" variant="primary" icon="plus">Nueva Categoría</flux:button>
    </div>

    <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 overflow-hidden">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-neutral-200 bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-800">
                <tr>
                    <th class="p-4 font-medium">Nombre</th>
                    <th class="p-4 font-medium">Slug</th>
                    <th class="p-4 font-medium">Categoría Padre</th>
                    <th class="p-4 font-medium">Estado</th>
                    <th class="p-4 font-medium text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                @forelse($categories as $category)
                    <tr class="bg-white dark:bg-neutral-900">
                        <td class="p-4 font-medium">{{ $category->nombre }}</td>
                        <td class="p-4 text-neutral-500">{{ $category->slug }}</td>
                        <td class="p-4">{{ $category->parent?->nombre ?? '-' }}</td>
                        <td class="p-4">
                            @if($category->activa)
                                <flux:badge color="green">Activa</flux:badge>
                            @else
                                <flux:badge color="red">Inactiva</flux:badge>
                            @endif
                        </td>
                        <td class="p-4 text-right">
                            <flux:button wire:click="edit({{ $category->id }})" variant="ghost" size="sm">Editar</flux:button>
                            <flux:button wire:click="delete({{ $category->id }})" wire:confirm="¿Eliminar esta categoría?" variant="ghost" size="sm" color="red">Eliminar</flux:button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-8 text-center text-neutral-500">No hay categorías registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div>
        {{ $categories->links() }}
    </div>

    <flux:modal wire:model="showModal" class="md:w-3/4 lg:w-1/2">
        <div class="space-y-6">
            <flux:heading size="lg">{{ $isEditing ? 'Editar Categoría' : 'Nueva Categoría' }}</flux:heading>

            <form wire:submit="save" class="space-y-4">
                <flux:input wire:model="nombre" label="Nombre" required />
                <flux:input wire:model="slug" label="Slug (opcional)" placeholder="Se generará automáticamente" />
                
                <flux:select wire:model="parent_id" label="Categoría Padre (opcional)">
                    <option value="">Ninguna (Categoría Principal)</option>
                    @foreach($parentCategories as $parent)
                        @if(!$isEditing || $parent->id !== $editingCategory?->id)
                            <option value="{{ $parent->id }}">{{ $parent->nombre }}</option>
                        @endif
                    @endforeach
                </flux:select>

                <flux:textarea wire:model="descripcion" label="Descripción" />
                
                <flux:switch wire:model="activa" label="Categoría Activa" />

                <div class="flex justify-end gap-2 mt-4">
                    <flux:button wire:click="$set('showModal', false)" variant="ghost">Cancelar</flux:button>
                    <flux:button type="submit" variant="primary">Guardar</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</div>