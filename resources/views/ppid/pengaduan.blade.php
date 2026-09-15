<x-layouts.ppid-layout title="{{ $page->title }}">
    <x-ppid.nav />

    <main class="ppid-content">
        <div class="ppid-page">
            <div class="ppid-page-breadcrumb">
                <a href="{{ route('ppid') }}">PPID</a>
                <span>/</span>
                <span>{{ $page->title }}</span>
            </div>
            <div class="ppid-page-header" data-reveal>
                <h1 class="ppid-page-title">{{ $page->title }}</h1>
                <p class="ppid-page-subtitle">{{ $page->subtitle ?? 'Sampaikan pengaduan terkait pelayanan' }}</p>
            </div>

            {{-- Instruksi Section --}}
            @if(isset($sectionsByType['instruksi']))
                @php $instruksi = $sectionsByType['instruksi']->first(); @endphp
                <section class="ppid-section" data-reveal>
                    <div class="ppid-section-content">
                        {!! $instruksi->content !!}
                    </div>
                </section>
            @endif

            {{-- Form Fields Section --}}
            @if(isset($sectionsByType['form_fields']))
                @php $form = $sectionsByType['form_fields']->first(); @endphp
                @php $fields = json_decode($form->metadata)->fields ?? []; @endphp
                <section class="ppid-section" data-reveal>
                    <div style="background: white; border: 1px solid rgba(140, 135, 130, 0.15); border: 1px solid oklch(58% 0.06 76 / 0.15); border-radius: 1.5rem; padding: 2rem;">
                        <h2 class="ppid-section-title" style="margin-bottom: 1.5rem;">{{ $form->title ?? 'Formulir Pengaduan' }}</h2>
                        <form>
                            @foreach($fields as $field)
                                <div class="ppid-form-group">
                                    <label class="ppid-form-label">
                                        {{ $field->label }}{{ $field->required ? ' *' : '' }}
                                    </label>
                                    @if($field->type === 'textarea')
                                        <textarea
                                            name="{{ $field->name }}"
                                            class="ppid-form-input"
                                            rows="5"
                                            placeholder="Masukkan {{ strtolower($field->label) }}"
                                            {{ $field->required ? 'required' : '' }}
                                        ></textarea>
                                    @elseif($field->type === 'select')
                                        <select
                                            name="{{ $field->name }}"
                                            class="ppid-form-input"
                                            {{ $field->required ? 'required' : '' }}
                                        >
                                            <option value="">Pilih {{ strtolower($field->label) }}</option>
                                            @foreach($field->options ?? [] as $option)
                                                <option value="{{ $option }}">{{ $option }}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        <input
                                            type="{{ $field->type }}"
                                            name="{{ $field->name }}"
                                            class="ppid-form-input"
                                            placeholder="Masukkan {{ strtolower($field->label) }}"
                                            {{ $field->required ? 'required' : '' }}
                                        >
                                    @endif
                                </div>
                            @endforeach

                            <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                                <button type="submit" class="ppid-btn ppid-btn-primary">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/>
                                    </svg>
                                    Kirim
                                </button>
                                <button type="reset" class="ppid-btn ppid-btn-secondary">Reset</button>
                            </div>
                        </form>
                    </div>
                </section>
            @endif
        </div>
        @include('ppid.partials.footer')
    </main>
</x-layouts.ppid-layout>
