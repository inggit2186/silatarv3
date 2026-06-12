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
    $yearpickerConfig = config('ui.yearpicker', []);
    $fieldId = $id ?? \Illuminate\Support\Str::slug($name . '-yearpicker') . '-' . \Illuminate\Support\Str::random(5);
@endphp

<div x-data="silatarYearpicker(@js([
    'name' => $name,
    'value' => $value,
    'placeholder' => $placeholder ?? ($yearpickerConfig['placeholder'] ?? 'Pilih tahun'),
    'clearLabel' => $yearpickerConfig['clear_label'] ?? 'Hapus',
    'applyLabel' => $yearpickerConfig['apply_label'] ?? 'Pilih',
    'locale' => $yearpickerConfig['locale'] ?? 'id-ID',
]))" x-init="init()" @keydown.escape.window="closePicker()" class="relative">
    @if ($label)
        <label for="{{ $fieldId }}" class="mb-2 flex items-center gap-2 font-mono text-xs font-semibold uppercase tracking-wider text-cyan-400/70">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            {{ $label }}@if ($required)<span class="text-rose-400">*</span>@endif
        </label>
    @endif

    <div class="relative">
        <input type="hidden" name="{{ $name }}" x-model="value" @if($required) required @endif>

        <button
            id="{{ $fieldId }}"
            type="button"
            x-ref="trigger"
            @click="togglePicker()"
            class="group relative w-full rounded-xl border border-cyan-500/30 bg-slate-900/80 px-4 py-3.5 text-left transition-all hover:border-cyan-400/50 focus:border-cyan-400 focus:shadow-[0_0_20px_rgba(0,212,255,0.2)] focus:outline-none"
            :class="open ? 'border-cyan-400 shadow-[0_0_20px_rgba(0,212,255,0.3)]' : ''"
            aria-haspopup="dialog"
            :aria-expanded="open.toString()"
        >
            {{-- Icon --}}
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                <svg class="h-5 w-5 text-cyan-500/50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>

            {{-- Text --}}
            <span class="block pl-10 pr-23 font-mono text-sm" :class="value ? 'text-cyan-300' : 'text-slate-500'" x-text="displayText()"></span>

            {{-- Right side buttons --}}
            <div class="absolute inset-y-0 right-0 flex items-center gap-2 pr-3">
                <span x-show="value" x-cloak @click.stop="clearValue()" class="cursor-pointer rounded-full border border-rose-500/30 bg-rose-500/10 px-3 py-1.5 font-mono text-xs font-semibold text-rose-400 transition-all hover:border-rose-400/50 hover:bg-rose-500/20">
                    Hapus
                </span>
                <svg class="h-5 w-5 flex-none text-cyan-500 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>
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
                class="fixed z-[100] overflow-hidden rounded-2xl border border-cyan-500/30 bg-slate-900/95 shadow-[0_0_60px_rgba(0,212,255,0.3)] backdrop-blur-xl"
                :style="popoverStyle"
            >
                {{-- Glow effect --}}
                <div class="absolute -inset-0.5 rounded-2xl bg-gradient-to-r from-cyan-500/10 via-transparent to-cyan-500/10 pointer-events-none"></div>

                <div class="relative p-5">
                    {{-- Header --}}
                    <div class="mb-5 flex items-center justify-between gap-6">
                        <button type="button" @click="prevYear()" class="group flex h-12 w-12 items-center justify-center rounded-xl border border-cyan-500/30 bg-slate-800/50 text-cyan-400 transition-all hover:border-cyan-400/50 hover:bg-cyan-500/20 hover:shadow-[0_0_15px_rgba(0,212,255,0.3)]">
                            <svg class="h-6 w-6 transition-transform group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>

                        <input
                            type="number"
                            class="w-36 rounded-xl border border-cyan-500/30 bg-slate-800/80 px-5 py-3.5 text-center font-mono text-xl font-bold text-cyan-300 shadow-[inset_0_2px_4px_rgba(0,0,0,0.3)] transition focus:border-cyan-400 focus:shadow-[0_0_20px_rgba(0,212,255,0.2),inset_0_2px_4px_rgba(0,0,0,0.3)] focus:outline-none"
                            :value="yearCursor"
                            @change="setYear($event.target.value)"
                            min="1900"
                            max="2100"
                        >

                        <button type="button" @click="nextYear()" class="group flex h-12 w-12 items-center justify-center rounded-xl border border-cyan-500/30 bg-slate-800/50 text-cyan-400 transition-all hover:border-cyan-400/50 hover:bg-cyan-500/20 hover:shadow-[0_0_15px_rgba(0,212,255,0.3)]">
                            <svg class="h-6 w-6 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Year Grid --}}
                    <div class="grid grid-cols-3 gap-3">
                        <template x-for="item in yearItems" :key="item.key">
                            <button
                                type="button"
                                class="relative rounded-xl border px-4 py-4 font-mono text-sm font-semibold transition-all"
                                :class="isSelected(item.year)
                                    ? 'border-cyan-400 bg-gradient-to-br from-cyan-500/30 to-cyan-600/30 text-white shadow-[0_0_20px_rgba(0,212,255,0.4)]'
                                    : 'border-cyan-500/20 bg-slate-800/50 text-cyan-300 hover:border-cyan-400/50 hover:bg-cyan-500/20'"
                                @click="selectYear(item.year)"
                            >
                                <span x-text="item.label"></span>
                                {{-- Selected indicator --}}
                                <span x-show="isSelected(item.year)" class="absolute -right-1.5 -top-1.5 flex h-5 w-5 items-center justify-center rounded-full bg-cyan-400 shadow-[0_0_10px_rgba(0,212,255,0.5)]">
                                    <svg class="h-3 w-3 text-slate-950" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </span>
                            </button>
                        </template>
                    </div>

                    {{-- Footer --}}
                    <div class="mt-5 flex items-center justify-between gap-4 border-t border-cyan-500/20 pt-5">
                        <button type="button" @click="clearValue()" class="flex items-center gap-2.5 rounded-xl border border-rose-500/30 bg-rose-500/10 px-5 py-3 font-mono text-sm font-semibold text-rose-400 transition-all hover:border-rose-400/50 hover:bg-rose-500/20">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Hapus
                        </button>
                        <button type="button" @click="closePicker()" class="flex items-center gap-2.5 rounded-xl bg-gradient-to-r from-cyan-600 to-cyan-500 px-6 py-3 font-mono text-sm font-bold uppercase tracking-wider text-white shadow-[0_0_20px_rgba(0,212,255,0.3)] transition-all hover:shadow-[0_0_30px_rgba(0,212,255,0.5)]">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            Pilih
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>

    @if ($help)
        <p class="mt-2 font-mono text-xs text-cyan-100/50">{{ $help }}</p>
    @endif
</div>
