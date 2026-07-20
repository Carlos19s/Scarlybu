<?php

namespace App\Http\Controllers\Gerente;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductoController extends Controller
{
    private function autorizar(): void
    {
        abort_unless(auth()->check() && auth()->user()->isGerente(), 403);
    }

    public function index()
    {
        $products = Product::with('category')->latest()->paginate(15);

        return view('gerente.productos.index', compact('products'));
    }

    public function create()
    {
        $categorias = Category::where('activa', true)->get();

        return view('gerente.productos.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:150',
            'descripcion' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'precio_compra' => 'required|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'iva_porcentaje' => 'required|numeric|min:0|max:100',
            'stock' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'fecha_caducidad' => 'nullable|date',
            'imagen' => 'nullable|image|max:2048',
            'activo' => 'boolean',
        ]);

        $data['slug'] = Str::slug($data['nombre']).'-'.uniqid();

        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->storePublicly('productos', 'public');
        }

        Product::create($data);

        return redirect()->route('gerente.productos.index')->with('mensaje', 'Producto creado.');
    }

    public function edit(Product $producto)
    {
        $categorias = Category::where('activa', true)->get();

        return view('gerente.productos.edit', compact('producto', 'categorias'));
    }

    public function update(Request $request, Product $producto)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:150',
            'descripcion' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'precio_compra' => 'required|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'iva_porcentaje' => 'required|numeric|min:0|max:100',
            'stock' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'fecha_caducidad' => 'nullable|date',
            'imagen' => 'nullable|image|max:2048',
            'activo' => 'boolean',
        ]);

        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->storePublicly('productos', 'public');
        }

        $producto->update($data);

        return redirect()->route('gerente.productos.index')->with('mensaje', 'Producto actualizado.');
    }

    public function destroy(Product $producto)
    {
        $producto->delete();

        return redirect()->route('gerente.productos.index')->with('mensaje', 'Producto eliminado.');
    }
}
