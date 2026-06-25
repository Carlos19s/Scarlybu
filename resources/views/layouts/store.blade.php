<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
        <style>
            :root {
                --bg: #121318;
                --panel: #1f2128;
                --panel-soft: #292b33;
                --text: #f2f2f7;
                --muted: #b8bac1;
                --accent: #55d6ff;
                --accent-02: #ff6b81;
                --border: rgba(255, 255, 255, 0.08);
                --shadow: 0 24px 80px rgba(0, 0, 0, 0.24);
            }
            body {
                background: radial-gradient(circle at top, rgba(85, 214, 255, 0.12), transparent 28%),
                            linear-gradient(180deg, #16171d 0%, #121318 100%) !important;
                color: var(--text) !important;
                font-family: 'Inter', system-ui, sans-serif !important;
            }
            .store-header {
                background: rgba(22, 23, 29, 0.8) !important;
                border-bottom: 1px solid var(--border) !important;
                backdrop-filter: blur(20px) !important;
            }
        </style>
    </head>
    <body class="min-h-screen antialiased text-[#f2f2f7] bg-[#121318]">
        {{-- Header / Navbar --}}
        <header class="sticky top-0 z-50 store-header" x-data="{ mobileOpen: false }">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    {{-- Logo --}}
                    <a href="{{ route('store.home') }}" class="flex items-center gap-3.5 group" wire:navigate>
                        <img src="{{ asset('img/Scarlybu.png') }}" alt="Logo Scarlybu" class="w-10 h-10 object-contain rounded-xl bg-white/5 border border-white/10 p-1 group-hover:scale-105 transition-transform duration-200" />
                        <div class="flex flex-col">
                            <span class="text-sm font-bold tracking-widest text-[#55d6ff] uppercase leading-none">Scarlybu</span>
                            <span class="text-[10px] text-[#b8bac1] font-medium tracking-wide mt-0.5">Moda urbana premium</span>
                        </div>
                    </a>

                    {{-- Category Nav (Desktop) --}}
                    <nav class="hidden md:flex items-center gap-1">
                        @php
                            $navCategories = \App\Models\Category::whereNull('parent_id')->where('activa', true)->get();
                        @endphp
                        @foreach($navCategories as $cat)
                            <a href="{{ route('store.category', $cat->slug) }}"
                               class="px-3 py-2 rounded-lg text-sm font-medium text-[#b8bac1] hover:text-white transition-colors"
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
                            <a href="{{ route('dashboard') }}" class="text-sm font-medium text-[#b8bac1] hover:text-white transition-colors hidden sm:block" wire:navigate>
                                Mi Cuenta
                            </a>

                            {{-- Role-based Action Button --}}
                            @php $userRole = auth()->user()->roles->first()?->name ?? 'cliente'; @endphp
                            @if($userRole !== 'cliente')
                                <a href="{{ route('dashboard') }}"
                                   class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold transition-colors shadow-lg shadow-emerald-600/20 hover:shadow-emerald-600/30"
                                   wire:navigate>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    Funciones
                                </a>
                            @else
                                <a href="{{ route('store.orders') }}"
                                   class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold transition-colors shadow-lg shadow-emerald-600/20 hover:shadow-emerald-600/30"
                                   wire:navigate>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                    Mis notas de pedido
                                </a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-medium text-[#b8bac1] hover:text-white transition-colors" wire:navigate>
                                Iniciar Sesión
                            </a>
                        @endauth

                        {{-- Mobile Menu Toggle --}}
                        <button @click="mobileOpen = !mobileOpen"
                                class="md:hidden p-2 rounded-lg text-[#b8bac1] hover:text-white hover:bg-white/5">
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
                     class="md:hidden border-t border-white/5 pb-4">
                    <nav class="flex flex-col gap-1 pt-2">
                        @foreach($navCategories as $cat)
                            <a href="{{ route('store.category', $cat->slug) }}"
                               class="px-3 py-2 rounded-lg text-sm font-medium text-[#b8bac1] hover:text-white hover:bg-white/5 transition-colors"
                               wire:navigate>
                                {{ $cat->nombre }}
                            </a>
                        @endforeach
                        @auth
                            <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded-lg text-sm font-medium text-[#b8bac1] hover:text-white hover:bg-white/5 transition-colors sm:hidden" wire:navigate>
                                Mi Cuenta
                            </a>
                            @if(auth()->user()->roles->first()?->name === 'cliente')
                                <a href="{{ route('store.orders') }}" class="px-3 py-2 rounded-lg text-sm font-medium text-[#55d6ff] hover:bg-white/5 transition-colors" wire:navigate>
                                    Mis notas de pedido
                                </a>
                            @else
                                <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded-lg text-sm font-medium text-[#55d6ff] hover:bg-white/5 transition-colors" wire:navigate>
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

        <footer class="mt-20 border-t border-white/5 bg-[#121318] py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center">
                <p class="text-sm text-[#b8bac1]">&copy; {{ date('Y') }} Scarlybu.</p>
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
