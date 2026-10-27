{{-- Shared picker kartu tipe section (dipakai di modal "Ganti Tipe" dan "Tambah Section").

     Parameter:
       $typeCards   — array registry tipe (default: config('ppid.section_types'))
       $radioName   — nama input radio
       $alpineModel — ekspresi Alpine untuk state terpilih, mis. "typeChoice"
       $current     — tipe yang sedang aktif (untuk menandai terpilih) --}}
@php
    $typeCards = $typeCards ?? config('ppid.section_types');
    $radioName = $radioName ?? 'section_type';
    $alpineModel = $alpineModel ?? 'typeChoice';
    $current = $current ?? null;
@endphp

<div class="update-type-grid">
    @foreach($typeCards as $cardKey => $card)
        <label class="update-type-card"
               :class="{ 'selected': {{ $alpineModel }} === '{{ $cardKey }}' }">
            <input type="radio"
                   name="{{ $radioName }}"
                   value="{{ $cardKey }}"
                   x-model="{{ $alpineModel }}"
                   @if($current === $cardKey) checked @endif>

            <span class="update-type-icon {{ $card['color'] }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                     width="22" height="22" aria-hidden="true">{!! $card['icon'] !!}</svg>
            </span>

            <span class="update-type-info">
                <span class="update-type-title">{{ $card['label'] }}</span>
                <span class="update-type-desc">{{ $card['description'] }}</span>
            </span>

            <span class="update-type-check" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                     width="14" height="14"><polyline points="20 6 9 17 4 12"/></svg>
            </span>
        </label>
    @endforeach
</div>
