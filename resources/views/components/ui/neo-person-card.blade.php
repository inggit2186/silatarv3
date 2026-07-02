@props([
    'person' => [],
    'featured' => false,
])

<article @class([
    'neo-person-card',
    'neo-person-card-featured' => $featured,
])>
    <div @class([
        'neo-person-photo-wrapper',
        'neo-person-photo-wrapper-featured' => $featured,
    ])>
        @if (! empty($person['photo_path']))
            <div class="neo-person-photo">
                <img
                    src="{{ $person['photo_path'] }}"
                    alt="{{ $person['name'] ?? 'PP' }}"
                    class="neo-person-photo-img"
                    onerror="this.style.display='none'; this.parentElement.style.display='none'; this.parentElement.nextElementSibling.style.display='flex';"
                >
            </div>
            <div class="neo-person-avatar" style="display: none;">
                {{ $person['avatar_text'] ?? 'PP' }}
            </div>
        @else
            <div class="neo-person-avatar">
                {{ $person['avatar_text'] ?? 'PP' }}
            </div>
        @endif
    </div>

    <div class="neo-person-info">
        <h3 class="neo-person-name">{{ $person['name'] ?? '-' }}</h3>
        <p class="neo-person-role">{{ $person['role_label'] ?? 'Pegawai' }}</p>

        @if (! empty($person['nomor_induk']))
            <div class="neo-person-meta">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <span class="neo-person-meta-value">{{ $person['nomor_induk'] ?? '-' }}</span>
            </div>
        @endif
    </div>
</article>
