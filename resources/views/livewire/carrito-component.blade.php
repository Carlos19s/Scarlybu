<div>
    {{-- Botón flotante carrito --}}
    <button wire:click="$toggle('mostrarCheckout')"
            class="fixed bottom-6 right-6 z-50 bg-indigo-600 hover:bg-indigo-700 text-white
                   rounded-full w-14 h-14 shadow-lg flex items-center justify-center transition">
        🛒
        @if(count($carrito) > 0)
            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs
                         w-5 h-5 rounded-full flex items-center justify-center font-bold">
                {{ count($carrito) }}
            </span>
        @endif
    </button>

    {{-- Panel carrito --}}
    @if($mostrarCheckout)
    <div class="fixed inset-0 z-40 flex justify-end" wire:click.self="$set('mostrarCheckout', false)">
        <div class="w-full max-w-md bg-white h-full shadow-2xl overflow-y-auto flex flex-col">

            <div class="p-6 border-b flex justify-between items-center">
                <h2 class="text-xl font-bold text-gray-800">🛒 Mi Carrito</h2>
                <button wire:click="$set('mostrarCheckout', false)" class="text-gray-400 hover:text-gray-600 text-2xl">✕</button>
            </div>

            @if(empty($carrito))
                <div class="flex-1 flex flex-col items-center justify-center text-gray-400 gap-3">
                    <span class="text-6xl">🛍️</span>
                    <p>Tu carrito está vacío</p>
                </div>
            @else
                {{-- Items --}}
                <div class="flex-1 overflow-y-auto p-6 space-y-4">
                    @foreach($carrito as $id => $item)
                    <div class="flex gap-3 items-center">
                        <div class="w-12 h-12 bg-gray-100 rounded-lg overflow-hidden shrink-0">
                            @if($item['imagen'])
                                <img src="{{ asset('storage/'.$item['imagen']) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center">🛍️</div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-800 truncate">{{ $item['nombre'] }}</p>
                            <p class="text-xs text-indigo-600">${{ number_format($item['precio'], 2) }}</p>
                        </div>
                        <div class="flex items-center gap-1">
                            <button wire:click="decrementar({{ $id }})"
                                    class="w-6 h-6 rounded bg-gray-100 hover:bg-gray-200 text-sm font-bold">−</button>
                            <span class="w-6 text-center text-sm font-semibold">{{ $item['cantidad'] }}</span>
                            <button wire:click="incrementar({{ $id }})"
                                    class="w-6 h-6 rounded bg-gray-100 hover:bg-gray-200 text-sm font-bold">+</button>
                        </div>
                        <span class="text-sm font-semibold text-gray-700 w-16 text-right">
                            ${{ number_format($item['precio'] * $item['cantidad'], 2) }}
                        </span>
                        <button wire:click="eliminar({{ $id }})" class="text-red-400 hover:text-red-600 text-xs">✕</button>
                    </div>
                    @endforeach
                </div>

                {{-- Totales --}}
                <div class="p-6 border-t space-y-2">
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Subtotal</span><span>${{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>IVA</span><span>${{ number_format($totalIva, 2) }}</span>
                    </div>
                    <div class="flex justify-between font-bold text-gray-800 text-base border-t pt-2">
                        <span>Total</span>
                        <span class="text-indigo-600">${{ number_format($total, 2) }}</span>
                    </div>

                    @auth
                    {{-- Formulario checkout --}}
                    <div class="mt-4 space-y-3">
                        <input wire:model="cliente_nombre" type="text" placeholder="Nombre completo *"
                               class="w-full rounded-xl border-gray-300 text-sm focus:ring-indigo-500">
                        @error('cliente_nombre') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror

                        <input wire:model="cliente_telefono" type="text" placeholder="Teléfono *"
                               class="w-full rounded-xl border-gray-300 text-sm focus:ring-indigo-500">
                        @error('cliente_telefono') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror

                        <input wire:model="cliente_correo" type="email" placeholder="Correo"
                               class="w-full rounded-xl border-gray-300 text-sm focus:ring-indigo-500">

                        <input wire:model="cliente_documento" type="text" placeholder="Documento"
                               class="w-full rounded-xl border-gray-300 text-sm focus:ring-indigo-500">

                        <textarea wire:model="cliente_direccion" placeholder="Dirección de entrega *" rows="2"
                                  class="w-full rounded-xl border-gray-300 text-sm focus:ring-indigo-500"></textarea>
                        @error('cliente_direccion') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror

                        @error('general') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror

                        <button wire:click="confirmarPedido" wire:loading.attr="disabled"
                                class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white
                                       font-bold rounded-xl transition">
                            <span wire:loading.remove>✅ Confirmar Pedido</span>
                            <span wire:loading>Procesando...</span>
                        </button>
                    </div>
                    @else
                    <a href="{{ route('login') }}"
                       class="block w-full text-center py-3 bg-indigo-600 hover:bg-indigo-700
                              text-white font-bold rounded-xl mt-4">
                        Inicia sesión para comprar
                    </a>
                    @endauth

                    <button wire:click="vaciar"
                            class="w-full py-2 text-sm text-gray-400 hover:text-red-500 transition mt-1">
                        Vaciar carrito
                    </button>
                </div>
            @endif
        </div>
    </div>
    @endif
</div>