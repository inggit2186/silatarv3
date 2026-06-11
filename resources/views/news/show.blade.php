<x-layouts.app title="{{ $news->title }}">
    <main class="relative overflow-x-hidden">
        <!-- Navigation -->
        <nav class="bg-slate-950/90 backdrop-blur-md border-b border-cyan-500/20 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6">
                <div class="flex items-center justify-between h-16">
                    <a href="{{ route('home') }}" class="flex items-center gap-3">
                        <img src="{{ asset('favicon.webp') }}" alt="SILATAR" class="h-10 w-10 rounded-xl object-cover">
                        <div>
                            <p class="text-sm font-bold uppercase tracking-wider text-cyan-400">SILATAR</p>
                            <p class="text-[9px] text-slate-500">Kemenag Tanah Datar</p>
                        </div>
                    </a>
                    <div class="flex items-center gap-4">
                        <a href="{{ route('home') }}" class="flex items-center gap-2 px-4 py-2 bg-cyan-500/10 border border-cyan-500/30 rounded-lg text-cyan-400 text-xs font-semibold hover:bg-cyan-500/20 transition-all">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Article Content -->
        <article class="relative py-10 md:py-16">
            <div class="max-w-4xl mx-auto px-4 sm:px-6">

                <!-- Breadcrumb -->
                <div class="flex items-center gap-2 mb-8 text-xs text-slate-500">
                    <a href="{{ route('home') }}" class="hover:text-cyan-400 transition-colors flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        Beranda
                    </a>
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    <span class="text-cyan-400">{{ $news->category }}</span>
                </div>

                <!-- Featured Image (Full Width, Not Cropped) -->
                @if($news->image)
                <figure class="mb-10 rounded-2xl overflow-hidden border border-slate-800 bg-slate-900">
                    <img src="{{ asset('storage/' . $news->image) }}" alt="{{ $news->title }}" class="w-full h-auto max-h-[500px] object-contain bg-slate-900">
                </figure>
                @endif

                <!-- Main Content Card -->
                <div class="bg-slate-900/95 backdrop-blur-sm rounded-2xl border border-slate-800 p-6 md:p-10">
                    <!-- Category & Date -->
                    <div class="flex flex-wrap items-center gap-3 mb-6">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-cyan-500/20 border border-cyan-500/30 rounded-full text-cyan-400 text-xs font-bold">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                            {{ $news->category }}
                        </span>
                        @if($news->publish_date)
                        <span class="inline-flex items-center gap-1.5 text-slate-400 text-xs">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            {{ \Carbon\Carbon::parse($news->publish_date)->format('d F Y, H:i') }} WIB
                        </span>
                        @endif
                    </div>

                    <!-- Title -->
                    <h1 class="text-xl md:text-2xl lg:text-3xl font-bold text-white mb-6 leading-snug">
                        <center>{{ $news->title }}</center>
                    </h1>

                    <!-- Excerpt / Lead -->
                    @if($news->excerpt)
                    <div class="relative pl-4 mb-8 border-l-4 border-cyan-500/50 bg-slate-800/50 rounded-r-lg p-4">
                        <p class="text-slate-300 text-sm md:text-base"><i>"{{ $news->excerpt }}"</i></p>
                    </div>
                    @endif

                    <!-- Team Info Cards -->
                    @if($news->writer || $news->editor || $news->photographer)
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-8">
                        @if($news->writer)
                        <div class="flex items-center gap-3 p-3 bg-slate-800/60 rounded-xl border border-slate-700/50">
                            <div class="w-9 h-9 rounded-full bg-cyan-500/20 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase tracking-wider text-slate-500">Penulis</p>
                                <p class="text-sm font-medium text-white">{{ $news->writer }}</p>
                            </div>
                        </div>
                        @endif
                        @if($news->editor)
                        <div class="flex items-center gap-3 p-3 bg-slate-800/60 rounded-xl border border-slate-700/50">
                            <div class="w-9 h-9 rounded-full bg-purple-500/20 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase tracking-wider text-slate-500">Editor</p>
                                <p class="text-sm font-medium text-white">{{ $news->editor }}</p>
                            </div>
                        </div>
                        @endif
                        @if($news->photographer)
                        <div class="flex items-center gap-3 p-3 bg-slate-800/60 rounded-xl border border-slate-700/50">
                            <div class="w-9 h-9 rounded-full bg-amber-500/20 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase tracking-wider text-slate-500">Fotografer</p>
                                <p class="text-sm font-medium text-white">{{ $news->photographer }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- Divider -->
                    <div class="flex items-center gap-4 mb-8">
                        <div class="flex-1 h-px bg-gradient-to-r from-transparent via-slate-700 to-transparent"></div>
                        <svg class="w-5 h-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V9a2 2 0 012-2h2a2 2 0 012 2v9a2 2 0 01-2 2h-2z"/></svg>
                        <div class="flex-1 h-px bg-gradient-to-r from-transparent via-slate-700 to-transparent"></div>
                    </div>

                    <!-- Content - Fixed Line Height & Smaller Font -->
                    <div class="prose prose-invert max-w-none">
                        <div class="text-slate-300 text-sm md:text-[15px] leading-[1.8] md:leading-[1.9] whitespace-pre-wrap">
                            {!! $news->content !!}
                        </div>
                    </div>

                    <!-- Share Section -->
                    <div class="mt-10 pt-8 border-t border-slate-800">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <span class="text-xs text-slate-500">Bagikan:</span>
                                <div class="flex items-center gap-2">
                                    <a href="#" class="w-9 h-9 flex items-center justify-center rounded-lg bg-slate-800 border border-slate-700 text-slate-400 hover:text-[#1DA1F2] hover:border-[#1DA1F2]/30 transition-all" title="Twitter/X">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                    </a>
                                    <a href="#" class="w-9 h-9 flex items-center justify-center rounded-lg bg-slate-800 border border-slate-700 text-slate-400 hover:text-[#1877F2] hover:border-[#1877F2]/30 transition-all" title="Facebook">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                    </a>
                                    <a href="#" class="w-9 h-9 flex items-center justify-center rounded-lg bg-slate-800 border border-slate-700 text-slate-400 hover:text-[#25D366] hover:border-[#25D366]/30 transition-all" title="WhatsApp">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                    </a>
                                    <button onclick="copyToClipboard()" class="w-9 h-9 flex items-center justify-center rounded-lg bg-slate-800 border border-slate-700 text-slate-400 hover:text-cyan-400 hover:border-cyan-500/30 transition-all" title="Salin Link">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                    </button>
                                </div>
                            </div>
                            <a href="{{ route('home') }}" class="flex items-center gap-2 px-4 py-2 bg-slate-800 border border-slate-700 rounded-lg text-slate-400 text-xs hover:text-cyan-400 hover:border-cyan-500/30 transition-all">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                Berita Lainnya
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </article>

        <!-- Related News -->
        @if($relatedNews->count() > 0)
        <section class="py-12 bg-gradient-to-b from-slate-950 to-slate-900">
            <div class="max-w-7xl mx-auto px-4 sm:px-6">
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-1 h-10 bg-gradient-to-b from-cyan-400 to-cyan-600 rounded-full"></div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V9a2 2 0 012-2h2a2 2 0 012 2v9a2 2 0 01-2 2h-2z"/></svg>
                        <h2 class="text-lg font-bold uppercase tracking-wider text-white">Berita Terkait</h2>
                    </div>
                </div>
                <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                    @foreach($relatedNews as $related)
                    <a href="{{ route('news.show', $related->slug ?? $related->id) }}" class="group relative rounded-xl border border-slate-800 bg-slate-900/80 overflow-hidden hover:border-cyan-500/30 hover:shadow-[0_0_40px_rgba(0,212,255,0.1)] transition-all duration-500">
                        <div class="relative aspect-[16/9] overflow-hidden">
                            @if($related->image)
                            <img src="{{ asset('storage/' . $related->image) }}" alt="{{ $related->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" />
                            @else
                            <img src="{{ asset('assets/img/template/banner-02.png') }}" alt="{{ $related->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" />
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/40 to-transparent"></div>
                            <div class="absolute top-3 left-3">
                                <span class="px-2.5 py-1 bg-cyan-500/90 backdrop-blur-sm text-slate-900 text-[10px] font-bold uppercase tracking-wider rounded-lg">
                                    {{ $related->category }}
                                </span>
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-white text-sm group-hover:text-cyan-300 transition-colors line-clamp-2 leading-snug mb-2">{{ $related->title }}</h3>
                            <p class="text-xs text-slate-500 line-clamp-2">{{ $related->excerpt }}</p>
                            <div class="flex items-center gap-2 mt-3 text-[11px] text-slate-600">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                {{ $related->publish_date ? \Carbon\Carbon::parse($related->publish_date)->format('d M Y') : '' }}
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
        </section>
        @endif

        <!-- Footer -->
        <footer class="relative border-t border-slate-800 bg-slate-950 py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6">
                <div class="flex flex-col items-center justify-between gap-6 md:flex-row">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('favicon.webp') }}" alt="SILATAR" class="h-10 w-10 rounded-lg object-cover">
                        <div>
                            <p class="text-sm font-bold uppercase tracking-wider text-cyan-400">SILATAR</p>
                            <p class="text-[9px] text-slate-500 uppercase tracking-widest">Kankemenag Tanah Datar</p>
                        </div>
                    </div>
                    <p class="text-xs uppercase tracking-wider text-slate-600 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        &copy; {{ date('Y') }} SILATAR - Kementerian Agama Tanah Datar
                    </p>
                </div>
            </div>
        </footer>
    </main>

    <script>
        function copyToClipboard() {
            navigator.clipboard.writeText(window.location.href).then(() => {
                const toast = document.createElement('div');
                toast.className = 'fixed bottom-4 right-4 z-50 px-5 py-2.5 bg-emerald-500 text-white rounded-xl shadow-lg flex items-center gap-2 text-sm font-medium';
                toast.innerHTML = '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> Link berhasil disalin!';
                document.body.appendChild(toast);
                setTimeout(() => toast.remove(), 3000);
            });
        }
    </script>
</x-layouts.app>
