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
    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function with(): array
    {
        return [
            'categories' => Category::with('parent')
                ->when($this->search, fn($q) => $q->where('nombre', 'like', "%{$this->search}%"))
                ->latest()
                ->paginate(10),
            'parentCategories' => Category::whereNull('parent_id')->get(),
        ];
    }

    public function create(): void
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function edit(Category $category): void
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

    public function save(): void
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

    public function delete(Category $category): void
    {
        $category->delete();
    }

    private function resetForm(): void
    {
        $this->reset(['nombre', 'slug', 'descripcion', 'parent_id', 'editingCategory']);
        $this->activa = true;
    }
}; ?>

<div class="space-y-6">
    <!-- Page Header -->
    <div class="admin-page-header">
        <div>
            <h1 class="admin-page-title">Categorías</h1>
            <p class="admin-page-subtitle">Organiza los productos de tu tienda</p>
        </div>
        <button wire:click="create"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-sm transition-colors shadow-lg shadow-emerald-600/20">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Nueva Categoría
        </button>
    </div>

    <!-- Search -->
    <div class="admin-toolbar">
        <div class="relative flex-1">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar categorías..."
                   class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm transition-colors">
        </div>
    </div>

    <!-- Categories Table -->
    <div class="admin-table-wrapper animate-fade-in-up">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Slug</th>
                    <th>Categoría Padre</th>
                    <th>Estado</th>
                    <th class="text-right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400">
                                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                </div>
                                <span class="font-medium text-slate-900 dark:text-slate-100">{{ $category->nombre }}</span>
                            </div>
                        </td>
                        <td class="text-slate-500 dark:text-slate-400 font-mono text-xs">{{ $category->slug }}</td>
                        <td>
                            @if($category->parent)
                                <span class="badge-blue">{{ $category->parent->nombre }}</span>
                            @else
                                <span class="text-slate-400 text-sm">—</span>
                            @endif
                        </td>
                        <td>
                            @if($category->activa)
                                <span class="badge-emerald">Activa</span>
                            @else
                                <span class="badge-red">Inactiva</span>
                            @endif
                        </td>
                        <td class="text-right">
                            <div class="inline-flex items-center gap-1">
                                <button wire:click="edit({{ $category->id }})"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-medium text-emerald-600 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Editar
                                </button>
                                <button wire:click="delete({{ $category->id }})" wire:confirm="¿Eliminar esta categoría?"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-medium text-red-500 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-12">
                            <div class="flex flex-col items-center gap-2 text-slate-400">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                <p class="font-medium">No hay categorías registradas</p>
                                <p class="text-sm">Crea una nueva categoría para organizar tus productos</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $categories->links() }}
    </div>

    <!-- Category Modal -->
    <flux:modal wire:model="showModal" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ $isEditing ? 'Editar Categoría' : 'Nueva Categoría' }}</flux:heading>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ $isEditing ? 'Modifica los datos de la categoría' : 'Completa los datos de la nueva categoría' }}</p>
            </div>

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

                <div class="flex justify-end gap-2 pt-2">
                    <flux:button wire:click="$set('showModal', false)" variant="ghost">Cancelar</flux:button>
                    <flux:button type="submit" variant="primary" class="!bg-emerald-600 hover:!bg-emerald-700">Guardar</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</div>