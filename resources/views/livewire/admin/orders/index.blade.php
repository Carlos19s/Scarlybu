<?php

use Livewire\Volt\Component;
use App\Models\Order;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

new #[Layout('layouts.app')] #[Title('Pedidos')] class extends Component {
    use WithPagination;

    public bool $showModal = false;
    public ?Order $viewingOrder = null;
    
    public string $estado = '';

    public function with(): array
    {
        return [
            'orders' => Order::with('user')->latest()->paginate(10),
        ];
    }

    public function viewDetails(Order $order)
    {
        $this->viewingOrder = $order->load('items.product', 'user');
        $this->estado = $order->estado;
        $this->showModal = true;
    }

    public function updateStatus()
    {
        $this->validate([
            'estado' => 'required|string',
        ]);

        if ($this->viewingOrder) {
            $this->viewingOrder->update(['estado' => $this->estado]);
        }
        $this->showModal = false;
    }
}; ?>

<div class="flex flex-col gap-6">
    <div class="flex items-center justify-between">
        <flux:heading size="xl" level="1">Pedidos</flux:heading>
    </div>

    <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 overflow-hidden">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-neutral-200 bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-800">
                <tr>
                    <th class="p-4 font-medium"># Pedido</th>
                    <th class="p-4 font-medium">Cliente</th>
                    <th class="p-4 font-medium">Total</th>
                    <th class="p-4 font-medium">Estado</th>
                    <th class="p-4 font-medium">Fecha</th>
                    <th class="p-4 font-medium text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                @forelse($orders as $order)
                    <tr class="bg-white dark:bg-neutral-900">
                        <td class="p-4 font-bold">{{ $order->numero_pedido }}</td>
                        <td class="p-4">
                            <div>{{ $order->cliente_nombre }}</div>
                            <div class="text-xs text-neutral-500">{{ $order->cliente_telefono }}</div>
                        </td>
                        <td class="p-4 font-medium">${{ number_format($order->total, 2) }}</td>
                        <td class="p-4">
                            @if($order->estado == 'entregado')
                                <flux:badge color="green">Entregado</flux:badge>
                            @elseif($order->estado == 'cancelado')
                                <flux:badge color="red">Cancelado</flux:badge>
                            @else
                                <flux:badge color="zinc">{{ ucfirst(str_replace('_', ' ', $order->estado)) }}</flux:badge>
                            @endif
                        </td>
                        <td class="p-4 text-neutral-500">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td class="p-4 text-right">
                            <flux:button wire:click="viewDetails({{ $order->id }})" variant="ghost" size="sm">Ver Detalles</flux:button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-neutral-500">No hay pedidos registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div>
        {{ $orders->links() }}
    </div>

    <flux:modal wire:model="showModal" class="md:w-3/4 lg:w-2/3">
        @if($viewingOrder)
        <div class="space-y-6">
            <div class="flex items-start justify-between">
                <div>
                    <flux:heading size="lg">Pedido #{{ $viewingOrder->numero_pedido }}</flux:heading>
                    <p class="text-sm text-neutral-500">Fecha: {{ $viewingOrder->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div class="text-right">
                    <p class="font-bold text-lg">${{ number_format($viewingOrder->total, 2) }}</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 text-sm border-y border-neutral-200 dark:border-neutral-700 py-4">
                <div>
                    <span class="text-neutral-500 block">Cliente:</span>
                    <strong>{{ $viewingOrder->cliente_nombre }}</strong>
                    <p>{{ $viewingOrder->cliente_telefono }}</p>
                    <p>{{ $viewingOrder->cliente_correo }}</p>
                </div>
                <div>
                    <span class="text-neutral-500 block">Dirección de Entrega:</span>
                    <p>{{ $viewingOrder->cliente_direccion }}</p>
                </div>
            </div>

            <div>
                <flux:heading size="md" class="mb-2">Productos</flux:heading>
                <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 overflow-hidden">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-neutral-50 dark:bg-neutral-800">
                            <tr>
                                <th class="p-3">Producto</th>
                                <th class="p-3">Cant.</th>
                                <th class="p-3">Precio</th>
                                <th class="p-3 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                            @foreach($viewingOrder->items as $item)
                            <tr>
                                <td class="p-3">{{ $item->product?->nombre ?? 'Producto Desconocido' }}</td>
                                <td class="p-3">{{ $item->cantidad }}</td>
                                <td class="p-3">${{ number_format($item->precio_unitario, 2) }}</td>
                                <td class="p-3 text-right">${{ number_format($item->subtotal, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            @if($viewingOrder->notas)
            <div class="bg-neutral-50 dark:bg-neutral-800 p-4 rounded-lg">
                <span class="font-medium text-sm block mb-1">Notas del cliente:</span>
                <p class="text-sm">{{ $viewingOrder->notas }}</p>
            </div>
            @endif

            <form wire:submit="updateStatus" class="flex items-end gap-4 bg-neutral-100 dark:bg-neutral-800/50 p-4 rounded-lg">
                <div class="flex-1">
                    <flux:select wire:model="estado" label="Cambiar Estado">
                        <option value="no_revisado">No Revisado</option>
                        <option value="en_proceso">En Proceso</option>
                        <option value="enviado">Enviado</option>
                        <option value="entregado">Entregado</option>
                        <option value="cancelado">Cancelado</option>
                    </flux:select>
                </div>
                <flux:button type="submit" variant="primary">Actualizar Estado</flux:button>
            </form>
            
            <div class="flex justify-between items-center mt-6 border-t border-neutral-200 dark:border-neutral-700 pt-4">
                <div class="flex gap-4">
                    <a href="{{ route('store.order.pdf', $viewingOrder) }}" target="_blank" class="text-sm font-medium text-rose-600 hover:text-rose-700 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Descargar PDF
                    </a>
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $viewingOrder->cliente_telefono) }}?text={{ urlencode('Hola ' . $viewingOrder->cliente_nombre . ', le contactamos de Scarlybu respecto a su pedido #' . $viewingOrder->numero_pedido) }}" target="_blank" class="text-sm font-medium text-green-600 hover:text-green-700 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.711.848 2.872.848 3.182 0 5.768-2.585 5.768-5.766 0-3.181-2.586-5.767-5.768-5.767m0-2c4.285 0 7.768 3.483 7.768 7.767 0 4.285-3.483 7.766-7.768 7.766-1.401 0-2.738-.382-3.882-1.054l-4.149 1.09 1.107-4.043c-.732-1.168-1.121-2.527-1.121-3.953 0-4.284 3.483-7.767 7.768-7.767m3.939 10.375c-.215.607-1.25.111-1.396-.117-.145-.228-1.503-1.89-1.503-1.89s-.144-.228-.016-.403c.129-.174.582-.581.582-.581s.145-.148.243.029c.099.176.435.606.435.606s.08.131.258.043c.176-.089 1.091-.403 1.503-.946.411-.543-.058-.696-.058-.696s-.662-.314-.814-.403c-.152-.089-.263-.127-.129-.356.134-.229.582-.76.711-.905.129-.145.263-.174.394-.145.131.029.814.382.814.382s.152.057.176.229c.023.172-.215 1.25-.215 1.25z"/></svg>
                        Contactar WhatsApp
                    </a>
                </div>
                <flux:button wire:click="$set('showModal', false)" variant="ghost">Cerrar</flux:button>
            </div>
        </div>
        @endif
    </flux:modal>
</div>