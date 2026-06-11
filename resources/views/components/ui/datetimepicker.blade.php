@props([
    'name',
    'dateName',
    'timeName',
    'label' => null,
    'dateValue' => null,
    'timeValue' => null,
    'placeholder' => null,
    'required' => false,
    'id' => null,
    'min' => null,
    'max' => null,
    'help' => null,
])

@php
    $datepickerConfig = config('ui.datepicker', []);
    $fieldId = $id ?? \Illuminate\Support\Str::slug($name . '-datetimepicker') . '-' . \Illuminate\Support\Str::random(5);
@endphp

<div x-data="silatarDateTimePicker(@js([
    'dateValue' => $dateValue,
    'timeValue' => $timeValue,
    'placeholder' => $placeholder ?? 'Pilih tanggal & waktu',
    'todayLabel' => $datepickerConfig['today_label'] ?? 'Hari ini',
    'clearLabel' => $datepickerConfig['clear_label'] ?? 'Hapus',
    'locale' => $datepickerConfig['locale'] ?? 'id-ID',
    'months' => $datepickerConfig['months'] ?? [],
    'weekdays' => $datepickerConfig['weekdays'] ?? [],
    'min' => $min,
    'max' => $max,
]))" x-init="init()" @keydown.escape.window="closePicker()" class="silatar-datetimepicker">
    @if ($label)
        <label for="{{ $fieldId }}" class="cyber-form-label flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 11v4m-2-2h4" stroke-width="2.5"/>
            </svg>
            {{ $label }}
        </label>
    @endif

    <div class="relative">
        <input type="hidden" name="{{ $dateName }}" x-model="dateValue" @if($required) required @endif>
        <input type="hidden" name="{{ $timeName }}" x-model="timeValue" @if($required) required @endif>

        <button
            id="{{ $fieldId }}"
            type="button"
            x-ref="trigger"
            @click="togglePicker()"
            class="silatar-datetimepicker-trigger w-full"
            :class="open ? 'is-open' : ''"
            aria-haspopup="dialog"
            :aria-expanded="open.toString()"
        >
            <span class="silatar-datetimepicker-trigger-icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </span>
            <span class="flex-1 text-left" :class="hasValue ? 'text-white' : 'text-slate-500'" x-text="displayText()"></span>
            <span x-show="hasValue" x-cloak class="silatar-datetimepicker-clear" @click.stop="clearValue()">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </span>
            <svg class="w-4 h-4 text-cyan-500 transition-transform" :class="open ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="m5 8 5 5 5-5" />
            </svg>
        </button>

        <template x-teleport="body">
            <div
                x-show="open"
                x-cloak
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                @click.outside="closePicker()"
                class="silatar-datetimepicker-popover"
                :style="popoverStyle"
                :data-name="'{{ $name }}'"
            >
            <!-- Tab Navigation -->
            <div class="silatar-datetimepicker-tabs">
                <button
                    type="button"
                    @click="activeTab = 'date'"
                    class="silatar-datetimepicker-tab"
                    :class="activeTab === 'date' ? 'is-active' : ''"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Tanggal
                </button>
                <button
                    type="button"
                    @click="activeTab = 'time'"
                    class="silatar-datetimepicker-tab"
                    :class="activeTab === 'time' ? 'is-active' : ''"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Waktu
                </button>
            </div>

            <!-- Date Section -->
            <div x-show="activeTab === 'date'" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                <div class="silatar-datetimepicker-popover-header">
                    <button type="button" @click="prevMonth()" class="silatar-datetimepicker-nav" aria-label="Bulan sebelumnya">
                        <svg class="w-4 h-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12.5 15 7.5 10l5-5" />
                        </svg>
                    </button>

                    <div class="flex min-w-0 flex-1 items-center gap-2">
                        <select class="silatar-datetimepicker-quick-select" :value="currentMonthIndex" @change="setMonth(Number($event.target.value))">
                            <template x-for="(monthName, index) in months" :key="monthName + index">
                                <option :value="index" x-text="monthName"></option>
                            </template>
                        </select>
                        <input
                            type="number"
                            class="silatar-datetimepicker-year-input"
                            :value="monthCursor.getFullYear()"
                            @change="setYear($event.target.value)"
                            min="1900"
                            max="2100"
                        >
                    </div>

                    <button type="button" @click="nextMonth()" class="silatar-datetimepicker-nav" aria-label="Bulan berikutnya">
                        <svg class="w-4 h-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m7.5 5 5 5-5 5" />
                        </svg>
                    </button>
                </div>

                <div class="silatar-datetimepicker-weekdays">
                    <template x-for="day in weekdays" :key="day">
                        <span x-text="day"></span>
                    </template>
                </div>

                <div class="silatar-datetimepicker-grid">
                    <template x-for="cell in days" :key="cell.key">
                        <button
                            type="button"
                            class="silatar-datetimepicker-day"
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

                <div class="silatar-datetimepicker-popover-footer">
                    <button type="button" @click="setToday()" class="silatar-datetimepicker-footer-button">
                        {{ $datepickerConfig['today_label'] ?? 'Hari ini' }}
                    </button>
                    <button type="button" @click="clearValue()" class="silatar-datetimepicker-footer-link">
                        {{ $datepickerConfig['clear_label'] ?? 'Hapus' }}
                    </button>
                </div>
            </div>

            <!-- Time Section -->
            <div x-show="activeTab === 'time'" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                <div class="silatar-datetimepicker-time-header">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Pilih Waktu</span>
                </div>

                <div class="silatar-datetimepicker-time-grid">
                    <template x-for="time in timeOptions" :key="time">
                        <button
                            type="button"
                            class="silatar-datetimepicker-time-option"
                            :class="{ 'is-selected': timeValue === time }"
                            @click="selectTime(time)"
                            x-text="time + ' WIB'"
                        ></button>
                    </template>
                </div>

                <div class="silatar-datetimepicker-popover-footer">
                    <button type="button" @click="activeTab = 'date'" class="silatar-datetimepicker-footer-link">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali
                    </button>
                    <button type="button" @click="clearValue()" class="silatar-datetimepicker-footer-link text-rose-400 hover:text-rose-300">
                        {{ $datepickerConfig['clear_label'] ?? 'Hapus' }}
                    </button>
                </div>
            </div>
            </div>
        </template>
    </div>

    @if ($help)
        <p class="mt-2 text-xs leading-5 text-slate-500">{{ $help }}</p>
    @endif
</div>
