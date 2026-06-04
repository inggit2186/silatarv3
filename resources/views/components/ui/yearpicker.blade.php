@props([
    'name',
    'label' => null,
    'value' => null,
    'placeholder' => null,
    'required' => false,
    'id' => null,
    'help' => null,
])

@php
<<<<<<< HEAD
    $fieldId = $id ?? \Illuminate\Support\Str::slug($name . '-yearpicker') . '-' . \Illuminate\Support\Str::random(5);
    $currentYear = (int) date('Y');
=======
    $yearpickerConfig = config('ui.yearpicker', []);
    $fieldId = $id ?? \Illuminate\Support\Str::slug($name . '-yearpicker') . '-' . \Illuminate\Support\Str::random(5);
>>>>>>> 1cdcd39f051e5cf74502037ab3e117ad5b143f87
@endphp

<div x-data="silatarYearpicker(@js([
    'name' => $name,
    'value' => $value,
<<<<<<< HEAD
    'placeholder' => $placeholder ?? 'Pilih tahun',
]))" class="silatar-yearpicker">
=======
    'placeholder' => $placeholder ?? ($yearpickerConfig['placeholder'] ?? 'Pilih tahun'),
    'clearLabel' => $yearpickerConfig['clear_label'] ?? 'Hapus',
    'applyLabel' => $yearpickerConfig['apply_label'] ?? 'Pilih',
    'locale' => $yearpickerConfig['locale'] ?? 'id-ID',
]))" x-init="init()" @keydown.escape.window="closePicker()" class="silatar-monthpicker">
>>>>>>> 1cdcd39f051e5cf74502037ab3e117ad5b143f87
    @if ($label)
        <label for="{{ $fieldId }}" class="mb-2 block text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
            {{ $label }}@if ($required)<span class="text-rose-500"> *</span>@endif
        </label>
    @endif

    <div class="relative">
        <input type="hidden" name="{{ $name }}" x-model="value" @if($required) required @endif>

<<<<<<< HEAD
        <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 pointer-events-none">
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1z"/>
                </svg>
            </span>
            <select
                x-model="value"
                @change="submitForm()"
                class="silatar-yearpicker-select pl-10 pr-10"
            >
                <option value="">-- Pilih Tahun --</option>
                @for($year = $currentYear; $year >= $currentYear - 10; $year--)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endfor
            </select>
            <span class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 pointer-events-none">
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m5 8 5 5 5-5" />
                </svg>
            </span>
        </div>
=======
        <button
            id="{{ $fieldId }}"
            type="button"
            x-ref="trigger"
            @click="togglePicker()"
            class="silatar-monthpicker-trigger"
            :class="open ? 'is-open' : ''"
            aria-haspopup="dialog"
            :aria-expanded="open.toString()"
        >
            <span class="silatar-monthpicker-trigger-icon">
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.5 4.5h9A1.5 1.5 0 0 1 16 6v9A1.5 1.5 0 0 1 14.5 16.5h-9A1.5 1.5 0 0 1 4 15V6A1.5 1.5 0 0 1 5.5 4.5Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 3.5v3M13 3.5v3M4.5 8.5h11" />
                </svg>
            </span>
            <span class="flex-1 text-left" :class="value ? 'text-slate-950' : 'text-slate-400'" x-text="displayText()"></span>
            <span x-show="value" x-cloak class="silatar-monthpicker-clear" @click.stop="clearValue()">
                {{ $yearpickerConfig['clear_label'] ?? 'Hapus' }}
            </span>
            <svg class="h-4 w-4 text-slate-400 transition" :class="open ? 'rotate-180 text-cyan-600' : ''" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="m5 8 5 5 5-5" />
            </svg>
        </button>

        <template x-teleport="body">
            <div
                x-show="open"
                x-cloak
                x-transition.opacity.scale.origin.top.left
                @click.outside="closePicker()"
                class="silatar-monthpicker-popover"
                :style="popoverStyle"
            >
            <div class="silatar-monthpicker-popover-header">
                <button type="button" @click="prevYear()" class="silatar-monthpicker-nav" aria-label="Tahun sebelumnya">
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12.5 15 7.5 10l5-5" />
                    </svg>
                </button>

                <input
                    type="number"
                    class="silatar-monthpicker-year-input"
                    :value="yearCursor"
                    @change="setYear($event.target.value)"
                    min="1900"
                    max="2100"
                >

                <button type="button" @click="nextYear()" class="silatar-monthpicker-nav" aria-label="Tahun berikutnya">
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m7.5 5 5 5-5 5" />
                    </svg>
                </button>
            </div>

            <div class="silatar-monthpicker-grid">
                <template x-for="item in yearItems" :key="item.key">
                    <button
                        type="button"
                        class="silatar-monthpicker-month"
                        :class="isSelected(item.year) ? 'is-selected' : ''"
                        @click="selectYear(item.year)"
                        x-text="item.label"
                    ></button>
                </template>
            </div>

            <div class="silatar-monthpicker-popover-footer">
                <button type="button" @click="clearValue()" class="silatar-monthpicker-footer-link">
                    {{ $yearpickerConfig['clear_label'] ?? 'Hapus' }}
                </button>
                <button type="button" @click="closePicker()" class="silatar-monthpicker-footer-button">
                    {{ $yearpickerConfig['apply_label'] ?? 'Pilih' }}
                </button>
            </div>
            </div>
        </template>
>>>>>>> 1cdcd39f051e5cf74502037ab3e117ad5b143f87
    </div>

    @if ($help)
        <p class="mt-2 text-xs leading-5 text-slate-500">{{ $help }}</p>
    @endif
<<<<<<< HEAD
</div>
=======
</div>
>>>>>>> 1cdcd39f051e5cf74502037ab3e117ad5b143f87
