{{-- Partial: Kriteria Item untuk Form Penilaian (NEO MIRAI Theme) --}}

@php
$iconColors = ['cyan', 'emerald', 'amber', 'violet', 'rose', 'blue', 'indigo', 'slime'];
$colorIndex = array_search($kriteriaKey, array_keys(\App\Models\PenilaianKriteria::KRITERIA));
$iconColor = $iconColors[$colorIndex] ?? 'cyan';
@endphp

<div class="kriteria-card" x-data="{
    up: 0,
    down: 0
}">
    <div class="kriteria-card-header">
        <div class="kriteria-icon {{ $iconColor }}">
            {{-- Icon sesuai kriteria --}}
            @switch($kriteriaInfo['icon'])
                @case('heart-handshake')
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>
                        <path d="m12 13-1-1 2-2-3-3 2-2"/>
                        <path d="m18 13 1-1-2-2 3-3-2-2"/>
                    </svg>
                @break
                @case('shield-check')
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/>
                        <path d="m9 12 2 2 4-4"/>
                    </svg>
                @break
                @case('award')
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="8" r="6"/>
                        <path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/>
                    </svg>
                @break
                @case('users')
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                @break
                @case('flag')
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/>
                        <line x1="4" x2="4" y1="22" y2="15"/>
                    </svg>
                @break
                @case('refresh-cw')
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/>
                        <path d="M21 3v5h-5"/>
                        <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/>
                        <path d="M8 16H3v5"/>
                    </svg>
                @break
                @case('git-branch')
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="6" x2="6" y1="3" y2="15"/>
                        <circle cx="18" cy="6" r="3"/>
                        <circle cx="6" cy="18" r="3"/>
                        <path d="M18 9a9 9 0 0 1-9 9"/>
                    </svg>
                @break
                @default
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                    </svg>
            @endswitch
        </div>
        <div class="kriteria-info">
            <h4>{{ $kriteriaInfo['nama'] }}</h4>
            <p>{{ $kriteriaInfo['deskripsi'] }}</p>
        </div>
    </div>

    {{-- Textarea Catatan --}}
    <div class="mb-4">
        <textarea
            name="kriteria[{{ $kriteriaKey }}][catatan]"
            class="neo-input"
            rows="2"
            placeholder="Catatan untuk kriteria ini (opsional)..."
        ></textarea>
    </div>

    {{-- Thumbs Controls --}}
    <div class="thumbs-controls">
        {{-- Thumbs Up --}}
        <div class="thumbs-group">
            <span class="thumbs-label">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"/>
                </svg>
                Bagus
            </span>
            <button type="button" @click="up = Math.max(0, up - 1)" class="thumbs-btn minus">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
            </button>
            <span class="thumbs-count positive" x-text="up">0</span>
            <button type="button" @click="up = Math.min(9, up + 1)" class="thumbs-btn up">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"/>
                </svg>
            </button>
            <input type="hidden" name="kriteria[{{ $kriteriaKey }}][thumbs_up]" :value="up">
        </div>

        {{-- Thumbs Down --}}
        <div class="thumbs-group">
            <span class="thumbs-label">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10 15v4a3 3 0 0 0 3 3l4-9V2H5.72a2 2 0 0 0-2 1.7l-1.38 9a2 2 0 0 0 2 2.3zM17 2h2.67A2.31 2.31 0 0 1 22 4v7a2.31 2.31 0 0 1-2.33 2H17"/>
                </svg>
                Kurang
            </span>
            <button type="button" @click="down = Math.max(0, down - 1)" class="thumbs-btn minus">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
            </button>
            <span class="thumbs-count negative" x-text="down">0</span>
            <button type="button" @click="down = Math.min(9, down + 1)" class="thumbs-btn down">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10 15v4a3 3 0 0 0 3 3l4-9V2H5.72a2 2 0 0 0-2 1.7l-1.38 9a2 2 0 0 0 2 2.3zM17 2h2.67A2.31 2.31 0 0 1 22 4v7a2.31 2.31 0 0 1-2.33 2H17"/>
                </svg>
            </button>
            <input type="hidden" name="kriteria[{{ $kriteriaKey }}][thumbs_down]" :value="down">
        </div>
    </div>
</div>
