<x-layouts::app :title="'Pedido Confirmado'">
<div class="max-w-lg mx-auto px-4 py-20 text-center">
    <div class="text-7xl mb-6">🎉</div>
    <h1 class="text-3xl font-bold text-gray-800 mb-2">¡Pedido confirmado!</h1>
    <p class="text-gray-500 mb-2">Tu número de pedido es:</p>
    <p class="text-2xl font-bold text-indigo-600 mb-6">{{ $order->numero_pedido }}</p>
    <p class="text-gray-600 mb-8">Total: <strong>${{ number_format($order->total, 2) }}</strong></p>
    <div class="flex gap-4 justify-center">
        <a href="{{ route('pedidos.historial') }}"
           class="px-6 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 font-medium">
            Ver mis pedidos
        </a>
        <a href="{{ route('catalogo.index') }}"
           class="px-6 py-3 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 font-medium">
            Seguir comprando
        </a>
    </div>
</div>
</x-layouts::app>