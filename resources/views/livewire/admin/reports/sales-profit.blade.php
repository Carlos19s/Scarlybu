<?php

use Livewire\Volt\Component;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

new #[Layout('layouts.app')] #[Title('Reporte de Ventas y Ganancias')] class extends Component {
    public string $periodType = 'daily'; // 'daily', 'weekly', 'monthly'
    public string $selectedDate = ''; // 'YYYY-MM-DD'
    public string $selectedMonth = ''; // 'YYYY-MM'

    public function mount(): void
    {
        $this->selectedDate = now()->toDateString();
        $this->selectedMonth = now()->format('Y-m');
    }

    public function updatedPeriodType(): void
    {
        $this->resetPage();
    }

    public function updatedSelectedDate(): void
    {
        $this->resetPage();
    }

    public function updatedSelectedMonth(): void
    {
        $this->resetPage();
    }

    private function resetPage(): void
    {
        // Placeholder method to handle pagination resets if we used pagination
    }

    private function getPeriodDates(): array
    {
        $start = '';
        $end = '';

        if ($this->periodType === 'daily') {
            $date = Carbon::parse($this->selectedDate ?: now()->toDateString());
            $start = $date->startOfDay()->toDateTimeString();
            $end = $date->endOfDay()->toDateTimeString();
        } elseif ($this->periodType === 'weekly') {
            $date = Carbon::parse($this->selectedDate ?: now()->toDateString());
            $start = $date->startOfWeek()->startOfDay()->toDateTimeString();
            $end = $date->endOfWeek()->endOfDay()->toDateTimeString();
        } else {
            // monthly
            $date = Carbon::parse(($this->selectedMonth ?: now()->format('Y-m')) . '-01');
            $start = $date->startOfMonth()->startOfDay()->toDateTimeString();
            $end = $date->endOfMonth()->endOfDay()->toDateTimeString();
        }

        return [$start, $end];
    }

    public function exportCsv()
    {
        [$start, $end] = $this->getPeriodDates();
        $data = $this->calculateReportData($start, $end);

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="reporte-ventas-ganancias-' . $this->periodType . '-' . now()->format('Y-m-d') . '.csv"',
        ];

        return response()->streamDownload(function () use ($data) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM

            // Title and Period
            fputcsv($handle, ['REPORTE DE VENTAS Y GANANCIAS (' . strtoupper($this->periodType) . ')']);
            fputcsv($handle, ['Periodo:', $data['start_date'] . ' al ' . $data['end_date']]);
            fputcsv($handle, []);

            // Summary Metrics
            fputcsv($handle, ['RESUMEN GENERAL']);
            fputcsv($handle, ['Metrica', 'Valor']);
            fputcsv($handle, ['Pedidos Realizados', $data['metrics']['total_orders']]);
            fputcsv($handle, ['Productos Vendidos', $data['metrics']['total_items_sold']]);
            fputcsv($handle, ['Ingresos Brutos (Con IVA)', '$' . number_format($data['metrics']['gross_sales'], 2)]);
            fputcsv($handle, ['Ingresos Netos (Sin IVA)', '$' . number_format($data['metrics']['net_sales'], 2)]);
            fputcsv($handle, ['Costo Total de Adquisicion', '$' . number_format($data['metrics']['total_cost'], 2)]);
            fputcsv($handle, ['Ganancia Real Total', '$' . number_format($data['metrics']['total_profit'], 2)]);
            fputcsv($handle, ['Margen de Ganancia Real', number_format($data['metrics']['profit_margin'], 2) . '%']);
            fputcsv($handle, []);

            // Product Breakdown
            fputcsv($handle, ['DESGLOSE POR PRODUCTO']);
            fputcsv($handle, ['Producto', 'Cantidad Vendida', 'Ingreso Bruto', 'Ingreso Neto', 'Costo Total', 'Ganancia Neta', 'Margen %']);
            foreach ($data['products'] as $prod) {
                fputcsv($handle, [
                    $prod['name'],
                    $prod['quantity'],
                    '$' . number_format($prod['gross_revenue'], 2),
                    '$' . number_format($prod['net_revenue'], 2),
                    '$' . number_format($prod['total_cost'], 2),
                    '$' . number_format($prod['profit'], 2),
                    number_format($prod['margin'], 2) . '%'
                ]);
            }
            fputcsv($handle, []);

            // Orders list
            fputcsv($handle, ['LISTADO DE PEDIDOS']);
            fputcsv($handle, ['Pedido', 'Fecha', 'Cliente', 'Estado', 'Ingreso Bruto', 'Ingreso Neto', 'Costo Total', 'Ganancia Neta', 'Margen %']);
            foreach ($data['orders'] as $order) {
                fputcsv($handle, [
                    $order['number'],
                    $order['date'],
                    $order['customer'],
                    strtoupper($order['status']),
                    '$' . number_format($order['gross'], 2),
                    '$' . number_format($order['net'], 2),
                    '$' . number_format($order['cost'], 2),
                    '$' . number_format($order['profit'], 2),
                    number_format($order['margin'], 2) . '%'
                ]);
            }

            fclose($handle);
        }, 'reporte-ventas-ganancias-' . $this->periodType . '-' . now()->format('Y-m-d') . '.csv', $headers);
    }

    private function calculateReportData(string $start, string $end): array
    {
        $orders = Order::with(['items' => function($q) {
            $q->with(['product' => function($pq) {
                $pq->withTrashed();
            }]);
        }])
        ->whereNotIn('estado', ['cancelado', 'no_revisado'])
        ->whereBetween('created_at', [$start, $end])
        ->get();

        $metrics = [
            'total_orders' => $orders->count(),
            'total_items_sold' => 0,
            'gross_sales' => 0.0,
            'net_sales' => 0.0,
            'total_cost' => 0.0,
            'total_profit' => 0.0,
            'profit_margin' => 0.0,
        ];

        $productsData = [];
        $ordersData = [];

        foreach ($orders as $order) {
            $orderGross = 0.0;
            $orderNet = 0.0;
            $orderCost = 0.0;

            foreach ($order->items as $item) {
                $qty = $item->cantidad;
                $priceWithIva = (float) $item->precio_unitario;
                $itemSubtotalWithIva = (float) $item->subtotal;
                $ivaPercent = (float) $item->iva_porcentaje;

                $itemSubtotalWithoutIva = $itemSubtotalWithIva / (1 + ($ivaPercent / 100));
                
                $product = $item->product;
                $costPrice = $product ? (float) $product->precio_compra : 0.0;
                $itemCost = $costPrice * $qty;

                $orderGross += $itemSubtotalWithIva;
                $orderNet += $itemSubtotalWithoutIva;
                $orderCost += $itemCost;

                // Aggregate by product
                $productId = $item->product_id;
                $productName = $product ? $product->nombre : 'Producto Eliminado (ID: ' . $productId . ')';
                
                if (!isset($productsData[$productId])) {
                    $productsData[$productId] = [
                        'name' => $productName,
                        'quantity' => 0,
                        'gross_revenue' => 0.0,
                        'net_revenue' => 0.0,
                        'total_cost' => 0.0,
                        'profit' => 0.0,
                        'margin' => 0.0,
                    ];
                }

                $productsData[$productId]['quantity'] += $qty;
                $productsData[$productId]['gross_revenue'] += $itemSubtotalWithIva;
                $productsData[$productId]['net_revenue'] += $itemSubtotalWithoutIva;
                $productsData[$productId]['total_cost'] += $itemCost;
                
                $metrics['total_items_sold'] += $qty;
            }

            $orderProfit = $orderNet - $orderCost;
            $orderMargin = $orderNet > 0 ? ($orderProfit / $orderNet) * 100 : 0.0;

            $ordersData[] = [
                'id' => $order->id,
                'number' => $order->numero_pedido,
                'date' => $order->created_at->format('d/m/Y H:i'),
                'customer' => $order->cliente_nombre,
                'status' => $order->estado,
                'gross' => $orderGross,
                'net' => $orderNet,
                'cost' => $orderCost,
                'profit' => $orderProfit,
                'margin' => $orderMargin,
            ];

            $metrics['gross_sales'] += $orderGross;
            $metrics['net_sales'] += $orderNet;
            $metrics['total_cost'] += $orderCost;
        }

        // Finish product metrics calculation
        foreach ($productsData as $id => $p) {
            $profit = $p['net_revenue'] - $p['total_cost'];
            $productsData[$id]['profit'] = $profit;
            $productsData[$id]['margin'] = $p['net_revenue'] > 0 ? ($profit / $p['net_revenue']) * 100 : 0.0;
        }

        // Sort products by net revenue desc
        usort($productsData, function($a, $b) {
            return $b['net_revenue'] <=> $a['net_revenue'];
        });

        $metrics['total_profit'] = $metrics['net_sales'] - $metrics['total_cost'];
        $metrics['profit_margin'] = $metrics['net_sales'] > 0 ? ($metrics['total_profit'] / $metrics['net_sales']) * 100 : 0.0;

        return [
            'start_date' => Carbon::parse($start)->format('d/m/Y'),
            'end_date' => Carbon::parse($end)->format('d/m/Y'),
            'metrics' => $metrics,
            'products' => $productsData,
            'orders' => $ordersData,
        ];
    }

    public function with(): array
    {
        [$start, $end] = $this->getPeriodDates();
        $reportData = $this->calculateReportData($start, $end);

        return [
            'report' => $reportData,
        ];
    }
}; ?>

<div class="space-y-8 animate-fade-in">
    <!-- Page Header -->
    <div class="admin-page-header">
        <div>
            <h1 class="admin-page-title">Reporte de Ventas y Ganancias</h1>
            <p class="admin-page-subtitle">Analiza el rendimiento comercial del minimarket, incluyendo ingresos brutos, netos, costos y ganancias reales.</p>
        </div>
        <div>
            <button wire:click="exportCsv"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg shadow-sm transition-all text-sm cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Exportar CSV
            </button>
        </div>
    </div>

    <!-- Period Filters -->
    <div class="admin-toolbar flex flex-col md:flex-row gap-4 p-5 bg-white dark:bg-slate-900/60 rounded-xl border border-slate-200 dark:border-slate-800">
        <div class="flex items-center gap-2">
            <span class="text-sm font-semibold text-slate-500 dark:text-slate-400">Tipo de Reporte:</span>
            <div class="inline-flex rounded-lg border border-slate-200 dark:border-slate-800 p-1 bg-slate-50 dark:bg-slate-900">
                <button wire:click="$set('periodType', 'daily')"
                        class="px-3.5 py-1.5 rounded-md text-xs font-bold transition-all cursor-pointer {{ $periodType === 'daily' ? 'bg-emerald-600 text-white shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900' }}">
                    Diario
                </button>
                <button wire:click="$set('periodType', 'weekly')"
                        class="px-3.5 py-1.5 rounded-md text-xs font-bold transition-all cursor-pointer {{ $periodType === 'weekly' ? 'bg-emerald-600 text-white shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900' }}">
                    Semanal
                </button>
                <button wire:click="$set('periodType', 'monthly')"
                        class="px-3.5 py-1.5 rounded-md text-xs font-bold transition-all cursor-pointer {{ $periodType === 'monthly' ? 'bg-emerald-600 text-white shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900' }}">
                    Mensual
                </button>
            </div>
        </div>

        <div class="flex-1 flex items-center gap-3">
            @if($periodType === 'daily' || $periodType === 'weekly')
                <div class="relative w-full max-w-xs">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400 uppercase">
                        {{ $periodType === 'daily' ? 'Día' : 'Semana de' }}
                    </span>
                    <input type="date" wire:model.live="selectedDate"
                           class="w-full pl-20 pr-4 py-2.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 text-slate-900 dark:text-slate-100 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
            @else
                <div class="relative w-full max-w-xs">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400 uppercase">
                        Mes
                    </span>
                    <input type="month" wire:model.live="selectedMonth"
                           class="w-full pl-16 pr-4 py-2.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 text-slate-900 dark:text-slate-100 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
            @endif

            <div class="text-xs text-slate-400 font-medium">
                Periodo calculado: <span class="text-slate-700 dark:text-slate-300 font-bold bg-slate-100 dark:bg-slate-800 px-2.5 py-1 rounded-md">{{ $report['start_date'] }} al {{ $report['end_date'] }}</span>
            </div>
        </div>
    </div>

    <!-- Stats Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Ingresos Brutos (Con IVA) -->
        <div class="stat-card relative overflow-hidden bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-sm">
            <div class="stat-card-accent blue"></div>
            <div class="stat-card-icon blue">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="stat-card-value font-mono">${{ number_format($report['metrics']['gross_sales'], 2) }}</div>
            <div class="stat-card-label">Ingresos Brutos (Con IVA)</div>
        </div>

        <!-- Ingresos Netos (Sin IVA) -->
        <div class="stat-card relative overflow-hidden bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-sm">
            <div class="stat-card-accent orange"></div>
            <div class="stat-card-icon orange">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <div class="stat-card-value font-mono">${{ number_format($report['metrics']['net_sales'], 2) }}</div>
            <div class="stat-card-label">Ingresos Netos (Sin IVA)</div>
        </div>

        <!-- Costo de Adquisición -->
        <div class="stat-card relative overflow-hidden bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-sm">
            <div class="stat-card-accent rose"></div>
            <div class="stat-card-icon rose">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
                </svg>
            </div>
            <div class="stat-card-value font-mono">${{ number_format($report['metrics']['total_cost'], 2) }}</div>
            <div class="stat-card-label">Costo de Adquisición</div>
        </div>

        <!-- Ganancia Real Total -->
        <div class="stat-card relative overflow-hidden bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-sm">
            <div class="stat-card-accent emerald"></div>
            <div class="stat-card-icon emerald">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
            </div>
            <div class="stat-card-value font-mono">${{ number_format($report['metrics']['total_profit'], 2) }}</div>
            <div class="stat-card-label">Ganancia Real Total</div>
        </div>
    </div>

    <!-- Secondary Metrics Row -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-4 bg-slate-50 dark:bg-slate-900/40 rounded-xl border border-slate-200/50 dark:border-slate-800/50 text-center">
        <div>
            <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider block">Pedidos Realizados</span>
            <span class="text-lg font-bold text-slate-800 dark:text-slate-200">{{ $report['metrics']['total_orders'] }}</span>
        </div>
        <div class="border-t md:border-t-0 md:border-x border-slate-200 dark:border-slate-800 py-3 md:py-0">
            <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider block">Productos Vendidos</span>
            <span class="text-lg font-bold text-slate-800 dark:text-slate-200">{{ $report['metrics']['total_items_sold'] }}</span>
        </div>
        <div>
            <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider block">Margen de Ganancia Real</span>
            <span class="text-lg font-bold text-emerald-600 dark:text-emerald-400">{{ number_format($report['metrics']['profit_margin'], 2) }}%</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Products Table Breakdown -->
        <div class="lg:col-span-7 space-y-3">
            <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100 flex items-center gap-2">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                Desglose por Producto
            </h2>
            <div class="admin-table-wrapper max-h-[500px] overflow-y-auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th class="text-center w-20">Cant.</th>
                            <th class="text-right">Bruto</th>
                            <th class="text-right">Neto</th>
                            <th class="text-right">Costo</th>
                            <th class="text-right">Ganancia</th>
                            <th class="text-right">Margen</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($report['products'] as $prod)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 text-xs">
                                <td class="font-medium text-slate-800 dark:text-slate-200 max-w-[150px] truncate" title="{{ $prod['name'] }}">
                                    {{ $prod['name'] }}
                                </td>
                                <td class="text-center font-semibold text-slate-600 dark:text-slate-400">{{ $prod['quantity'] }}</td>
                                <td class="text-right font-mono text-slate-500">${{ number_format($prod['gross_revenue'], 2) }}</td>
                                <td class="text-right font-mono text-slate-500">${{ number_format($prod['net_revenue'], 2) }}</td>
                                <td class="text-right font-mono text-slate-500">${{ number_format($prod['total_cost'], 2) }}</td>
                                <td class="text-right font-mono font-semibold text-emerald-600 dark:text-emerald-400">${{ number_format($prod['profit'], 2) }}</td>
                                <td class="text-right">
                                    <span class="badge-emerald text-[10px]">{{ number_format($prod['margin'], 1) }}%</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-8 text-slate-400 text-sm">No hay productos vendidos en este periodo.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Orders Table list -->
        <div class="lg:col-span-5 space-y-3">
            <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100 flex items-center gap-2">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Pedidos en el Periodo
            </h2>
            <div class="admin-table-wrapper max-h-[500px] overflow-y-auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Nº Pedido</th>
                            <th>Cliente</th>
                            <th class="text-right">Bruto (Con IVA)</th>
                            <th class="text-right">Ganancia Real</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($report['orders'] as $order)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 text-xs">
                                <td>
                                    <div class="font-bold text-slate-800 dark:text-slate-200">{{ $order['number'] }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $order['date'] }}</div>
                                </td>
                                <td class="truncate max-w-[100px]">
                                    <div class="font-medium text-slate-700 dark:text-slate-300">{{ $order['customer'] }}</div>
                                    <div class="text-[9px]">
                                        @if($order['status'] === 'revisado')
                                            <span class="text-emerald-500 font-semibold uppercase">Revisado</span>
                                        @elseif($order['status'] === 'pendiente')
                                            <span class="text-orange-500 font-semibold uppercase">Pendiente</span>
                                        @else
                                            <span class="text-slate-400 uppercase">{{ $order['status'] }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-right font-mono font-semibold text-slate-800 dark:text-slate-200">${{ number_format($order['gross'], 2) }}</td>
                                <td class="text-right font-mono font-bold text-emerald-600 dark:text-emerald-400">${{ number_format($order['profit'], 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-8 text-slate-400 text-sm">No hay pedidos registrados en este periodo.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
