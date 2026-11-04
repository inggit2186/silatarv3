@php
    // $soal  : UjianSoal|null  (null = mode tambah)
    // $jumlah: jumlah baris opsi yang ditampilkan
    $opsi = $soal?->pilihan->values() ?? collect();
    $huruf = ['A', 'B', 'C', 'D', 'E', 'F'];
@endphp

<div class="space-y-2">
    @for ($i = 0; $i < $jumlah; $i++)
        @php
            $p = $opsi->get($i);
        @endphp
        <div class="flex items-center gap-3">
            <input type="hidden" name="pilihan[{{ $i }}][id]" value="{{ $p->id ?? '' }}">
            <input type="radio" name="pilihan_benar" value="{{ $i }}"
                @checked((int) old('pilihan_benar', $p?->benar ? $i : -1) === $i)>
            <span class="w-7 h-7 rounded-lg flex items-center justify-center text-xs font-bold flex-shrink-0"
                style="background: rgba(212,168,83,0.12); color: var(--gold);">{{ $huruf[$i] }}</span>
            <input type="text" name="pilihan[{{ $i }}][teks]" value="{{ $p->teks ?? '' }}"
                placeholder="Opsi jawaban {{ $huruf[$i] }}" class="form-input">
        </div>
    @endfor
</div>
<p class="text-sm text-muted mt-2">Pilih radio di kiri untuk menandai kunci jawaban yang benar.</p>
