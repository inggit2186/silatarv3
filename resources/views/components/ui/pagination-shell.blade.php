@props([
    'paginator',
])

@if ($paginator && $paginator->hasPages())
    @php
        $paginationConfig = config('pagination', []);
        $labels = $paginationConfig['labels'] ?? [];
        $classes = $paginationConfig['classes'] ?? [];
        $pageName = $paginator->getPageName();
        $baseQuery = request()->except($pageName);
        $previousUrl = $paginator->previousPageUrl();
        $nextUrl = $paginator->nextPageUrl();
        $shellClass = $classes['shell'] ?? 'silatar-pagination-shell';
        $panelClass = $classes['panel'] ?? 'silatar-pagination-panel';
        $sideClass = $classes['side'] ?? 'silatar-pagination-side';
        $metaClass = $classes['meta'] ?? 'silatar-pagination-meta';
        $badgeClass = $classes['badge'] ?? 'silatar-pagination-badge';
        $textClass = $classes['text'] ?? 'silatar-pagination-text';
        $jumpClass = $classes['jump'] ?? 'silatar-pagination-jump';
        $labelClass = $classes['label'] ?? 'silatar-pagination-form-label';
        $inputClass = $classes['input'] ?? 'silatar-pagination-input';
        $buttonClass = $classes['button'] ?? 'silatar-pagination-button';
        $navClass = $classes['nav'] ?? 'silatar-pagination-nav';
        $pageLabel = $labels['page'] ?? 'Page';
        $totalLabel = $labels['total'] ?? 'Total';
        $jumpLabel = $labels['jump'] ?? 'Ke halaman';
        $jumpButtonLabel = $labels['jump_button'] ?? 'Lompat';
        $previousLabel = $labels['previous'] ?? 'Halaman sebelumnya';
        $nextLabel = $labels['next'] ?? 'Halaman berikutnya';
    @endphp

    <div {{ $attributes->merge(['class' => $shellClass]) }}>
        <div class="{{ $panelClass }}">
            <div class="{{ $sideClass }}">
                <a
                    @class([
                        $navClass,
                        'is-disabled pointer-events-none opacity-40' => ! $previousUrl,
                    ])
                    href="{{ $previousUrl ?: '#' }}"
                    aria-label="{{ $previousLabel }}"
                >
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12.5 15 7.5 10l5-5" />
                    </svg>
                </a>

                <div class="{{ $metaClass }}">
                    <span class="{{ $badgeClass }}">{{ $pageLabel }} {{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}</span>
                    <span class="{{ $textClass }}">{{ $totalLabel }} {{ $paginator->total() }} data</span>
                </div>

                <a
                    @class([
                        $navClass,
                        'is-disabled pointer-events-none opacity-40' => ! $nextUrl,
                    ])
                    href="{{ $nextUrl ?: '#' }}"
                    aria-label="{{ $nextLabel }}"
                >
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m7.5 5 5 5-5 5" />
                    </svg>
                </a>
            </div>

            <form method="GET" class="{{ $jumpClass }}">
                @foreach ($baseQuery as $key => $value)
                    @if (is_array($value))
                        @foreach ($value as $nestedKey => $nestedValue)
                            <input type="hidden" name="{{ $key }}[{{ $nestedKey }}]" value="{{ $nestedValue }}">
                        @endforeach
                    @else
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endif
                @endforeach

                <label for="{{ $pageName }}_jump" class="{{ $labelClass }}">{{ $jumpLabel }}</label>
                <input
                    id="{{ $pageName }}_jump"
                    name="{{ $pageName }}"
                    type="number"
                    min="1"
                    max="{{ $paginator->lastPage() }}"
                    value="{{ $paginator->currentPage() }}"
                    class="{{ $inputClass }}"
                >
                <button type="submit" class="{{ $buttonClass }}">
                    {{ $jumpButtonLabel }}
                </button>
            </form>
        </div>
    </div>
@endif
