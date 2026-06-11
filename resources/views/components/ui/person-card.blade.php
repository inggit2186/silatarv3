@props([
    'person' => [],
    'featured' => false,
])

<article @class([
    'cyber-person-card',
    'cyber-person-card-featured' => $featured,
])>
    <div @class([
        'cyber-person-photo-wrapper',
        'cyber-person-photo-wrapper-featured' => $featured,
    ])>
        @if (! empty($person['photo_path']))
            <div class="cyber-person-photo">
                <img
                    src="{{ $person['photo_path'] }}"
                    alt="{{ $person['name'] ?? 'PP' }}"
                    class="cyber-person-photo-img"
                    onerror="this.style.display='none'; this.parentElement.style.display='none'; this.parentElement.nextElementSibling.style.display='flex';"
                >
            </div>
            <div class="cyber-person-avatar" style="display: none;">
                {{ $person['avatar_text'] ?? 'PP' }}
            </div>
        @else
            <div class="cyber-person-avatar">
                {{ $person['avatar_text'] ?? 'PP' }}
            </div>
        @endif

        @if ($featured)
            <div class="cyber-person-glow-ring"></div>
        @endif
    </div>

    <div class="cyber-person-info">
        <p class="cyber-person-name">{{ $person['name'] ?? '-' }}</p>
        <p class="cyber-person-role">{{ $person['role_label'] ?? 'Pegawai' }}</p>

        <div class="cyber-person-meta">
            <div class="cyber-person-meta-item">
                <span class="cyber-person-meta-label">NIP</span>
                <span class="cyber-person-meta-value">{{ $person['nomor_induk'] ?? '-' }}</span>
            </div>
        </div>
    </div>

    @if ($featured)
        <div class="cyber-card-glow-line"></div>
    @endif
</article>