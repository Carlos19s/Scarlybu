@php
if (! isset($scrollTo)) {
    $scrollTo = 'body';
}

$scrollIntoViewJsSnippet = ($scrollTo !== false)
    ? <<<JS
       (\$el.closest('{$scrollTo}') || document.querySelector('{$scrollTo}')).scrollIntoView()
    JS
    : '';
@endphp

<div>
    @if ($paginator->hasPages())
        <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
            <span>
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium leading-5 rounded-xl cursor-default opacity-50" style="background:white; color:#1f2128;">
                        &laquo; Anterior
                    </span>
                @else
                    <button type="button" wire:click="previousPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled" class="relative inline-flex items-center px-4 py-2 text-sm font-medium leading-5 rounded-xl transition-all hover:scale-105 active:scale-95" style="background:white; color:#1f2128; box-shadow:0 4px 15px rgba(255,255,255,0.1);">
                        &laquo; Anterior
                    </button>
                @endif
            </span>

            {{-- Current Page Information --}}
            <span class="text-sm font-semibold text-center" style="color:#b8bac1;">
                Página <span style="color:#f2f2f7;">{{ $paginator->currentPage() }}</span> de <span style="color:#f2f2f7;">{{ $paginator->lastPage() }}</span>
            </span>

            <span>
                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <button type="button" wire:click="nextPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled" class="relative inline-flex items-center px-4 py-2 text-sm font-medium leading-5 rounded-xl transition-all hover:scale-105 active:scale-95" style="background:white; color:#1f2128; box-shadow:0 4px 15px rgba(255,255,255,0.1);">
                        Siguiente &raquo;
                    </button>
                @else
                    <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium leading-5 rounded-xl cursor-default opacity-50" style="background:white; color:#1f2128;">
                        Siguiente &raquo;
                    </span>
                @endif
            </span>
        </nav>
    @endif
</div>
