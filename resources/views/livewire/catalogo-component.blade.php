<div class="max-w-7xl mx-auto px-4 py-10">

    {{-- Filtros --}}
    <div class="flex flex-wrap gap-4 mb-8 items-center">
        <input wire:model.live="buscar" type="text" placeholder="Buscar producto..."
               class="rounded-xl border-gray-300 focus:ring-indigo-500 text-sm px-4 py-2 flex-1 min-w-[200px]">

        <select wire:model.live="categoriaId"
                class="rounded-xl border-gray-300 focus:ring-indigo-500 text-sm px-4 py-2">
            <option value="0">Todas las categorías</option>
            @foreach($categorias as $cat)
                <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
            @endforeach
        </select>

        <select wire:model.live="ordenar"
                class="rounded-xl border-gray-300 focus:ring-indigo-500 text-sm px-4 py-2">
            <option value="nombre">Nombre A-Z</option>
            <option value="precio_venta">Menor precio</option>
        </select>
    </div>

    {{-- Grid productos --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5">
        @forelse($productos as $producto)
        <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition overflow-hidden group">
            <div class="h-44 bg-gray-100 overflow-hidden">
                @if($producto->imagen)
                    <img src="{{ asset('storage/'.$producto->imagen) }}"
                         alt="{{ $producto->nombre }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                @else
                    <div class="w-full h-full flex items-center justify-center text-5xl text-gray-200">🛍️</div>
                @endif
            </div>

            <div class="p-4">
                <p class="text-xs text-indigo-500 font-medium mb-1">{{ $producto->category->nombre }}</p>
                <h3 class="font-semibold text-gray-800 text-sm truncate">{{ $producto->nombre }}</h3>

                <div class="flex items-center justify-between mt-2">
                    <span class="text-indigo-600 font-bold text-base">
                        ${{ number_format($producto->precio_venta, 2) }}
                    </span>
                    @if($producto->bajo_stock)
                        <span class="text-xs text-orange-400">Poco stock</span>
                    @endif
                </div>

                {{-- Livewire agrega al carrito --}}
                <button wire:click="$dispatch('agregarAlCarrito', { id: {{ $producto->id }} })"
                        class="w-full mt-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white
                               text-sm font-medium rounded-xl transition">
                    + Agregar al carrito
                </button>
            </div>
        </div>
        @empty
            <div class="col-span-4 text-center py-20 text-gray-400">
                <p class="text-4xl mb-3">🔍</p>
                <p>No se encontraron productos.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-8">{{ $productos->links() }}</div>
</div>