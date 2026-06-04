@props([
    'name',
    'label' => null,
    'value' => null,
    'placeholder' => null,
    'required' => false,
    'id' => null,
    'min' => null,
    'max' => null,
    'help' => null,
])

@php
    $datepickerConfig = config('ui.datepicker', []);
    $fieldId = $id ?? \Illuminate\Support\Str::slug($name . '-datepicker') . '-' . \Illuminate\Support\Str::random(5);
@endphp

<div x-data="silatarDatepicker(@js([
    'value' => $value,
    'placeholder' => $placeholder ?? ($datepickerConfig['placeholder'] ?? 'Pilih tanggal'),
    'todayLabel' => $datepickerConfig['today_label'] ?? 'Hari ini',
    'clearLabel' => $datepickerConfig['clear_label'] ?? 'Hapus',
    'locale' => $datepickerConfig['locale'] ?? 'en-US',
    'months' => $datepickerConfig['months'] ?? [],
    'weekdays' => $datepickerConfig['weekdays'] ?? [],
    'min' => $min,
    'max' => $max,
]))" x-init="init()" @keydown.escape.window="closePicker()" class="silatar-datepicker">
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
            class="silatar-datepicker-trigger"
            :class="open ? 'is-open' : ''"
            aria-haspopup="dialog"
            :aria-expanded="open.toString()"
        >
            <span class="silatar-datepicker-trigger-icon">
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.5 4.5h9A1.5 1.5 0 0 1 16 6v9A1.5 1.5 0 0 1 14.5 16.5h-9A1.5 1.5 0 0 1 4 15V6A1.5 1.5 0 0 1 5.5 4.5Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 3.5v3M13 3.5v3M4.5 8.5h11" />
                </svg>
            </span>
            <span class="flex-1 text-left" :class="value ? 'text-slate-950' : 'text-slate-400'" x-text="displayText()"></span>
            <span x-show="value" x-cloak class="silatar-datepicker-clear" @click.stop="clearValue()">
                {{ $datepickerConfig['clear_label'] ?? 'Hapus' }}
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
                class="silatar-datepicker-popover"
                :style="popoverStyle"
                :data-name="'{{ $name }}'"
            >
            <div class="silatar-datepicker-popover-header">
                <button type="button" @click="prevMonth()" class="silatar-datepicker-nav" aria-label="Bulan sebelumnya">
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12.5 15 7.5 10l5-5" />
                    </svg>
                </button>

                <div class="flex min-w-0 flex-1 items-center gap-2">
                    <select class="silatar-datepicker-quick-select flex-1" :value="currentMonthIndex" @change="setMonth(Number($event.target.value))">
                        <template x-for="(monthName, index) in months" :key="monthName + index">
                            <option :value="index" x-text="monthName"></option>
                        </template>
                    </select>
                    <input
                        type="number"
                        class="silatar-datepicker-year-input"
                        :value="monthCursor.getFullYear()"
                        @change="setYear($event.target.value)"
                        min="1900"
                        max="2100"
                    >
                </div>

                <button type="button" @click="nextMonth()" class="silatar-datepicker-nav" aria-label="Bulan berikutnya">
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m7.5 5 5 5-5 5" />
                    </svg>
                </button>
            </div>

            <div class="silatar-datepicker-weekdays">
                <template x-for="day in weekdays" :key="day">
                    <span x-text="day"></span>
                </template>
            </div>

            <div class="silatar-datepicker-grid">
                <template x-for="cell in days" :key="cell.key">
                    <button
                        type="button"
                        class="silatar-datepicker-day"
                        :class="{
                            'is-empty': cell.blank,
                            'is-selected': ! cell.blank && isSelected(cell.date),
                            'is-today': ! cell.blank && isToday(cell.date),
                            'is-disabled': cell.blank || ! inRange(cell.date),
                        }"
                        :disabled="cell.blank || ! inRange(cell.date)"
                        @click="! cell.blank && selectDay(cell.date)"
                        x-text="cell.blank ? '' : cell.date.getDate()"
                    ></button>
                </template>
            </div>

            <div class="silatar-datepicker-popover-footer">
                <button type="button" @click="setToday()" class="silatar-datepicker-footer-button">
                    {{ $datepickerConfig['today_label'] ?? 'Hari ini' }}
                </button>
                <button type="button" @click="clearValue()" class="silatar-datepicker-footer-link">
                    {{ $datepickerConfig['clear_label'] ?? 'Hapus' }}
                </button>
            </div>
            </div>
        </template>
    </div>

    @if ($help)
        <p class="mt-2 text-xs leading-5 text-slate-500">{{ $help }}</p>
    @endif
</div>
