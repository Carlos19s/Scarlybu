<?php

use Livewire\Volt\Component;
use App\Models\AuditLog;
use App\Models\User;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

new #[Layout('layouts.app')] #[Title('Bitácora del Sistema')] class extends Component {
    use WithPagination;

    public string $search = '';
    public string $userId = '';
    public string $eventType = '';
    public string $startDate = '';
    public string $endDate = '';

    public ?AuditLog $selectedLog = null;
    public bool $showModal = false;

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingUserId(): void { $this->resetPage(); }
    public function updatingEventType(): void { $this->resetPage(); }
    public function updatingStartDate(): void { $this->resetPage(); }
    public function updatingEndDate(): void { $this->resetPage(); }

    public function clearFilters(): void
    {
        $this->reset(['search', 'userId', 'eventType', 'startDate', 'endDate']);
        $this->resetPage();
    }

    protected function getFilteredLogsQuery()
    {
        return AuditLog::with('user')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('description', 'like', "%{$this->search}%")
                      ->orWhere('ip_address', 'like', "%{$this->search}%")
                      ->orWhere('model_type', 'like', "%{$this->search}%");
                });
            })
            ->when($this->userId, function ($query) {
                $query->where('user_id', $this->userId);
            })
            ->when($this->eventType, function ($query) {
                $query->where('event', $this->eventType);
            })
            ->when($this->startDate, function ($query) {
                $query->whereDate('created_at', '>=', $this->startDate);
            })
            ->when($this->endDate, function ($query) {
                $query->whereDate('created_at', '<=', $this->endDate);
            });
    }

    public function with(): array
    {
        return [
            'logs' => $this->getFilteredLogsQuery()->latest()->paginate(15),
            'users' => User::where('role', '!=', 'cliente')->orderBy('name')->get(),
        ];
    }

    public function viewDetails(AuditLog $log): void
    {
        $this->selectedLog = $log;
        $this->showModal = true;
    }

    public function export()
    {
        $logs = $this->getFilteredLogsQuery()->latest()->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="bitacora-auditoria-' . now()->format('Y-m-d') . '.csv"',
        ];

        return response()->streamDownload(function () use ($logs) {
            $handle = fopen('php://output', 'w');
            // Write UTF-8 BOM for proper Spanish characters display in Excel
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, ['ID', 'Fecha/Hora', 'Usuario', 'Email', 'Rol', 'Evento', 'Descripción', 'IP', 'Navegador']);

            foreach ($logs as $log) {
                fputcsv($handle, [
                    $log->id,
                    $log->created_at->format('d/m/Y H:i:s'),
                    $log->user?->name ?? 'Sistema/Invitado',
                    $log->user?->email ?? '-',
                    $log->user?->role ?? '-',
                    strtoupper($log->event),
                    $log->description,
                    $log->ip_address,
                    $log->user_agent,
                ]);
            }
            fclose($handle);
        }, 'bitacora-auditoria-' . now()->format('Y-m-d') . '.csv', $headers);
    }
}; ?>

<div class="space-y-6">
    <!-- Page Header -->
    <div class="admin-page-header">
        <div>
            <h1 class="admin-page-title">Bitácora del Sistema</h1>
            <p class="admin-page-subtitle">Monitorea los accesos de usuarios y modificaciones de registros en tiempo real.</p>
        </div>
        <div>
            <button wire:click="export"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg shadow-sm transition-all text-sm cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Exportar CSV
            </button>
        </div>
    </div>

    <!-- Filters Toolbar -->
    <div class="admin-toolbar flex flex-col gap-4 p-5 bg-white dark:bg-slate-900/60 rounded-xl border border-slate-200 dark:border-slate-800">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 w-full">
            <!-- Search field -->
            <div class="relative">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar descripción, IP..."
                       class="w-full pl-9 pr-4 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm transition-colors">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>

            <!-- User Select -->
            <select wire:model.live="userId"
                    class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 text-slate-900 dark:text-slate-100 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                <option value="">Todos los usuarios...</option>
                @foreach($users as $u)
                    <option value="{{ $u->id }}">{{ $u->name }} ({{ ucfirst(str_replace('_', ' ', $u->role)) }})</option>
                @endforeach
            </select>

            <!-- Event Type Select -->
            <select wire:model.live="eventType"
                    class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 text-slate-900 dark:text-slate-100 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                <option value="">Todos los eventos...</option>
                <option value="login">Inicio de Sesión</option>
                <option value="logout">Cierre de Sesión</option>
                <option value="login_failed">Intento Fallido</option>
                <option value="created">Creación (Insert)</option>
                <option value="updated">Modificación (Update)</option>
                <option value="deleted">Eliminación (Delete)</option>
            </select>

            <!-- Start Date -->
            <input type="date" wire:model.live="startDate"
                   class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 text-slate-900 dark:text-slate-100 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">

            <!-- End Date -->
            <input type="date" wire:model.live="endDate"
                   class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 text-slate-900 dark:text-slate-100 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
        </div>

        @if($search || $userId || $eventType || $startDate || $endDate)
            <div class="flex justify-end w-full">
                <button wire:click="clearFilters"
                        class="text-xs font-semibold text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 flex items-center gap-1.5 cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    Limpiar Filtros
                </button>
            </div>
        @endif
    </div>

    <!-- Logs Table -->
    <div class="admin-table-wrapper animate-fade-in-up">
        <table class="admin-table">
            <thead>
                <tr>
                    <th class="w-32">Fecha/Hora</th>
                    <th class="w-48">Usuario</th>
                    <th class="w-28">Evento</th>
                    <th>Descripción</th>
                    <th class="w-32">IP</th>
                    <th class="text-right w-24">Detalles</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20">
                        <td class="text-slate-500 dark:text-slate-400 text-xs">
                            {{ $log->created_at->format('d/m/Y') }}
                            <div class="text-[10px] text-slate-400">{{ $log->created_at->format('H:i:s') }}</div>
                        </td>
                        <td>
                            @if($log->user)
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-lg bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center text-xs font-bold text-slate-700 dark:text-slate-300">
                                        {{ $log->user->initials() }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-slate-900 dark:text-slate-200 text-xs">{{ $log->user->name }}</div>
                                        <div class="text-[10px] text-slate-400">{{ ucfirst(str_replace('_', ' ', $log->user->role)) }}</div>
                                    </div>
                                </div>
                            @else
                                <span class="text-slate-400 italic text-xs">Sistema / Invitado</span>
                            @endif
                        </td>
                        <td>
                            @if($log->event === 'login')
                                <span class="badge-blue text-[10px]">LOGIN</span>
                            @elseif($log->event === 'logout')
                                <span class="badge-zinc text-[10px]">LOGOUT</span>
                            @elseif($log->event === 'login_failed')
                                <span class="badge-red text-[10px]">FALLIDO</span>
                            @elseif($log->event === 'created')
                                <span class="badge-emerald text-[10px]">CREADO</span>
                            @elseif($log->event === 'updated')
                                <span class="badge-orange text-[10px]">MODIFICADO</span>
                            @elseif($log->event === 'deleted')
                                <span class="badge-red text-[10px]">ELIMINADO</span>
                            @else
                                <span class="badge-zinc text-[10px]">{{ strtoupper($log->event) }}</span>
                            @endif
                        </td>
                        <td class="text-slate-700 dark:text-slate-300 text-xs font-mono max-w-md truncate">
                            {{ $log->description }}
                        </td>
                        <td class="text-slate-500 dark:text-slate-400 text-xs font-mono">
                            {{ $log->ip_address ?? '-' }}
                        </td>
                        <td class="text-right">
                            @if($log->old_values || $log->new_values)
                                <button wire:click="viewDetails({{ $log->id }})"
                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-[11px] font-semibold text-slate-700 dark:text-slate-300 transition-colors cursor-pointer">
                                    Ver
                                </button>
                            @else
                                <span class="text-slate-400 text-xs">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-12">
                            <div class="flex flex-col items-center gap-2 text-slate-400">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <p class="font-medium text-sm">No se encontraron registros de auditoría</p>
                                <p class="text-xs">Prueba ajustando los filtros de búsqueda</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $logs->links() }}
    </div>

    <!-- Details Modal -->
    <flux:modal wire:model="showModal" class="w-full max-w-3xl">
        <div class="space-y-6">
            @if($selectedLog)
                <div>
                    <flux:heading size="lg">Detalles del Cambio de Registro</flux:heading>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                        {{ $selectedLog->description }} · {{ $selectedLog->created_at->format('d/m/Y H:i:s') }}
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Old Values -->
                    <div class="space-y-2">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">Valores Anteriores</span>
                        <div class="p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg overflow-x-auto max-h-96">
                            @if($selectedLog->old_values)
                                <pre class="text-xs font-mono text-slate-800 dark:text-slate-200">{{ json_encode($selectedLog->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                            @else
                                <span class="text-xs text-slate-400 italic">Ninguno</span>
                            @endif
                        </div>
                    </div>

                    <!-- New Values -->
                    <div class="space-y-2">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">Valores Nuevos</span>
                        <div class="p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg overflow-x-auto max-h-96">
                            @if($selectedLog->new_values)
                                <pre class="text-xs font-mono text-slate-800 dark:text-slate-200">{{ json_encode($selectedLog->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                            @else
                                <span class="text-xs text-slate-400 italic">Ninguno</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Extra Meta -->
                <div class="p-3.5 bg-slate-50 dark:bg-slate-900/60 border border-slate-200/60 dark:border-slate-800/60 rounded-lg grid grid-cols-3 gap-2 text-xs">
                    <div>
                        <span class="text-slate-400 block">Modelo Afectado</span>
                        <span class="font-medium text-slate-700 dark:text-slate-300 font-mono select-all">{{ $selectedLog->model_type }} (ID: {{ $selectedLog->model_id }})</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block">Dirección IP</span>
                        <span class="font-medium text-slate-700 dark:text-slate-300 font-mono">{{ $selectedLog->ip_address ?? 'No registrada' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block">Navegador</span>
                        <span class="font-medium text-slate-700 dark:text-slate-300 truncate block" title="{{ $selectedLog->user_agent }}">{{ $selectedLog->user_agent ?? 'No registrado' }}</span>
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <flux:button wire:click="$set('showModal', false)" variant="ghost">Cerrar</flux:button>
                </div>
            @endif
        </div>
    </flux:modal>
</div>
