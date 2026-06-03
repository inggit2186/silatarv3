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
    $fieldId = $id ?? \Illuminate\Support\Str::slug($name . '-yearpicker') . '-' . \Illuminate\Support\Str::random(5);
    $currentYear = (int) date('Y');
@endphp

<div x-data="silatarYearpicker(@js([
    'name' => $name,
    'value' => $value,
    'placeholder' => $placeholder ?? 'Pilih tahun',
]))" class="silatar-yearpicker">
    @if ($label)
        <label for="{{ $fieldId }}" class="mb-2 block text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
            {{ $label }}@if ($required)<span class="text-rose-500"> *</span>@endif
        </label>
    @endif

    <div class="relative">
        <input type="hidden" name="{{ $name }}" x-model="value" @if($required) required @endif>

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
    </div>

    @if ($help)
        <p class="mt-2 text-xs leading-5 text-slate-500">{{ $help }}</p>
    @endif
</div>