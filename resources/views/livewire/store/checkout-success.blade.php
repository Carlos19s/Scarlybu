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
    <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8 py-10 sm:py-20 text-center">
        <div class="w-20 h-20 sm:w-24 sm:h-24 bg-emerald-100 text-emerald-500 rounded-full flex items-center justify-center mx-auto mb-6 sm:mb-8">
            <svg class="w-10 h-10 sm:w-12 sm:h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        </div>

        <h1 class="text-3xl sm:text-4xl font-bold text-zinc-900 dark:text-white mb-3 sm:mb-4">¡Pedido Recibido!</h1>
        <p class="text-lg sm:text-xl text-zinc-600 dark:text-zinc-400 mb-6 sm:mb-8">Tu número de pedido es: <span class="font-bold text-zinc-900 dark:text-white">{{ $order->numero_pedido }}</span></p>

        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-2xl sm:rounded-3xl p-5 sm:p-8 shadow-sm mb-6 sm:mb-8 text-left">
            <h2 class="text-xl sm:text-2xl font-bold text-zinc-900 dark:text-white mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Instrucciones de Pago
            </h2>
            <div class="space-y-4 text-zinc-600 dark:text-zinc-300 text-sm sm:text-base">
                <p class="font-medium text-zinc-800 dark:text-zinc-200">
                    Para reservar tu pedido deposita o puedes hacer el pago físicamente acercándote al local.
                    <span class="block mt-2 text-rose-600 dark:text-rose-400 font-bold">Debe enviar el comprobante de pago al WhatsApp y una vez verificado seguirá con el proceso.</span>
                </p>
                <div class="bg-zinc-50 dark:bg-zinc-800 p-4 sm:p-5 rounded-xl border border-zinc-200 dark:border-zinc-700 font-mono text-xs sm:text-sm space-y-1.5 break-all">
                    <p><strong>Banco:</strong> Guayaquil S.A</p>
                    <p><strong>Titular:</strong> Rodolfo Romo</p>
                    <p><strong>Nro de cuenta:</strong> 1002996377001</p>
                    <p><strong>CEDULA/RUC:</strong> 21239831</p>
                    <p><strong>Tipo de cuenta:</strong> Ahorros</p>
                    <p class="pt-2 border-t border-zinc-200 dark:border-zinc-700"><strong>Total a Pagar:</strong> <span class="text-emerald-600 dark:text-emerald-400 font-bold">${{ number_format($order->total, 2) }}</span></p>
                </div>
                <p class="text-xs sm:text-sm text-zinc-500 dark:text-zinc-400">Una vez realizado el pago, envíanos el comprobante por WhatsApp adjuntando tu número de pedido (<strong>{{ $order->numero_pedido }}</strong>) o tu Nota de Pedido.</p>
            </div>
        </div>

        <div class="flex flex-col gap-3 sm:flex-row sm:gap-4 justify-center">
            <a href="{{ route('store.order.pdf', $order) }}" target="_blank" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 font-bold rounded-xl shadow-lg hover:bg-zinc-800 dark:hover:bg-zinc-100 transition-all text-sm sm:text-base">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Descargar Nota de Pedido
            </a>
            <a href="https://wa.me/593991329846?text={{ urlencode('Hola, acabo de realizar el pedido #' . $order->numero_pedido . ' y deseo enviar mi comprobante de pago para continuar con el proceso.') }}" target="_blank"
               class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 bg-[#25d366] hover:bg-[#20ba5a] text-white font-bold rounded-xl shadow-lg shadow-[#25d366]/20 transition-all hover:scale-105 active:scale-95 text-sm sm:text-base">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
                WhatsApp
            </a>
            <a href="{{ route('store.home') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-3 border border-zinc-300 dark:border-zinc-600 text-zinc-700 dark:text-zinc-300 font-bold rounded-xl hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-all text-sm sm:text-base" wire:navigate>
                Volver a la Tienda
            </a>
        </div>
    </div>
</div>
