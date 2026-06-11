<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class CatalogoComponent extends Component
{
    use WithPagination;

    public string $buscar = '';
    public int $categoriaId = 0;
    public string $ordenar = 'nombre';

    public function updatingBuscar()
    {
        $this->resetPage();
    }

    public function updatingCategoriaId()
    {
        $this->resetPage();
    }

    public function render()
{
    $categorias = \App\Models\Category::where('activa', true)->get();

    $productos = \App\Models\Product::with('category')
        ->where('activo', true)
        ->where('stock', '>', 0)
        ->when($this->buscar, fn($q) =>
            $q->where('nombre', 'ilike', '%'.$this->buscar.'%')
        )
        ->when($this->categoriaId, fn($q) =>
            $q->where('category_id', $this->categoriaId)
        )
        ->orderBy($this->ordenar)
        ->paginate(12);

    return view('livewire.catalogo-component', [
        'productos' => $productos,
        'categorias' => $categorias // 🔥 ESTE ES EL QUE TE FALTA
    ]);
}
}