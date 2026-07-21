<?php

namespace App\Http\Controllers;

use App\Models\Category;

class CatalogoController extends Controller
{
    public function index()
    {
        $categorias = Category::with(['children', 'products' => fn ($q) => $q->where('activo', true)->where('stock', '>', 0)->take(8),
        ])
            ->whereNull('parent_id')
            ->where('activa', true)
            ->get();

        return view('catalogo.index', compact('categorias'));
    }
}
