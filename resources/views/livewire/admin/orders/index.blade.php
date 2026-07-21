<?php

use Livewire\Volt\Component;
use App\Models\Order;
use App\Models\Product;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

new #[Layout('layouts.app')] #[Title('Pedidos')] class extends Component {
    use WithPagination;

    public bool $showModal = false;
    public ?Order $viewingOrder = null;

    public string $estado = '';
    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function with(): array
    {
        return [
            'orders' => Order::with('user')
                ->when($this->search, fn($q) => $q->where('numero_pedido', 'like', "%{$this->search}%")
                    ->orWhere('cliente_nombre', 'like', "%{$this->search}%"))
                ->latest()
                ->paginate(10),
        ];
    }

    public function viewDetails(Order $order): void
    {
        $this->viewingOrder = $order->load('items.product', 'user');
        $this->estado = $order->estado;
        $this->showModal = true;
    }

    public function updateStatus(): void
    {
        $this->validate([
            'estado' => 'required|string',
        ]);

        if ($this->viewingOrder) {
            $oldEstado = $this->viewingOrder->estado;
            $newEstado = $this->estado;

            // If changing TO cancelado from a non-cancelado state → restore stock
            if ($newEstado === 'cancelado' && $oldEstado !== 'cancelado') {
                foreach ($this->viewingOrder->items as $item) {
                    Product::where('id', $item->product_id)->increment('stock', $item->cantidad);
                }
            }

            // If changing FROM cancelado to a non-cancelado state → deduct stock
            if ($oldEstado === 'cancelado' && $newEstado !== 'cancelado') {
                foreach ($this->viewingOrder->items as $item) {
                    Product::where('id', $item->product_id)->decrement('stock', $item->cantidad);
                }
            }

            $this->viewingOrder->update(['estado' => $newEstado]);
        }
        $this->showModal = false;
    }
}; ?>

<div class="space-y-6">
    <!-- Page Header -->
    <div class="admin-page-header">
        <div>
            <h1 class="admin-page-title">Pedidos</h1>
            <p class="admin-page-subtitle">Gestiona los pedidos de tus clientes</p>
        </div>
    </div>

    <!-- Search -->
    <div class="admin-toolbar">
        <div class="relative flex-1">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar por # pedido o nombre de cliente..."
                   class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm transition-colors">
        </div>
    </div>

    <!-- Orders Table -->
    <div class="admin-table-wrapper animate-fade-in-up">
        <table class="admin-table">
            <thead>
                <tr>
                    <th># Pedido</th>
                    <th>Cliente</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th class="text-right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td>
                            <span class="font-bold text-slate-900 dark:text-slate-100">{{ $order->numero_pedido }}</span>
                        </td>
                        <td>
                            <div class="font-medium text-slate-900 dark:text-slate-100">{{ $order->cliente_nombre }}</div>
                            <div class="text-xs text-slate-500 dark:text-slate-400">{{ $order->cliente_telefono }}</div>
                        </td>
                        <td>
                            <span class="font-semibold text-emerald-600 dark:text-emerald-400">${{ number_format($order->total, 2) }}</span>
                        </td>
                        <td>
                            @if($order->estado == 'entregado')
                                <span class="badge-emerald">Entregado</span>
                            @elseif($order->estado == 'cancelado')
                                <span class="badge-red">Cancelado</span>
                            @elseif($order->estado == 'enviado')
                                <span class="badge-blue">Enviado</span>
                            @elseif($order->estado == 'en_proceso')
                                <span class="badge-orange">En Proceso</span>
                            @else
                                <span class="badge-zinc">{{ ucfirst(str_replace('_', ' ', $order->estado)) }}</span>
                            @endif
                        </td>
                        <td class="text-sm text-slate-500 dark:text-slate-400">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td class="text-right">
                            <button wire:click="viewDetails({{ $order->id }})"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-medium text-emerald-600 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                Ver
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-12">
                            <div class="flex flex-col items-center gap-2 text-slate-400">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                <p class="font-medium">No hay pedidos registrados</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $orders->links() }}
    </div>

    <!-- Order Detail Modal -->
    <flux:modal wire:model="showModal" class="md:w-3/4 lg:w-2/3">
        @if($viewingOrder)
        <div class="space-y-6">
            <div class="flex items-start justify-between">
                <div>
                    <flux:heading size="lg">Pedido #{{ $viewingOrder->numero_pedido }}</flux:heading>
                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ $viewingOrder->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">${{ number_format($viewingOrder->total, 2) }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700">
                <div>
                    <span class="text-xs uppercase tracking-wider font-semibold text-slate-500 dark:text-slate-400">Cliente</span>
                    <p class="font-medium text-slate-900 dark:text-slate-100 mt-1">{{ $viewingOrder->cliente_nombre }}</p>
                    <p class="text-sm text-slate-500">{{ $viewingOrder->cliente_telefono }}</p>
                    <p class="text-sm text-slate-500">{{ $viewingOrder->cliente_correo }}</p>
                </div>
                <div>
                    <span class="text-xs uppercase tracking-wider font-semibold text-slate-500 dark:text-slate-400">Dirección</span>
                    <p class="text-sm text-slate-900 dark:text-slate-100 mt-1">{{ $viewingOrder->cliente_direccion }}</p>
                </div>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-slate-900 dark:text-slate-100 mb-3">Productos del Pedido</h3>
                <div class="admin-table-wrapper">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Cant.</th>
                                <th>Precio</th>
                                <th class="text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($viewingOrder->items as $item)
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3">
                                        @if($item->product && $item->product->imagen)
                                            @php
                                                $imgUrl = str_starts_with($item->product->imagen, 'http') ? $item->product->imagen : asset('storage/' . $item->product->imagen);
                                            @endphp
                                            <div class="w-10 h-10 rounded overflow-hidden bg-slate-100 dark:bg-slate-800 shrink-0">
                                                <img src="{{ $imgUrl }}" alt="{{ $item->product->nombre }}" class="w-full h-full object-cover">
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="font-medium text-slate-900 dark:text-slate-100">{{ $item->product->nombre }}</span>
                                                <a href="{{ $imgUrl }}" target="_blank" class="text-xs text-emerald-600 dark:text-emerald-400 hover:underline mt-0.5 inline-flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                    Ver foto completa
                                                </a>
                                            </div>
                                        @else
                                            <div class="w-10 h-10 rounded bg-slate-100 dark:bg-slate-800 flex items-center justify-center shrink-0">
                                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            </div>
                                            <span class="font-medium text-slate-900 dark:text-slate-100">{{ $item->product?->nombre ?? 'Producto Desconocido' }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td>{{ $item->cantidad }}</td>
                                <td>${{ number_format($item->precio_unitario, 2) }}</td>
                                <td class="text-right font-medium">${{ number_format($item->subtotal, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            @if($viewingOrder->notas)
            <div class="p-4 rounded-xl bg-amber-50 dark:bg-amber-900/10 border border-amber-200 dark:border-amber-800/30">
                <span class="text-xs uppercase tracking-wider font-semibold text-amber-600 dark:text-amber-400">Notas del cliente</span>
                <p class="text-sm text-slate-700 dark:text-slate-300 mt-1">{{ $viewingOrder->notas }}</p>
            </div>
            @endif

            <form wire:submit="updateStatus" class="flex items-end gap-3 p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700">
                <div class="flex-1">
                    <flux:select wire:model="estado" label="Cambiar Estado">
                        <option value="no_revisado">No Revisado</option>
                        <option value="en_proceso">En Proceso</option>
                        <option value="enviado">Enviado</option>
                        <option value="entregado">Entregado</option>
                        <option value="cancelado">Cancelado</option>
                    </flux:select>
                </div>
                <flux:button type="submit" variant="primary" class="!bg-emerald-600 hover:!bg-emerald-700">Actualizar</flux:button>
            </form>

            <div class="flex flex-wrap justify-between items-center pt-4 border-t border-slate-200 dark:border-slate-700 gap-3">
                <div class="flex gap-3">
                    <a href="{{ route('store.order.pdf', $viewingOrder) }}" target="_blank"
                       class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-sm font-medium text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Descargar PDF
                    </a>
                    <a href="https://wa.me/{{ preg_replace('/^0/', '593', preg_replace('/[^0-9]/', '', $viewingOrder->cliente_telefono)) }}?text={{ urlencode('Hola ' . $viewingOrder->cliente_nombre . ', le contactamos de Scarlybu respecto a su pedido #' . $viewingOrder->numero_pedido) }}"
                       target="_blank"
                       class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-sm font-medium text-emerald-600 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        WhatsApp
                    </a>
                </div>
                <flux:button wire:click="$set('showModal', false)" variant="ghost">Cerrar</flux:button>
            </div>
        </div>
        @endif
    </flux:modal>
</div>