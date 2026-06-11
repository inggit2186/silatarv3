@props([
    'card' => [],
    'sectionLabel' => '',
    'chipClass' => 'bg-cyan-600',
    'coverBg' => '',
    'href' => null,
])

@if ($href)
<a href="{{ $href }}" class="block h-full">
    <article class="cyber-satker-card">
@else
    <article class="cyber-satker-card">
@endif
    <div class="cyber-satker-cover">
        <img
            src="{{ $card['cover_path'] }}"
            alt="{{ $card['title'] }}"
            loading="lazy"
            decoding="async"
            class="cyber-satker-cover-img"
            onerror="this.style.display='none'"
        >
        <div class="cyber-satker-cover-overlay"></div>
        <div class="cyber-satker-cover-chip">
            {{ $sectionLabel }}
        </div>
        <div class="cyber-satker-cover-star">
            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.7">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 17s-6-3.5-6-8.5A3.5 3.5 0 0 1 10 5a3.5 3.5 0 0 1 6 3.5C16 13.5 10 17 10 17Z" />
            </svg>
        </div>
        <div class="cyber-satker-cover-label">
            <p class="cyber-satker-cover-label-text">
                {{ $card['title'] }}
            </p>
        </div>
    </div>

    <div class="cyber-satker-body">
        @if (! empty($card['head_photo']) || ! empty($card['head_value']))
            <div class="cyber-satker-head">
                <div class="cyber-satker-head-photo">
                    @if (! empty($card['head_photo']))
                        <img
                            src="{{ $card['head_photo'] }}"
                            alt="{{ $card['head_value'] }}"
                            class="cyber-satker-head-img"
                            onerror="this.style.display='none'; this.parentElement.textContent='{{ $card['head_initials'] ?? 'NA' }}';"
                        >
                    @else
                        <span class="cyber-satker-head-initials">{{ $card['head_initials'] ?? 'NA' }}</span>
                    @endif
                </div>
                <div class="cyber-satker-head-info">
                    <p class="cyber-satker-head-label">{{ $card['head_label'] }}</p>
                    <p class="cyber-satker-head-value">{{ $card['head_value'] }}</p>
                </div>
            </div>
        @endif

        <div class="cyber-satker-info">
            <span class="cyber-satker-info-label">
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.7">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 4a3 3 0 0 1 3 3v1a3 3 0 0 1-6 0V7a3 3 0 0 1 3-3Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16a6 6 0 0 1 12 0" />
                </svg>
                Pegawai
            </span>
            <span class="cyber-satker-info-value">
                {{ $card['extra_value'] }}
            </span>
        </div>

        <div class="cyber-satker-footer">
            <span class="cyber-satker-meta">
                {{ $sectionLabel }}
                @if (($card['type'] ?? null) === 'user')
                    <span class="cyber-satker-meta-pp">PP</span>
                @endif
            </span>
            <span class="cyber-satker-location">
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.7">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 17s-5-3.2-5-7.4A5 5 0 1 1 15 9.6C15 13.8 10 17 10 17Z" />
                    <circle cx="10" cy="9.5" r="1.4" />
                </svg>
                Unit SILATAR
            </span>
        </div>
    </div>

    <div class="cyber-card-glow-line"></div>
@if ($href)
    </article>
</a>
@else
    </article>
@endif