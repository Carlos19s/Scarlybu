<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Category;
use App\Models\Product;

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
        $categorias = Category::where('activa', true)->get();

        $productos = Product::with('category')
            ->where('activo', true)
            ->where('stock', '>', 0)
            ->when($this->buscar, function ($q) {
                $q->where('nombre', 'ilike', '%' . $this->buscar . '%');
            })
            ->when($this->categoriaId, function ($q) {
                $q->where('category_id', $this->categoriaId);
            })
            ->orderBy($this->ordenar)
            ->paginate(12);

        return view('livewire.catalogo-component', [
            'categorias' => $categorias,
            'productos' => $productos,
        ]);
    }
}