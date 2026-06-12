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
    $monthpickerConfig = config('ui.monthpicker', []);
    $fieldId = $id ?? \Illuminate\Support\Str::slug($name . '-monthpicker') . '-' . \Illuminate\Support\Str::random(5);
@endphp

<div x-data="silatarMonthpicker(@js([
    'name' => $name,
    'value' => $value,
    'placeholder' => $placeholder ?? ($monthpickerConfig['placeholder'] ?? 'Pilih bulan'),
    'clearLabel' => $monthpickerConfig['clear_label'] ?? 'Hapus',
    'applyLabel' => $monthpickerConfig['apply_label'] ?? 'Pilih',
    'locale' => $monthpickerConfig['locale'] ?? 'en-US',
    'months' => $monthpickerConfig['months'] ?? [],
    'noSubmit' => true,
]))" x-init="init()" @keydown.escape.window="closePicker()" class="silatar-monthpicker">
    @if ($label)
        <label for="{{ $fieldId }}" class="mb-2 block text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
            {{ $label }}@if ($required)<span class="text-rose-500"> *</span>@endif
        </label>
    @endif

    <div class="relative">
        <input type="hidden" name="{{ $name }}" x-model="value" @if($required) required @endif>

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
                {{ $monthpickerConfig['clear_label'] ?? 'Hapus' }}
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
                <template x-for="month in monthItems" :key="month.key">
                    <button
                        type="button"
                        class="silatar-monthpicker-month"
                        :class="isSelected(month.index) ? 'is-selected' : ''"
                        @click="selectMonth(month.index)"
                        x-text="month.label"
                    ></button>
                </template>
            </div>

            <div class="silatar-monthpicker-popover-footer">
                <button type="button" @click="clearValue()" class="silatar-monthpicker-footer-link">
                    {{ $monthpickerConfig['clear_label'] ?? 'Hapus' }}
                </button>
                <button type="button" @click="closePicker()" class="silatar-monthpicker-footer-button">
                    {{ $monthpickerConfig['apply_label'] ?? 'Pilih' }}
                </button>
            </div>
            </div>
        </template>
    </div>

    @if ($help)
        <p class="mt-2 text-xs leading-5 text-slate-500">{{ $help }}</p>
    @endif
</div>
