<x-layouts::app.sidebar :title="$title ?? null">
    {{ $slot }}
    @stack('scripts')
</x-layouts::app.sidebar>