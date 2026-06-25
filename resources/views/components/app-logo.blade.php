@props([
    'sidebar' => false,
])

@if($sidebar)
    <flux:sidebar.brand name="Scarlybu" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-lg bg-gradient-to-br from-emerald-500 to-emerald-600">
            <x-app-logo-icon class="size-5 fill-current text-white dark:text-white" />
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand name="Scarlybu" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-lg bg-gradient-to-br from-emerald-500 to-emerald-600">
            <x-app-logo-icon class="size-5 fill-current text-white dark:text-white" />
        </x-slot>
    </flux:brand>
@endif
