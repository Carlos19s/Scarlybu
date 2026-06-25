<?php

use Livewire\Volt\Component;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

new #[Layout('layouts.app')] #[Title('Usuarios y Perfiles')] class extends Component {
    use WithPagination;

    public bool $showModal = false;
    public ?User $editingUser = null;

    public string $name = '';
    public string $email = '';
    public string $role = '';
    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function with(): array
    {
        return [
            'users' => User::with('roles')
                ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%"))
                ->latest()
                ->paginate(10),
            'roles' => Role::all(),
        ];
    }

    public function edit(User $user): void
    {
        $this->editingUser = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->roles->first()?->name ?? '';
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate([
            'role' => 'required|exists:roles,name',
        ]);

        if ($this->editingUser) {
            $this->editingUser->syncRoles([$this->role]);
        }

        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->reset(['editingUser', 'name', 'email', 'role']);
    }
}; ?>

<div class="space-y-6">
    <!-- Page Header -->
    <div class="admin-page-header">
        <div>
            <h1 class="admin-page-title">Usuarios y Perfiles</h1>
            <p class="admin-page-subtitle">Gestiona los usuarios y sus roles de acceso</p>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="admin-toolbar">
        <div class="relative flex-1">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar por nombre o email..."
                   class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm transition-colors">
        </div>
    </div>

    <!-- Users Table -->
    <div class="admin-table-wrapper animate-fade-in-up">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Registrado</th>
                    <th class="text-right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="user-avatar">
                                    {{ $user->initials() }}
                                </div>
                                <span class="font-medium text-slate-900 dark:text-slate-100">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="text-slate-500 dark:text-slate-400">{{ $user->email }}</td>
                        <td>
                            @php $roleName = $user->roles->first()?->name ?? 'sin_rol'; @endphp
                            @if($roleName === 'admin_sistema')
                                <span class="badge-orange">{{ ucfirst(str_replace('_', ' ', $roleName)) }}</span>
                            @elseif($roleName === 'gerente')
                                <span class="badge-emerald">{{ ucfirst($roleName) }}</span>
                            @elseif($roleName === 'vendedor')
                                <span class="badge-blue">{{ ucfirst($roleName) }}</span>
                            @else
                                <span class="badge-zinc">{{ ucfirst(str_replace('_', ' ', $roleName)) }}</span>
                            @endif
                        </td>
                        <td class="text-slate-500 dark:text-slate-400 text-sm">{{ $user->created_at->format('d/m/Y') }}</td>
                        <td class="text-right">
                            <button wire:click="edit({{ $user->id }})"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-medium text-emerald-600 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Cambiar Rol
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-12">
                            <div class="flex flex-col items-center gap-2 text-slate-400">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <p class="font-medium">No se encontraron usuarios</p>
                                <p class="text-sm">Intenta con otro término de búsqueda</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $users->links() }}
    </div>

    <!-- Edit Modal -->
    <flux:modal wire:model="showModal" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Cambiar Rol de Usuario</flux:heading>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Modifica el rol de acceso del usuario</p>
            </div>

            <form wire:submit="save" class="space-y-4">
                <flux:input wire:model="name" label="Nombre" disabled />
                <flux:input wire:model="email" label="Email" disabled />

                <flux:select wire:model="role" label="Rol" required>
                    <option value="">Selecciona un rol...</option>
                    @foreach($roles as $roleOption)
                        <option value="{{ $roleOption->name }}">{{ ucfirst(str_replace('_', ' ', $roleOption->name)) }}</option>
                    @endforeach
                </flux:select>

                <div class="flex justify-end gap-2 pt-2">
                    <flux:button wire:click="$set('showModal', false)" variant="ghost">Cancelar</flux:button>
                    <flux:button type="submit" variant="primary" class="!bg-emerald-600 hover:!bg-emerald-700">Guardar</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</div>
