@props([
    'paginator',
    'compact' => false,
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

    <div class="neo-pagination-wrapper" x-data="{ showJump: false, jumpPage: {{ $currentPage }} }">
        <div class="neo-pagination">
            {{-- Previous Button --}}
            @if ($previousUrl)
                <a href="{{ $previousUrl }}" class="neo-pagination-btn" aria-label="Halaman sebelumnya">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                        <path d="M15 18l-6-6 6-6"/>
                    </svg>
                </a>
            @else
                <span class="neo-pagination-btn neo-pagination-btn-disabled">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                        <path d="M15 18l-6-6 6-6"/>
                    </svg>
                </span>
            @endif

            {{-- Page Numbers --}}
            @if ($start > 1)
                <a href="{{ $paginator->url(1) }}" class="neo-pagination-page">1</a>
                @if ($start > 2)
                    <span class="neo-pagination-dots">...</span>
                @endif
            @endif

            @for ($i = $start; $i <= $end; $i++)
                @if ($i === $currentPage)
                    <span class="neo-pagination-page neo-pagination-page-active">{{ $i }}</span>
                @else
                    <a href="{{ $paginator->url($i) }}" class="neo-pagination-page">{{ $i }}</a>
                @endif
            @endfor

            @if ($end < $lastPage)
                @if ($end < $lastPage - 1)
                    <span class="neo-pagination-dots">...</span>
                @endif
                <a href="{{ $paginator->url($lastPage) }}" class="neo-pagination-page">{{ $lastPage }}</a>
            @endif

            {{-- Next Button --}}
            @if ($nextUrl)
                <a href="{{ $nextUrl }}" class="neo-pagination-btn" aria-label="Halaman berikutnya">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                        <path d="M9 18l6-6-6-6"/>
                    </svg>
                </a>
            @else
                <span class="neo-pagination-btn neo-pagination-btn-disabled">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                        <path d="M9 18l6-6-6-6"/>
                    </svg>
                </span>
            @endif
        </div>

        {{-- Info & Go To Page --}}
        <div class="neo-pagination-footer">
            <div class="neo-pagination-info">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
                    <path d="M13 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V9z"/>
                    <path d="M13 2v7h7"/>
                </svg>
                <span>Halaman <strong>{{ $currentPage }}</strong> dari <strong>{{ $lastPage }}</strong></span>
                <span class="neo-pagination-divider">|</span>
                <span>Total <strong>{{ $total }}</strong> data</span>
            </div>

            @if ($lastPage > 1)
                <form method="GET" class="neo-pagination-jump" x-show="showJump" x-transition>
                    @foreach ($baseQuery as $key => $value)
                        @if (is_array($value))
                            @foreach ($value as $nestedKey => $nestedValue)
                                <input type="hidden" name="{{ $key }}[{{ $nestedKey }}]" value="{{ $nestedValue }}">
                            @endforeach
                        @else
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endif
                    @endforeach

                    <label for="{{ $pageName }}_jump">Ke halaman:</label>
                    <input
                        id="{{ $pageName }}_jump"
                        name="{{ $pageName }}"
                        type="number"
                        min="1"
                        max="{{ $lastPage }}"
                        x-model="jumpPage"
                        class="neo-pagination-jump-input"
                    >
                    <span>dari {{ $lastPage }}</span>
                    <button type="submit" class="neo-pagination-jump-btn">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
                            <path d="M5 12h14m-7-7l7 7-7 7"/>
                        </svg>
                        Go
                    </button>
                </form>

                <button
                    type="button"
                    class="neo-pagination-toggle-jump"
                    @click="showJump = !showJump"
                    :class="showJump ? 'is-active' : ''"
                >
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
                        <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <span x-text="showJump ? 'Tutup' : 'Ke Halaman'"></span>
                </button>
            @endif
        </div>
    </div>
@endif
