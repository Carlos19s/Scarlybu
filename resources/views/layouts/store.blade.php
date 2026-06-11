<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-900 text-zinc-800 dark:text-zinc-200 antialiased">
        {{-- Header / Navbar --}}
        <header class="sticky top-0 z-50 border-b border-zinc-200 dark:border-zinc-700 bg-white/80 dark:bg-zinc-900/80 backdrop-blur-lg">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    {{-- Logo --}}
                    <a href="{{ route('store.home') }}" class="flex items-center gap-2 group" wire:navigate>
                        <span class="text-2xl font-black tracking-tight bg-gradient-to-r from-pink-500 via-rose-500 to-orange-400 bg-clip-text text-transparent">
                            Scarlybu
                        </span>
                    </a>

                    {{-- Category Nav (Desktop) --}}
                    <nav class="hidden md:flex items-center gap-1">
                        @php
                            $navCategories = \App\Models\Category::whereNull('parent_id')->where('activa', true)->get();
                        @endphp
                        @foreach($navCategories as $cat)
                            <a href="{{ route('store.category', $cat->slug) }}"
                               class="px-3 py-2 rounded-lg text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors"
                               wire:navigate>
                                {{ $cat->nombre }}
                            </a>
                        @endforeach
                    </nav>

                    {{-- Right side: Cart + Auth --}}
                    <div class="flex items-center gap-3">
                        <a href="{{ route('store.cart') }}" class="relative p-2 rounded-lg text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors" wire:navigate>
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                            </svg>
                            @php $cartCount = count(session('cart', [])); @endphp
                            @if($cartCount > 0)
                                <span class="absolute -top-0.5 -right-0.5 flex h-5 w-5 items-center justify-center rounded-full bg-rose-500 text-[10px] font-bold text-white">
                                    {{ $cartCount }}
                                </span>
                            @endif
                        </a>

                        @auth
                            <a href="{{ route('dashboard') }}" class="text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition-colors" wire:navigate>
                                Mi Cuenta
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition-colors" wire:navigate>
                                Iniciar Sesión
                            </a>
                        @endauth

                        {{-- Mobile Menu Toggle --}}
                        <button
                            x-data="{ open: false }"
                            @click="open = !open; $dispatch('toggle-mobile-menu')"
                            class="md:hidden p-2 rounded-lg text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Mobile Nav --}}
                <div x-data="{ open: false }" @toggle-mobile-menu.window="open = !open" x-show="open" x-collapse class="md:hidden border-t border-zinc-200 dark:border-zinc-700 pb-4">
                    <nav class="flex flex-col gap-1 pt-2">
                        @foreach($navCategories as $cat)
                            <a href="{{ route('store.category', $cat->slug) }}"
                               class="px-3 py-2 rounded-lg text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors"
                               wire:navigate>
                                {{ $cat->nombre }}
                            </a>
                        @endforeach
                    </nav>
                </div>
            </div>
        </header>

        {{-- Main Content --}}
        <main>
            {{ $slot }}
        </main>

        {{-- Footer --}}
        <footer class="mt-16 border-t border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-900">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div>
                        <span class="text-xl font-black bg-gradient-to-r from-pink-500 via-rose-500 to-orange-400 bg-clip-text text-transparent">
                            Scarlybu
                        </span>
                        <p class="mt-3 text-sm text-zinc-500 dark:text-zinc-400">
                            Tu tienda favorita de gorras, accesorios, cosméticos, ropa y zapatos.
                        </p>
                    </div>
                    <div>
                        <h4 class="font-semibold text-sm mb-3 text-zinc-700 dark:text-zinc-300">Categorías</h4>
                        <ul class="space-y-2 text-sm text-zinc-500 dark:text-zinc-400">
                            @foreach($navCategories as $cat)
                                <li><a href="{{ route('store.category', $cat->slug) }}" class="hover:text-rose-500 transition-colors" wire:navigate>{{ $cat->nombre }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold text-sm mb-3 text-zinc-700 dark:text-zinc-300">Contacto</h4>
                        <ul class="space-y-2 text-sm text-zinc-500 dark:text-zinc-400">
                            <li>📍 Ibarra, Ecuador</li>
                            <li>📱 +593 98 095 1601</li>
                            <li>✉️ contacto@scarlybu.com</li>
                        </ul>
                    </div>
                </div>
                <div class="mt-8 pt-6 border-t border-zinc-200 dark:border-zinc-800 text-center text-xs text-zinc-400">
                    &copy; {{ date('Y') }} Scarlybu. Todos los derechos reservados.
                </div>
            </div>
        </footer>

        @fluxScripts
    </body>
</html>
