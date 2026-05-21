@props([
    'person' => [],
    'featured' => false,
])

<article @class([
    'silatar-person-card',
    'silatar-person-card-featured' => $featured,
])>
    <div @class([
        'silatar-person-card-body',
        'p-6' => $featured,
    ])>
        @if (! empty($person['photo_path']))
            <div @class([
                'mx-auto overflow-hidden rounded-full border border-white/70 shadow-xl',
                'h-28 w-28' => $featured,
                'h-20 w-20' => ! $featured,
            ])>
                <img
                    src="{{ $person['photo_path'] }}"
                    alt="{{ $person['name'] ?? 'PP' }}"
                    class="h-full w-full object-cover"
                    onerror="this.style.display='none'; this.parentElement.nextElementSibling.classList.remove('hidden');"
                >
            </div>
        @endif

        <div @class([
            'silatar-person-avatar',
            'silatar-person-avatar-featured' => $featured,
            'hidden' => ! empty($person['photo_path']),
        ])>
            {{ $person['avatar_text'] ?? 'PP' }}
        </div>

        <p @class([
            'silatar-person-name',
            'silatar-person-name-featured' => $featured,
        ])>
            {{ $person['name'] ?? '-' }}
        </p>

        <div class="silatar-person-role">
            {{ $person['role_label'] ?? 'Pegawai' }}
        </div>

        <div class="silatar-person-meta">
            <div>
                <span class="font-semibold text-slate-700">Nomor Induk: </span>
                <span>{{ $person['nomor_induk'] ?? '-' }}</span>
            </div>
            <div>
                <span class="font-semibold text-slate-700">Satker: </span>
                <span>{{ $person['satker'] ?? '-' }}</span>
            </div>
        </div>
    </div>
</article>
