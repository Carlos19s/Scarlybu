<?php

use Livewire\Volt\Component;
use App\Models\AuditReport;
use App\Models\AuditLog;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

new #[Layout('layouts.app')] #[Title('Reportes de Auditoría')] class extends Component {
    use WithPagination;

    // Report generation form (Auditor)
    public string $title = '';
    public string $startDate = '';
    public string $endDate = '';
    public string $description = '';

    // Review form (Gerente)
    public string $comments = '';

    public bool $showCreateModal = false;
    public bool $showViewModal = false;
    public ?AuditReport $selectedReport = null;

    // Report details statistics
    public int $statLogins = 0;
    public int $statFailed = 0;
    public int $statCreated = 0;
    public int $statUpdated = 0;
    public int $statDeleted = 0;

    public function mount(): void
    {
        abort_unless(auth()->user()->hasAnyPermission(['manage_audit_reports', 'review_audit_reports']), 403);
    }

    public function with(): array
    {
        return [
            'reports' => AuditReport::with(['generator', 'reviewer'])->latest()->paginate(10),
            'isAuditor' => auth()->user()->hasPermissionTo('manage_audit_reports'),
            'isGerente' => auth()->user()->hasPermissionTo('review_audit_reports'),
        ];
    }

    public function openCreateModal(): void
    {
        $this->reset(['title', 'startDate', 'endDate', 'description']);
        $this->showCreateModal = true;
    }

    public function generateReport(): void
    {
        abort_unless(auth()->user()->hasPermissionTo('manage_audit_reports'), 403);

        $this->validate([
            'title' => 'required|string|max:150',
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
            'description' => 'nullable|string',
        ]);

        AuditReport::create([
            'title' => $this->title,
            'description' => $this->description,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
            'generated_by' => auth()->id(),
            'status' => 'pending',
        ]);

        $this->showCreateModal = false;
        $this->reset(['title', 'startDate', 'endDate', 'description']);
        
        // Return a toast notification using Flux
        // Note: Flux uses dynamic session variables or dispatch events for toasts
        session()->flash('mensaje', 'Reporte de auditoría generado correctamente.');
    }

    public function viewDetails(AuditReport $report): void
    {
        $this->selectedReport = $report;
        $this->comments = $report->comments ?? '';

        // Calculate statistics dynamically for the report date range
        $start = $report->start_date->format('Y-m-d') . ' 00:00:00';
        $end = $report->end_date->format('Y-m-d') . ' 23:59:59';

        $this->statLogins = AuditLog::whereBetween('created_at', [$start, $end])->where('event', 'login')->count();
        $this->statFailed = AuditLog::whereBetween('created_at', [$start, $end])->where('event', 'login_failed')->count();
        $this->statCreated = AuditLog::whereBetween('created_at', [$start, $end])->where('event', 'created')->count();
        $this->statUpdated = AuditLog::whereBetween('created_at', [$start, $end])->where('event', 'updated')->count();
        $this->statDeleted = AuditLog::whereBetween('created_at', [$start, $end])->where('event', 'deleted')->count();

        $this->showViewModal = true;
    }

    public function saveReview(): void
    {
        abort_unless(auth()->user()->hasPermissionTo('review_audit_reports'), 403);

        $this->validate([
            'comments' => 'required|string|max:1000',
        ]);

        if ($this->selectedReport) {
            $this->selectedReport->update([
                'status' => 'reviewed',
                'comments' => $this->comments,
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
            ]);
        }

        $this->showViewModal = false;
        session()->flash('mensaje', 'Informe de auditoría revisado y firmado correctamente.');
    }
}; ?>

<div class="space-y-6">
    <!-- Page Header -->
    <div class="admin-page-header">
        <div>
            <h1 class="admin-page-title">Informes de Auditoría</h1>
            <p class="admin-page-subtitle">Genera resúmenes de cambios y revisa las firmas de auditoría del sistema.</p>
        </div>
        <div>
            @if($isAuditor)
                <button wire:click="openCreateModal"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg shadow-sm transition-all text-sm cursor-pointer">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Generar Reporte de Cambios
                </button>
            @endif
        </div>
    </div>

    <!-- Feedback alert -->
    @if(session()->has('mensaje'))
        <div class="p-4 rounded-lg bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-900/40 text-emerald-800 dark:text-emerald-300 text-sm font-medium animate-fade-in-up">
            {{ session('mensaje') }}
        </div>
    @endif

    <!-- Reports Table -->
    <div class="admin-table-wrapper animate-fade-in-up">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Periodo Auditado</th>
                    <th>Generado Por</th>
                    <th>Estado</th>
                    <th>Revisado Por</th>
                    <th class="text-right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reports as $report)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20">
                        <td>
                            <div class="font-medium text-slate-900 dark:text-slate-100 text-sm">{{ $report->title }}</div>
                            <div class="text-xs text-slate-400 mt-0.5">{{ $report->created_at->format('d/m/Y H:i') }}</div>
                        </td>
                        <td class="text-slate-500 dark:text-slate-400 text-xs">
                            <div class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span>{{ $report->start_date->format('d/m/Y') }}</span>
                                <span class="text-slate-300 dark:text-slate-700">al</span>
                                <span>{{ $report->end_date->format('d/m/Y') }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="text-xs font-medium text-slate-700 dark:text-slate-300">{{ $report->generator->name }}</div>
                            <div class="text-[10px] text-slate-400">Auditor</div>
                        </td>
                        <td>
                            @if($report->status === 'reviewed')
                                <span class="badge-emerald text-[10px] flex items-center gap-1 w-fit">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    REVISADO
                                </span>
                            @else
                                <span class="badge-orange text-[10px] flex items-center gap-1 w-fit">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    PENDIENTE
                                </span>
                            @endif
                        </td>
                        <td class="text-xs text-slate-500 dark:text-slate-400">
                            @if($report->reviewer)
                                <div class="font-medium text-slate-700 dark:text-slate-300">{{ $report->reviewer->name }}</div>
                                <div class="text-[10px] text-slate-400">{{ $report->reviewed_at->format('d/m/Y H:i') }}</div>
                            @else
                                <span class="italic text-slate-400 text-xs">Sin revisar</span>
                            @endif
                        </td>
                        <td class="text-right">
                            <button wire:click="viewDetails({{ $report->id }})"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-50 dark:bg-emerald-950/20 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-100 dark:hover:bg-emerald-950/40 transition-colors cursor-pointer">
                                {{ $isGerente && $report->status !== 'reviewed' ? 'Revisar' : 'Ver Informe' }}
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-12">
                            <div class="flex flex-col items-center gap-2 text-slate-400">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <p class="font-medium text-sm">No se han generado informes de auditoría</p>
                                <p class="text-xs">Los auditores pueden generar informes usando el botón superior</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $reports->links() }}
    </div>

    <!-- Create Report Modal (Auditor) -->
    <flux:modal wire:model="showCreateModal" class="md:w-[500px]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Generar Reporte de Cambios</flux:heading>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">El sistema computará automáticamente los cambios de base de datos del rango seleccionado.</p>
            </div>

            <form wire:submit="generateReport" class="space-y-4">
                <flux:input wire:model="title" label="Título del Reporte" placeholder="Ej. Reporte de Auditoría Semanal - Julio 2026" required />
                
                <div class="grid grid-cols-2 gap-4">
                    <flux:input type="date" wire:model="startDate" label="Fecha Inicio" required />
                    <flux:input type="date" wire:model="endDate" label="Fecha Fin" required />
                </div>

                <flux:textarea wire:model="description" label="Comentarios/Conclusiones de Auditoría" rows="4" placeholder="Escribe aquí tus observaciones preliminares sobre el comportamiento del sistema..." />

                <div class="flex justify-end gap-2 pt-2">
                    <flux:button wire:click="$set('showCreateModal', false)" variant="ghost">Cancelar</flux:button>
                    <flux:button type="submit" variant="primary" class="!bg-emerald-600 hover:!bg-emerald-700">Generar Reporte</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>

    <!-- View / Review Report Modal -->
    <flux:modal wire:model="showViewModal" class="w-full max-w-3xl">
        <div class="space-y-6">
            @if($selectedReport)
                <div class="flex justify-between items-start">
                    <div>
                        <flux:heading size="lg">{{ $selectedReport->title }}</flux:heading>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                            Generado por {{ $selectedReport->generator->name }} el {{ $selectedReport->created_at->format('d/m/Y H:i') }}
                        </p>
                    </div>
                    <div>
                        @if($selectedReport->status === 'reviewed')
                            <span class="badge-emerald text-xs">FIRMADO Y REVISADO</span>
                        @else
                            <span class="badge-orange text-xs">PENDIENTE DE REVISIÓN</span>
                        @endif
                    </div>
                </div>

                <!-- Range Metadata -->
                <div class="p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg text-xs grid grid-cols-2 gap-4">
                    <div>
                        <span class="text-slate-400">Rango Auditado</span>
                        <div class="font-semibold text-slate-800 dark:text-slate-200 text-sm mt-0.5">
                            {{ $selectedReport->start_date->format('d/m/Y') }} al {{ $selectedReport->end_date->format('d/m/Y') }}
                        </div>
                    </div>
                    <div>
                        <span class="text-slate-400">Firma del Auditor</span>
                        <div class="font-mono text-slate-600 dark:text-slate-400 mt-0.5">
                            hash::{{ md5($selectedReport->id . $selectedReport->created_at) }}
                        </div>
                    </div>
                </div>

                <!-- Stats summary grid -->
                <div class="space-y-2">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">Resumen de Actividades en el Periodo</span>
                    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
                        <div class="p-3 bg-blue-50/50 dark:bg-blue-950/10 border border-blue-100 dark:border-blue-900/20 rounded-lg text-center">
                            <div class="text-lg font-bold text-blue-600 dark:text-blue-400">{{ $statLogins }}</div>
                            <div class="text-[9px] text-slate-400 uppercase font-semibold">Logins</div>
                        </div>
                        <div class="p-3 bg-red-50/50 dark:bg-red-950/10 border border-red-100 dark:border-red-900/20 rounded-lg text-center">
                            <div class="text-lg font-bold text-red-600 dark:text-red-400">{{ $statFailed }}</div>
                            <div class="text-[9px] text-slate-400 uppercase font-semibold">Fallidos</div>
                        </div>
                        <div class="p-3 bg-emerald-50/50 dark:bg-emerald-950/10 border border-emerald-100 dark:border-emerald-900/20 rounded-lg text-center">
                            <div class="text-lg font-bold text-emerald-600 dark:text-emerald-400">{{ $statCreated }}</div>
                            <div class="text-[9px] text-slate-400 uppercase font-semibold">Creados</div>
                        </div>
                        <div class="p-3 bg-orange-50/50 dark:bg-orange-950/10 border border-orange-100 dark:border-orange-900/20 rounded-lg text-center">
                            <div class="text-lg font-bold text-orange-600 dark:text-orange-400">{{ $statUpdated }}</div>
                            <div class="text-[9px] text-slate-400 uppercase font-semibold">Modificados</div>
                        </div>
                        <div class="p-3 bg-rose-50/50 dark:bg-rose-950/10 border border-rose-100 dark:border-rose-900/20 rounded-lg text-center">
                            <div class="text-lg font-bold text-rose-600 dark:text-rose-400">{{ $statDeleted }}</div>
                            <div class="text-[9px] text-slate-400 uppercase font-semibold">Eliminados</div>
                        </div>
                    </div>
                </div>

                <!-- Description / Auditor Comments -->
                <div class="space-y-2">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">Observaciones del Auditor</span>
                    <div class="p-4 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg text-sm text-slate-700 dark:text-slate-300 min-h-20 whitespace-pre-wrap">
                        {{ $selectedReport->description ?: 'Sin observaciones.' }}
                    </div>
                </div>

                <!-- Review / Comments Section -->
                @if($selectedReport->status === 'reviewed')
                    <div class="p-4 bg-emerald-50/30 dark:bg-emerald-950/10 border border-emerald-150 dark:border-emerald-900/30 rounded-lg space-y-2">
                        <div class="flex justify-between items-center text-xs font-bold text-emerald-800 dark:text-emerald-300 uppercase tracking-wider">
                            <span>Revisión de Gerencia</span>
                            <span>Firmado por: {{ $selectedReport->reviewer->name }}</span>
                        </div>
                        <p class="text-sm text-slate-700 dark:text-slate-300 italic whitespace-pre-wrap">
                            "{{ $selectedReport->comments }}"
                        </p>
                        <div class="text-[10px] text-slate-400 text-right">
                            Fecha de firma: {{ $selectedReport->reviewed_at->format('d/m/Y H:i:s') }}
                        </div>
                    </div>
                @elseif($isGerente)
                    <form wire:submit="saveReview" class="space-y-3 pt-2 border-t border-slate-200 dark:border-slate-800">
                        <flux:textarea wire:model="comments" label="Comentarios de Revisión (Gerente)" required rows="3" placeholder="Ingresa tus comentarios u observaciones sobre este informe de auditoría..." />
                        
                        <div class="flex justify-end gap-2">
                            <flux:button wire:click="$set('showViewModal', false)" variant="ghost">Cerrar</flux:button>
                            <flux:button type="submit" variant="primary" class="!bg-emerald-600 hover:!bg-emerald-700">Firmar y Aprobar Informe</flux:button>
                        </div>
                    </form>
                @endif

                @if($selectedReport->status !== 'reviewed' && !$isGerente)
                    <div class="flex justify-end pt-2">
                        <flux:button wire:click="$set('showViewModal', false)" variant="ghost">Cerrar</flux:button>
                    </div>
                @endif
            @endif
        </div>
    </flux:modal>
</div>
