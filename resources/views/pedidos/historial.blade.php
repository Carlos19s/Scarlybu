<x-layouts::app :title="'Mis Pedidos'">
<div class="max-w-5xl mx-auto px-4 py-10">
    <h1 class="text-2xl font-bold text-gray-800 mb-8">📋 Mis Pedidos</h1>

    @forelse($pedidos as $pedido)
    <div class="bg-white rounded-2xl shadow-sm mb-5 overflow-hidden">
        <div class="flex justify-between items-center p-4 bg-gray-50 border-b">
            <div class="flex items-center gap-3">
                <span class="font-bold text-gray-800">{{ $pedido->numero_pedido }}</span>
                <span class="text-sm text-gray-500">{{ $pedido->created_at->format('d/m/Y') }}</span>
                <span class="text-xs px-2 py-0.5 rounded-full font-medium
                    {{ $pedido->estado === 'revisado' ? 'bg-green-100 text-green-700' :
                       ($pedido->estado === 'cancelado' ? 'bg-red-100 text-red-700' :
                       ($pedido->estado === 'pendiente' ? 'bg-yellow-100 text-yellow-700' :
                       'bg-gray-100 text-gray-600')) }}">
                    {{ ucfirst(str_replace('_', ' ', $pedido->estado)) }}
                </span>
            </div>
            <span class="font-bold text-indigo-600">${{ number_format($pedido->total, 2) }}</span>
        </div>
        <table class="w-full text-sm">
            <tbody class="divide-y divide-gray-50">
                @foreach($pedido->items as $item)
                <tr class="hover:bg-gray-50">
                    <td class="p-3 text-gray-700">{{ $item->product->nombre }}</td>
                    <td class="p-3 text-center text-gray-500">×{{ $item->cantidad }}</td>
                    <td class="p-3 text-right font-semibold text-gray-800">
                        ${{ number_format($item->subtotal, 2) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @empty
        <div class="text-center py-20 text-gray-400">
            <p class="text-5xl mb-4">📭</p>
            <p>No tienes pedidos aún.</p>
            <a href="{{ route('catalogo.index') }}" class="mt-4 inline-block text-indigo-600 hover:underline">
                Ir al catálogo →
            </a>
        </div>
    @endforelse

    <div class="mt-4">{{ $pedidos->links() }}</div>
</div>
</x-layouts::app>