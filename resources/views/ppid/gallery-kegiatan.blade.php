<x-layouts.ppid-layout title="{{ $page->title }}">
    <x-ppid.nav />

    <main class="ppid-content">
        <div class="ppid-page">
            <div class="ppid-page-breadcrumb">
                <a href="{{ route('ppid') }}">PPID</a>
                <span>/</span>
                <span>Gallery</span>
                <span>/</span>
                <span>Kegiatan</span>
            </div>
            <div class="ppid-page-header" data-reveal>
                <h1 class="ppid-page-title">{{ $page->title }}</h1>
                <p class="ppid-page-subtitle">{{ $page->subtitle ?? 'Dokumentasi kegiatan PPID' }}</p>
            </div>

            <section class="ppid-section" data-reveal>
                @if($galleryItems->count() > 0)
                    <div class="ppid-grid">
                        @foreach($galleryItems as $item)
                            <div class="ppid-card" style="padding: 0; overflow: hidden;">
                                <div style="aspect-ratio: 16/10; background: linear-gradient(135deg, rgba(212, 168, 83, 0.2), rgba(212, 168, 83, 0.1)), linear-gradient(135deg, oklch(68% 0.145 74 / 0.2), oklch(68% 0.145 74 / 0.1)); display: flex; align-items: center; justify-content: center;">
                                    @if($item->image_path)
                                        <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="color: var(--gold);">
                                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                        </svg>
                                    @endif
                                </div>
                                <div style="padding: 1.25rem;">
                                    <span style="font-family: var(--font-mono); font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.1em; color: var(--ppid-primary);">
                                        {{ $item->created_at->format('F Y') }}
                                    </span>
                                    <h3 class="ppid-card-title">{{ $item->title }}</h3>
                                    @if($item->description)
                                        <p class="ppid-card-text">{{ $item->description }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="text-align: center; padding: 3rem; color: var(--text-muted);">
                        <p>Belum ada dokumentasi kegiatan yang tersedia.</p>
                    </div>
                @endif
            </section>
        </div>
        @include('ppid.partials.footer')
    </main>
</x-layouts.ppid-layout>
