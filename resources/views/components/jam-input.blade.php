@props([
    'name',
    'model' => 'row.jam_mulai',
])

@php
    $jamHours = array_map(fn ($hour) => str_pad((string) $hour, 2, '0', STR_PAD_LEFT), range(0, 23));
    $jamMinutes = array_map(fn ($menit) => str_pad((string) $menit, 2, '0', STR_PAD_LEFT), range(0, 59));
    $jamCepat = ['06:30', '07:00', '08:00', '09:00', '10:00', '11:30', '12:00', '13:00', '14:00', '15:30', '16:00', '17:00'];
@endphp

<div class="sr-jam-input" @click.outside="row.jamOpen = false">
    <div class="sr-jam-input-row">
        <input
            :name="`{{ $name }}`"
            x-model="{{ $model }}"
            type="text"
            inputmode="numeric"
            maxlength="7"
            pattern="([01][0-9]|2[0-3]):[0-5][0-9]"
            placeholder="HH:MM"
            autocomplete="off"
            title="Format 24 jam (00:00-23:59)"
            @input="applyJam($event, false)"
            @blur="applyJam($event, true)"
            @keydown.enter="applyJam($event, true)"
            class="silatar-report-edit-field"
        >
        <button
            type="button"
            class="sr-jam-toggle"
            :class="row.jamOpen && 'is-open'"
            @click="toggleJamPicker(row)"
            aria-label="Pilih jam"
        >
            <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6">
                <circle cx="10" cy="10" r="7.25" />
                <path stroke-linecap="round" d="M10 5.75V10l2.75 1.75" />
            </svg>
        </button>
    </div>

    <template x-if="row.jamOpen">
        <div
            class="sr-jam-roll"
            x-init="syncJamRoll($el, row)"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
        >
            <div class="sr-jam-roll-head">
                <div class="min-w-0">
                    <p class="sr-jam-roll-eyebrow">Waktu Pelaksanaan</p>
                    <p class="sr-jam-roll-value">
                        <span x-text="row.jamHour || '00'">00</span><span class="sr-jam-roll-sep">:</span><span x-text="row.jamMinute || '00'">00</span>
                    </p>
                </div>
                <span class="sr-jam-roll-badge">WIB</span>
            </div>

            <div class="sr-jam-roll-cols">
                <div class="sr-jam-roll-col">
                    <div class="sr-jam-roll-col-head">
                        <span class="sr-jam-roll-title">Jam</span>
                        <span class="sr-jam-roll-nudge">
                            <button type="button" @click="nudgeJam($event, row, 'hour', -1)" title="Jam sebelumnya" aria-label="Jam sebelumnya">
                                <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12l5-5 5 5" /></svg>
                            </button>
                            <button type="button" @click="nudgeJam($event, row, 'hour', 1)" title="Jam berikutnya" aria-label="Jam berikutnya">
                                <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 8l5 5 5-5" /></svg>
                            </button>
                        </span>
                    </div>
                    <div class="sr-jam-roll-viewport">
                        <span class="sr-jam-roll-band" aria-hidden="true"></span>
                        <div class="sr-jam-roll-track" data-jam-track="hour" @scroll="onJamRoll($event, row, 'hour')">
                            <div class="sr-jam-roll-pad" aria-hidden="true"></div>
                            @foreach ($jamHours as $hour)
                                <div class="sr-jam-roll-item" :class="row.jamHour === '{{ $hour }}' && 'is-active'">{{ $hour }}</div>
                            @endforeach
                            <div class="sr-jam-roll-pad" aria-hidden="true"></div>
                        </div>
                    </div>
                </div>

                <div class="sr-jam-roll-col">
                    <div class="sr-jam-roll-col-head">
                        <span class="sr-jam-roll-title">Menit</span>
                        <span class="sr-jam-roll-nudge">
                            <button type="button" @click="nudgeJam($event, row, 'minute', -1)" title="Menit sebelumnya (5 menit)" aria-label="Menit sebelumnya">
                                <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12l5-5 5 5" /></svg>
                            </button>
                            <button type="button" @click="nudgeJam($event, row, 'minute', 1)" title="Menit berikutnya (5 menit)" aria-label="Menit berikutnya">
                                <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 8l5 5 5-5" /></svg>
                            </button>
                        </span>
                    </div>
                    <div class="sr-jam-roll-viewport">
                        <span class="sr-jam-roll-band" aria-hidden="true"></span>
                        <div class="sr-jam-roll-track" data-jam-track="minute" @scroll="onJamRoll($event, row, 'minute')">
                            <div class="sr-jam-roll-pad" aria-hidden="true"></div>
                            @foreach ($jamMinutes as $menit)
                                <div class="sr-jam-roll-item" :class="row.jamMinute === '{{ $menit }}' && 'is-active'">{{ $menit }}</div>
                            @endforeach
                            <div class="sr-jam-roll-pad" aria-hidden="true"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="sr-jam-quick">
                <p class="sr-jam-quick-title">Pilih Cepat</p>
                <div class="sr-jam-quick-list">
                    @foreach ($jamCepat as $cepat)
                        <button
                            type="button"
                            class="sr-jam-quick-chip"
                            :class="row.jam_mulai === '{{ $cepat }}' && 'is-active'"
                            @click="setJamCepat($event, row, '{{ $cepat }}')"
                        >{{ $cepat }}</button>
                    @endforeach
                </div>
            </div>

            <div class="sr-jam-roll-actions">
                <button type="button" class="sr-jam-picker-btn is-primary" @click="row.jamOpen = false">Selesai</button>
                <button type="button" class="sr-jam-picker-btn" @click="setJamSekarang(row)">Sekarang</button>
                <button type="button" class="sr-jam-picker-btn is-ghost" @click="clearJam(row)">Hapus</button>
            </div>
        </div>
    </template>
</div>
