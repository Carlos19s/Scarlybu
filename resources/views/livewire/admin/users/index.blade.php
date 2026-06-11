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

    public function with(): array
    {
        return [
            'users' => User::with('roles')->paginate(10),
            'roles' => Role::all(),
        ];
    }

    public function edit(User $user)
    {
        $this->editingUser = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->roles->first()?->name ?? '';
        $this->showModal = true;
    }

    public function save()
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

    private function resetForm()
    {
        $this->reset(['editingUser', 'name', 'email', 'role']);
    }
}; ?>

<div class="flex flex-col gap-6">
    <div class="flex items-center justify-between">
        <flux:heading size="xl" level="1">Usuarios y Perfiles</flux:heading>
    </div>

    <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 overflow-hidden">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-neutral-200 bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-800">
                <tr>
                    <th class="p-4 font-medium">Nombre</th>
                    <th class="p-4 font-medium">Email</th>
                    <th class="p-4 font-medium">Rol</th>
                    <th class="p-4 font-medium text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                @foreach($users as $user)
                    <tr class="bg-white dark:bg-neutral-900">
                        <td class="p-4 font-medium">{{ $user->name }}</td>
                        <td class="p-4">{{ $user->email }}</td>
                        <td class="p-4">
                            @php
                                $roleName = $user->roles->first()?->name ?? 'Sin Rol';
                                $color = match($roleName) {
                                    'admin_sistema' => 'purple',
                                    'gerente' => 'blue',
                                    'vendedor' => 'green',
                                    default => 'zinc',
                                };
                            @endphp
                            <flux:badge :color="$color">{{ ucfirst(str_replace('_', ' ', $roleName)) }}</flux:badge>
                        </td>
                        <td class="p-4 text-right">
                            <flux:button wire:click="edit({{ $user->id }})" variant="ghost" size="sm">Cambiar Rol</flux:button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div>
        {{ $users->links() }}
    </div>

    <flux:modal wire:model="showModal" class="md:w-1/2">
        <div class="space-y-6">
            <flux:heading size="lg">Editar Perfil de Usuario</flux:heading>

            <form wire:submit="save" class="space-y-4">
                <flux:input wire:model="name" label="Nombre" disabled />
                <flux:input wire:model="email" label="Email" disabled />
                
                <flux:select wire:model="role" label="Rol" required>
                    <option value="">Selecciona un rol...</option>
                    @foreach($roles as $roleOption)
                        <option value="{{ $roleOption->name }}">{{ ucfirst(str_replace('_', ' ', $roleOption->name)) }}</option>
                    @endforeach
                </flux:select>

                <div class="flex justify-end gap-2 mt-4">
                    <flux:button wire:click="$set('showModal', false)" variant="ghost">Cancelar</flux:button>
                    <flux:button type="submit" variant="primary">Guardar</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</div>
