<x-layouts::app :title="__('Dashboard')">
    @php
        $salesToday = 0;
        $salesWeek = 0;
        $salesMonth = 0;
        $totalOrders = 0;
        $totalProducts = 0;
        $lowStockCount = 0;
        $totalUsers = 0;

        if ($role !== 'cliente') {
            $totalOrders = \App\Models\Order::count();
            $totalProducts = \App\Models\Product::count();
            $lowStockCount = \App\Models\Product::whereColumn('stock', '<=', 'stock_minimo')->count();
            $salesMonth = \App\Models\Order::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();
            
            if ($role === 'gerente') {
                $salesToday = \App\Models\Order::whereDate('created_at', today())->count();
                $salesWeek = \App\Models\Order::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
            }
            
            if (auth()->user()?->can('manage_users')) {
                $totalUsers = \App\Models\User::count();
            }
        }
    @endphp

    <div class="space-y-8">
        <!-- Welcome Header -->
        <div class="animate-fade-in-up">
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-slate-100 tracking-tight">
                ¡Hola, {{ explode(' ', $userName)[0] }}! 👋
            </h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1">
                Panel de {{ $userName }} · {{ now()->translatedFormat('l, d \\d\\e F Y') }}
            </p>
        </div>

        <!-- Stats Grid (Solo Gerente) -->
        @if($role === 'gerente')
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
            <!-- Sales Today -->
            <div class="stat-card animate-fade-in-up stagger-1">
                <div class="stat-card-accent emerald"></div>
                <div class="stat-card-icon emerald">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="stat-card-value">{{ $salesToday }}</div>
                <div class="stat-card-label">Ventas Hoy</div>
            </div>

            <!-- Sales This Week -->
            <div class="stat-card animate-fade-in-up stagger-2">
                <div class="stat-card-accent blue"></div>
                <div class="stat-card-icon blue">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <div class="stat-card-value">{{ $salesWeek }}</div>
                <div class="stat-card-label">Ventas Semana</div>
            </div>

            <!-- Sales This Month -->
            <div class="stat-card animate-fade-in-up stagger-3">
                <div class="stat-card-accent orange"></div>
                <div class="stat-card-icon orange">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </div>
                <div class="stat-card-value">{{ $salesMonth }}</div>
                <div class="stat-card-label">Ventas Mes</div>
            </div>

            <!-- Total Orders -->
            <div class="stat-card animate-fade-in-up stagger-4">
                <div class="stat-card-accent blue"></div>
                <div class="stat-card-icon blue">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                </div>
                <div class="stat-card-value">{{ $totalOrders }}</div>
                <div class="stat-card-label">Notas de Pedido</div>
            </div>
        </div>
        @endif

        @if($role === 'cliente')
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm animate-fade-in-up stagger-2">
            <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Mi Cuenta Scarlybu</h2>
            <p class="text-slate-500 dark:text-slate-400 mb-6">Desde aquí puedes gestionar tu perfil, ver el historial de tus pedidos y continuar explorando el catálogo de moda.</p>
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('store.orders') }}" wire:navigate class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl shadow-lg shadow-emerald-600/20 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    Mis notas de pedido
                </a>
                <a href="{{ route('store.home') }}" wire:navigate class="inline-flex items-center gap-2 px-6 py-3 border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 font-semibold rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                    Ir a la Tienda
                </a>
            </div>
        </div>
        @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Quick Actions Panel -->
            <div class="lg:col-span-2 animate-fade-in-up stagger-3">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">Acceso Rápido</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @can('manage_catalog')
                    <a href="{{ route('admin.products.index') }}" wire:navigate class="quick-link">
                        <div class="quick-link-icon">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </div>
                        <div>
                            <div class="font-medium">Productos</div>
                            <div class="text-xs text-slate-500 dark:text-slate-400">{{ $totalProducts }} registrados</div>
                        </div>
                    </a>
                    <a href="{{ route('admin.categories.index') }}" wire:navigate class="quick-link">
                        <div class="quick-link-icon">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                        </div>
                        <div>
                            <div class="font-medium">Categorías</div>
                            <div class="text-xs text-slate-500 dark:text-slate-400">Administrar</div>
                        </div>
                    </a>
                    @endcan
                    @can('manage_orders')
                    <a href="{{ route('admin.orders.index') }}" wire:navigate class="quick-link">
                        <div class="quick-link-icon" style="background: rgba(59,130,246,0.1); color: #3b82f6;">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                        </div>
                        <div>
                            <div class="font-medium">Pedidos</div>
                            <div class="text-xs text-slate-500 dark:text-slate-400">{{ $totalOrders }} notas de pedido</div>
                        </div>
                    </a>
                    @endcan
                    @can('manage_users')
                    <a href="{{ route('admin.users.index') }}" wire:navigate class="quick-link">
                        <div class="quick-link-icon" style="background: rgba(249,115,22,0.1); color: #f97316;">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        </div>
                        <div>
                            <div class="font-medium">Usuarios</div>
                            <div class="text-xs text-slate-500 dark:text-slate-400">{{ $totalUsers }} registrados</div>
                        </div>
                    </a>
                    @endcan
                    <a href="{{ route('store.home') }}" wire:navigate class="quick-link">
                        <div class="quick-link-icon" style="background: rgba(168,85,247,0.1); color: #a855f7;">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                        </div>
                        <div>
                            <div class="font-medium">Ver Tienda</div>
                            <div class="text-xs text-slate-500 dark:text-slate-400">Ir al catálogo</div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Info Sidebar -->
            <div class="animate-fade-in-up stagger-4">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">Resumen</h2>
                <div class="space-y-3">
                    @if($lowStockCount > 0)
                    <div class="flex items-center gap-3 p-3 rounded-xl bg-red-50 dark:bg-red-900/10 border border-red-200 dark:border-red-800/30">
                        <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 flex-shrink-0">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-red-800 dark:text-red-300">{{ $lowStockCount }} producto(s) con stock bajo</p>
                            <p class="text-xs text-red-600 dark:text-red-400">Revisar inventario</p>
                        </div>
                    </div>
                    @endif

                    <div class="flex items-center gap-3 p-3 rounded-xl bg-emerald-50 dark:bg-emerald-900/10 border border-emerald-200 dark:border-emerald-800/30">
                        <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 flex-shrink-0">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-emerald-800 dark:text-emerald-300">{{ $salesMonth }} ventas registradas este mes</p>
                            <p class="text-xs text-emerald-600 dark:text-emerald-400">Notas de pedido realizadas</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 p-3 rounded-xl bg-blue-50 dark:bg-blue-900/10 border border-blue-200 dark:border-blue-800/30">
                        <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex-shrink-0">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-blue-800 dark:text-blue-300">{{ $totalProducts }} productos en catálogo</p>
                            <p class="text-xs text-blue-600 dark:text-blue-400">{{ $totalOrders }} notas de pedido totales</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</x-layouts::app>
