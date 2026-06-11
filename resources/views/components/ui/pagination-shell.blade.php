@props([
    'paginator',
])

@if ($paginator && $paginator->hasPages())
    @php
        $pageName = $paginator->getPageName();
        $baseQuery = request()->except($pageName);
        $previousUrl = $paginator->previousPageUrl();
        $nextUrl = $paginator->nextPageUrl();
        $currentPage = $paginator->currentPage();
        $lastPage = $paginator->lastPage();
        $total = $paginator->total();

        // Generate visible page numbers (show max 5 around current)
        $start = max(1, $currentPage - 2);
        $end = min($lastPage, $currentPage + 2);
        if ($end - $start < 4) {
            if ($start === 1) {
                $end = min($lastPage, $start + 4);
            } else {
                $start = max(1, $end - 4);
            }
        }
    @endphp

    <div class="cyber-pagination">
        <div class="cyber-pagination-info">
            <span class="cyber-pagination-badge">
                Halaman {{ $currentPage }} / {{ $lastPage }}
            </span>
            <span class="cyber-pagination-text">
                Total {{ $total }} data
            </span>
        </div>

        <div class="cyber-pagination-nav-group">
            <a
                @class(['cyber-pagination-nav', 'is-disabled' => ! $previousUrl])
                href="{{ $previousUrl ?: '#' }}"
                aria-label="Halaman sebelumnya"
            >
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12.5 15 7.5 10l5-5" />
                </svg>
            </a>

            @if ($start > 1)
                <a href="{{ $paginator->url(1) }}" class="cyber-pagination-page">1</a>
                @if ($start > 2)
                    <span class="cyber-pagination-ellipsis">...</span>
                @endif
            @endif

            @for ($i = $start; $i <= $end; $i++)
                <a
                    href="{{ $paginator->url($i) }}"
                    @class(['cyber-pagination-page', 'is-active' => $i === $currentPage])
                >
                    {{ $i }}
                </a>
            @endfor

            @if ($end < $lastPage)
                @if ($end < $lastPage - 1)
                    <span class="cyber-pagination-ellipsis">...</span>
                @endif
                <a href="{{ $paginator->url($lastPage) }}" class="cyber-pagination-page">{{ $lastPage }}</a>
            @endif

            <a
                @class(['cyber-pagination-nav', 'is-disabled' => ! $nextUrl])
                href="{{ $nextUrl ?: '#' }}"
                aria-label="Halaman berikutnya"
            >
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m7.5 5 5 5-5 5" />
                </svg>
            </a>
        </div>

        <form method="GET" class="cyber-pagination-jump">
            @foreach ($baseQuery as $key => $value)
                @if (is_array($value))
                    @foreach ($value as $nestedKey => $nestedValue)
                        <input type="hidden" name="{{ $key }}[{{ $nestedKey }}]" value="{{ $nestedValue }}">
                    @endforeach
                @else
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endif
            @endforeach

            <label for="{{ $pageName }}_jump" class="cyber-pagination-jump-label">Ke halaman</label>
            <input
                id="{{ $pageName }}_jump"
                name="{{ $pageName }}"
                type="number"
                min="1"
                max="{{ $lastPage }}"
                value="{{ $currentPage }}"
                class="cyber-pagination-jump-input"
            >
            <button type="submit" class="cyber-pagination-jump-btn">
                Lompat
            </button>
        </form>
    </div>
@endif