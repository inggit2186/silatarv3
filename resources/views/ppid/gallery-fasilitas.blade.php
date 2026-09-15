<x-layouts.ppid-layout title="{{ $page->title }}">
    <x-ppid.nav />

    <main class="ppid-content">
        <div class="ppid-page">
            <div class="ppid-page-breadcrumb">
                <a href="{{ route('ppid') }}">PPID</a>
                <span>/</span>
                <span>Gallery</span>
                <span>/</span>
                <span>Fasilitas</span>
            </div>
            <div class="ppid-page-header" data-reveal>
                <h1 class="ppid-page-title">{{ $page->title }}</h1>
                <p class="ppid-page-subtitle">{{ $page->subtitle ?? 'Dokumentasi fasilitas untuk masyarakat' }}</p>
            </div>

            <section class="ppid-section" data-reveal>
                @if($galleryItems->count() > 0)
                    <div class="ppid-grid">
                        @foreach($galleryItems as $item)
                            <div class="ppid-card" style="padding: 0; overflow: hidden;">
                                <div style="aspect-ratio: 16/10; background: linear-gradient(135deg, rgba(10, 100, 150, 0.2), rgba(10, 100, 150, 0.1)), linear-gradient(135deg, oklch(8% 0.15 190 / 0.2), oklch(8% 0.15 190 / 0.1)); display: flex; align-items: center; justify-content: center;">
                                    @if($item->image_path)
                                        <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="color: var(--ppid-primary);">
                                            <rect x="3" y="3" width="18" height="18" rx="2"/>
                                            <circle cx="8.5" cy="8.5" r="1.5"/>
                                            <path d="M21 15l-5-5L5 21"/>
                                        </svg>
                                    @endif
                                </div>
                                <div style="padding: 1.25rem;">
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
                        <p>Belum ada gambar fasilitas yang tersedia.</p>
                    </div>
                @endif
            </section>
        </div>
        @include('ppid.partials.footer')
    </main>
</x-layouts.ppid-layout>
