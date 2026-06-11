<?php

use Livewire\Volt\Component;
use App\Models\Order;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

new #[Layout('layouts.store')] #[Title('Pedido Confirmado - Scarlybu')] class extends Component {
    public Order $order;

    public function mount(Order $order)
    {
        // Ensure the logged in user owns this order
        if (auth()->id() !== $order->user_id) {
            abort(403);
        }
        $this->order = $order;
    }
}; ?>

<div>
    <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8 py-20 text-center">
        <div class="w-24 h-24 bg-emerald-100 text-emerald-500 rounded-full flex items-center justify-center mx-auto mb-8">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        </div>

        <h1 class="text-4xl font-bold text-zinc-900 dark:text-white mb-4">¡Pedido Recibido!</h1>
        <p class="text-xl text-zinc-600 dark:text-zinc-400 mb-8">Tu número de pedido es: <span class="font-bold text-zinc-900 dark:text-white">{{ $order->numero_pedido }}</span></p>

        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-3xl p-8 shadow-sm mb-8 text-left">
            <h2 class="text-2xl font-bold text-zinc-900 dark:text-white mb-4 flex items-center gap-2">
                <svg class="w-6 h-6 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Instrucciones de Pago
            </h2>
            <div class="space-y-4 text-zinc-600 dark:text-zinc-300">
                <p>Para procesar y enviar tu pedido, por favor realiza el pago por transferencia o depósito a la siguiente cuenta:</p>
                <div class="bg-zinc-50 dark:bg-zinc-800 p-4 rounded-xl border border-zinc-200 dark:border-zinc-700 font-mono text-sm">
                    <p><strong>Banco:</strong> [Banco de Ejemplo]</p>
                    <p><strong>Cuenta Ahorros:</strong> 1234567890</p>
                    <p><strong>Titular:</strong> Scarlybu C.A.</p>
                    <p><strong>Cédula/RUC:</strong> 0000000000</p>
                    <p><strong>Total a Pagar:</strong> ${{ number_format($order->total, 2) }}</p>
                </div>
                <p class="font-bold text-rose-600">Importante:</p>
                <p>Una vez realizado el pago, envíanos el comprobante por WhatsApp al número <strong>+593 99 999 9999</strong> adjuntando tu número de pedido ({{ $order->numero_pedido }}) o tu Nota de Pedido.</p>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('store.order.pdf', $order) }}" target="_blank" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 font-bold rounded-xl shadow-lg hover:bg-zinc-800 dark:hover:bg-zinc-100 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Descargar Nota de Pedido
            </a>
            <a href="{{ route('store.home') }}" class="inline-flex items-center justify-center px-6 py-3 border border-zinc-300 dark:border-zinc-600 text-zinc-700 dark:text-zinc-300 font-bold rounded-xl hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-all" wire:navigate>
                Volver a la Tienda
            </a>
        </div>
    </div>
</div>
