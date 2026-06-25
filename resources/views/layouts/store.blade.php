<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-200 antialiased">
        {{-- Header / Navbar --}}
        <header class="sticky top-0 z-50 border-b border-slate-200/80 dark:border-slate-800 bg-white/90 dark:bg-slate-950/90 backdrop-blur-xl" x-data="{ mobileOpen: false }">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    {{-- Logo --}}
                    <a href="{{ route('store.home') }}" class="flex items-center gap-2.5 group" wire:navigate>
                        <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-gradient-to-br from-emerald-500 to-emerald-600 shadow-lg shadow-emerald-500/20 group-hover:shadow-emerald-500/40 transition-shadow">
                            <svg class="w-4.5 h-4.5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                        </div>
                        <span class="text-xl font-black tracking-tight bg-gradient-to-r from-emerald-600 to-teal-500 bg-clip-text text-transparent">
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
                               class="px-3 py-2 rounded-lg text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-colors"
                               wire:navigate>
                                {{ $cat->nombre }}
                            </a>
                        @endforeach
                    </nav>

                    {{-- Right side: Cart + Auth + Funciones / Mis notas de pedido --}}
                    <div class="flex items-center gap-2 sm:gap-3">
                        {{-- Cart --}}
                        <livewire:store.cart-badge />

                        @auth
                            <a href="{{ route('dashboard') }}" class="text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors hidden sm:block" wire:navigate>
                                Mi Cuenta
                            </a>

                            {{-- Role-based Action Button --}}
                            @php $userRole = auth()->user()->roles->first()?->name ?? 'cliente'; @endphp
                            @if($userRole !== 'cliente')
                                <a href="{{ route('dashboard') }}"
                                   class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold transition-colors shadow-md shadow-emerald-600/20 hover:shadow-emerald-600/30"
                                   wire:navigate>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    Funciones
                                </a>
                            @else
                                <a href="{{ route('store.orders') }}"
                                   class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold transition-colors shadow-md shadow-emerald-600/20 hover:shadow-emerald-600/30"
                                   wire:navigate>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                    Mis notas de pedido
                                </a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors" wire:navigate>
                                Iniciar Sesión
                            </a>
                        @endauth

                        {{-- Mobile Menu Toggle --}}
                        <button @click="mobileOpen = !mobileOpen"
                                class="md:hidden p-2 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800">
                            <svg x-show="!mobileOpen" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" /></svg>
                            <svg x-show="mobileOpen" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Mobile Nav --}}
                <div x-show="mobileOpen" x-cloak
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     class="md:hidden border-t border-slate-200 dark:border-slate-800 pb-4">
                    <nav class="flex flex-col gap-1 pt-2">
                        @foreach($navCategories as $cat)
                            <a href="{{ route('store.category', $cat->slug) }}"
                               class="px-3 py-2 rounded-lg text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-colors"
                               wire:navigate>
                                {{ $cat->nombre }}
                            </a>
                        @endforeach
                        @auth
                            <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded-lg text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-colors sm:hidden" wire:navigate>
                                Mi Cuenta
                            </a>
                            @if(auth()->user()->roles->first()?->name === 'cliente')
                                <a href="{{ route('store.orders') }}" class="px-3 py-2 rounded-lg text-sm font-medium text-emerald-600 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-colors" wire:navigate>
                                    Mis notas de pedido
                                </a>
                            @else
                                <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded-lg text-sm font-medium text-emerald-600 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-colors" wire:navigate>
                                    Funciones
                                </a>
                            @endif
                        @endauth
                    </nav>
                </div>
            </div>
        </header>

        {{-- Main Content --}}
        <main>
            {{ $slot }}
        </main>

        {{-- Footer --}}
        <footer class="mt-16 border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div>
                        <div class="flex items-center gap-2.5 mb-3">
                            <div class="flex items-center justify-center w-7 h-7 rounded-lg bg-gradient-to-br from-emerald-500 to-emerald-600">
                                <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                            </div>
                            <span class="text-lg font-black tracking-tight bg-gradient-to-r from-emerald-600 to-teal-500 bg-clip-text text-transparent">
                                Scarlybu
                            </span>
                        </div>
                        <p class="text-sm text-slate-500 dark:text-slate-400">
                            Tu tienda favorita de gorras, accesorios, cosméticos, ropa y zapatos.
                        </p>
                    </div>
                    <div>
                        <h4 class="font-semibold text-sm mb-3 text-slate-700 dark:text-slate-300">Categorías</h4>
                        <ul class="space-y-2 text-sm text-slate-500 dark:text-slate-400">
                            @foreach($navCategories as $cat)
                                <li><a href="{{ route('store.category', $cat->slug) }}" class="hover:text-emerald-500 transition-colors" wire:navigate>{{ $cat->nombre }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold text-sm mb-3 text-slate-700 dark:text-slate-300">Contacto</h4>
                        <ul class="space-y-2 text-sm text-slate-500 dark:text-slate-400">
                            <li>📍 Ibarra, Ecuador</li>
                            <li>📱 +593 98 095 1601</li>
                            <li>✉️ contacto@scarlybu.com</li>
                        </ul>
                    </div>
                </div>
                <div class="mt-8 pt-6 border-t border-slate-200 dark:border-slate-800 text-center text-xs text-slate-400">
                    &copy; {{ date('Y') }} Scarlybu. Todos los derechos reservados.
                </div>
            </div>
        </footer>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
