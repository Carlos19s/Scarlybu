<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="admin-body">
        <!-- Top Navigation Bar -->
        <nav class="admin-nav sticky top-0 z-50" x-data="{ mobileOpen: false }">
            <div class="max-w-7xl mx-auto px-4 sm:px-6">
                <div class="flex items-center justify-between h-16">
                    <!-- Left: Logo + Main Nav -->
                    <div class="flex items-center gap-2">
                        <!-- Mobile toggle -->
                        <button @click="mobileOpen = !mobileOpen" class="mobile-nav-toggle">
                            <svg x-show="!mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                            <svg x-show="mobileOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>

                        <!-- Brand -->
                        <a href="{{ route('store.home') }}" wire:navigate class="flex items-center gap-2.5 mr-4">
                            <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-gradient-to-br from-emerald-500 to-emerald-600 shadow-lg shadow-emerald-500/20">
                                <svg class="w-4.5 h-4.5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                            </div>
                            <span class="text-white font-bold text-lg tracking-tight hidden sm:block">Scarlybu</span>
                        </a>

                        <!-- Desktop Nav Links -->
                        <div class="nav-links items-center gap-1 hidden md:flex">
                            <a href="{{ route('dashboard') }}" wire:navigate
                               class="admin-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                Dashboard
                            </a>
                            <a href="{{ route('store.home') }}" wire:navigate
                               class="admin-nav-item">
                                Tienda
                            </a>
                            @can('manage_catalog')
                                <a href="{{ route('admin.products.index') }}" wire:navigate
                                   class="admin-nav-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                                    Productos
                                </a>
                                <a href="{{ route('admin.categories.index') }}" wire:navigate
                                   class="admin-nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                                    Categorías
                                </a>
                            @endcan
                            @can('manage_orders')
                                <a href="{{ route('admin.orders.index') }}" wire:navigate
                                   class="admin-nav-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                                    Pedidos
                                </a>
                            @endcan
                            @can('manage_users')
                                <a href="{{ route('admin.users.index') }}" wire:navigate
                                   class="admin-nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                                    Usuarios
                                </a>
                            @endcan
                        </div>
                    </div>

                    <!-- Right: User Menu -->
                    <div class="flex items-center gap-3">
                        <flux:dropdown position="bottom" align="end">
                            <flux:profile :initials="auth()->user()->initials()" icon-trailing="chevron-down" class="cursor-pointer" />
                            <flux:menu>
                                <div class="px-3 py-2 border-b border-zinc-200 dark:border-zinc-700">
                                    <p class="text-sm font-semibold">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-zinc-500">{{ auth()->user()->email }}</p>
                                    <span class="badge-emerald mt-1 text-[0.65rem]">{{ ucfirst(str_replace('_', ' ', auth()->user()->roles->first()?->name ?? 'Usuario')) }}</span>
                                </div>
                                <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                                <flux:menu.separator />
                                <form method="POST" action="{{ route('logout') }}" class="w-full">
                                    @csrf
                                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full cursor-pointer" data-test="logout-button">
                                        {{ __('Log out') }}
                                    </flux:menu.item>
                                </form>
                            </flux:menu>
                        </flux:dropdown>
                    </div>
                </div>

                <!-- Mobile Nav Links -->
                <div x-show="mobileOpen" x-cloak
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-2"
                     class="md:hidden pb-3 space-y-1">
                    <a href="{{ route('dashboard') }}" wire:navigate
                       class="admin-nav-item block {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('store.home') }}" wire:navigate class="admin-nav-item block">
                        Tienda
                    </a>
                    @can('manage_catalog')
                        <a href="{{ route('admin.products.index') }}" wire:navigate
                           class="admin-nav-item block {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                            Productos
                        </a>
                        <a href="{{ route('admin.categories.index') }}" wire:navigate
                           class="admin-nav-item block {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                            Categorías
                        </a>
                    @endcan
                    @can('manage_orders')
                        <a href="{{ route('admin.orders.index') }}" wire:navigate
                           class="admin-nav-item block {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                            Pedidos
                        </a>
                    @endcan
                    @can('manage_users')
                        <a href="{{ route('admin.users.index') }}" wire:navigate
                           class="admin-nav-item block {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            Usuarios
                        </a>
                    @endcan
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="admin-container">
            <flux:main>
                {{ $slot }}
            </flux:main>
        </div>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
