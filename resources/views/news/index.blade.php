<x-layouts.app title="Semua Berita - SILATAR">
    <main class="min-h-screen bg-gradient-to-b from-slate-950 to-slate-900 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-2">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 px-3 py-1.5 bg-cyan-500/10 border border-cyan-500/30 rounded-full text-cyan-400 font-mono text-xs hover:bg-cyan-500/20 transition-all">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        Kembali
                    </a>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-1 h-12 bg-gradient-to-b from-cyan-400 to-cyan-600 rounded-full"></div>
                    <div>
                        <h1 class="font-mono text-3xl font-black uppercase tracking-wider text-white">Semua Berita</h1>
                        <p class="font-mono text-sm text-slate-400 mt-1">Arsip berita dan informasi terkini</p>
                    </div>
                </div>
            </div>

            <!-- Category Filter -->
            @if($categories->count() > 0)
            <div class="mb-8 flex flex-wrap items-center gap-3">
                <a href="{{ route('news.index') }}"
                   class="px-4 py-2 rounded-full font-mono text-xs font-bold uppercase tracking-wider transition-all {{ !$selectedCategory ? 'bg-cyan-500 text-slate-900' : 'bg-slate-800 text-slate-400 border border-slate-700 hover:bg-slate-700 hover:text-white' }}">
                    Semua
                </a>
                @foreach($categories as $category)
                <a href="{{ route('news.index', ['category' => $category]) }}"
                   class="px-4 py-2 rounded-full font-mono text-xs font-bold uppercase tracking-wider transition-all {{ $selectedCategory === $category ? 'bg-cyan-500 text-slate-900' : 'bg-slate-800 text-slate-400 border border-slate-700 hover:bg-slate-700 hover:text-white' }}">
                    {{ $category }}
                </a>
                @endforeach
            </div>
            @endif

            <!-- News Grid -->
            @if($news->count() > 0)
            <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                @foreach($news as $item)
                <article class="group relative rounded-xl border border-cyan-500/20 bg-slate-900/80 overflow-hidden hover:border-cyan-400/50 hover:shadow-[0_0_30px_rgba(0,212,255,0.15)] transition-all duration-300">
                    <a href="{{ route('news.show', $item->slug ?? $item->id) }}">
                        <div class="relative aspect-[16/9] overflow-hidden">
                            @if($item->image)
                            <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" class="h-full w-full object-cover group-hover:scale-110 transition-transform duration-500" />
                            @else
                            <img src="{{ asset('assets/img/template/banner-02.png') }}" alt="{{ $item->title }}" class="h-full w-full object-cover group-hover:scale-110 transition-transform duration-500" />
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-transparent to-transparent"></div>
                            <div class="absolute top-3 left-3 flex items-center gap-2">
                                <span class="px-2.5 py-1 bg-cyan-500/90 text-slate-900 font-mono text-[10px] font-bold uppercase tracking-wider rounded flex items-center gap-1.5">
                                    {{ $item->category }}
                                </span>
                            </div>
                        </div>
                        <div class="p-4">
                            <div class="flex items-center gap-2 mb-2">
                                <svg class="w-3.5 h-3.5 text-cyan-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span class="font-mono text-[11px] text-slate-400">{{ $item->publish_date ? \Carbon\Carbon::parse($item->publish_date)->format('d M Y') : '' }}</span>
                            </div>
                            <h3 class="font-mono text-sm font-bold uppercase tracking-wider text-white group-hover:text-cyan-300 transition-colors line-clamp-2 leading-snug">{{ $item->title }}</h3>
                            <p class="mt-2 text-xs text-slate-400 line-clamp-2 leading-relaxed">{{ $item->excerpt }}</p>
                            <div class="mt-3 flex items-center gap-1 text-cyan-400 font-mono text-xs font-semibold uppercase tracking-wider">
                                Baca Selengkapnya
                                <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </div>
                        </div>
                    </a>
                </article>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($news->hasPages())
            <div class="mt-10 flex justify-center">
                <nav class="flex items-center gap-2">
                    @if($news->onFirstPage())
                    <span class="flex items-center gap-2 px-4 py-2 bg-slate-800/50 border border-slate-700 rounded-lg text-slate-500 font-mono text-sm cursor-not-allowed">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        Prev
                    </span>
                    @else
                    <a href="{{ $news->previousPageUrl() }}" class="flex items-center gap-2 px-4 py-2 bg-cyan-500/10 border border-cyan-500/30 rounded-lg text-cyan-400 font-mono text-sm hover:bg-cyan-500/20 transition-all">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        Prev
                    </a>
                    @endif

                    <div class="flex items-center gap-1">
                        @foreach($news->getUrlRange(1, $news->lastPage()) as $page => $url)
                            @if($page == $news->currentPage())
                            <span class="w-10 h-10 flex items-center justify-center bg-cyan-500 text-slate-900 font-mono text-sm font-bold rounded-lg">{{ $page }}</span>
                            @elseif($page <= 3 || $page > $news->lastPage() - 2 || abs($page - $news->currentPage()) <= 1)
                            <a href="{{ $url }}" class="w-10 h-10 flex items-center justify-center bg-slate-800 border border-slate-700 text-slate-400 font-mono text-sm hover:bg-slate-700 hover:text-white rounded-lg transition-all">{{ $page }}</a>
                            @elseif($page == 4 || $page == $news->lastPage() - 3)
                            <span class="text-slate-500 font-mono text-sm">...</span>
                            @endif
                        @endforeach
                    </div>

                    @if($news->hasMorePages())
                    <a href="{{ $news->nextPageUrl() }}" class="flex items-center gap-2 px-4 py-2 bg-cyan-500/10 border border-cyan-500/30 rounded-lg text-cyan-400 font-mono text-sm hover:bg-cyan-500/20 transition-all">
                        Next
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </a>
                    @else
                    <span class="flex items-center gap-2 px-4 py-2 bg-slate-800/50 border border-slate-700 rounded-lg text-slate-500 font-mono text-sm cursor-not-allowed">
                        Next
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </span>
                    @endif
                </nav>
            </div>
            @endif

            @else
            <div class="text-center py-16 rounded-xl border border-slate-700/30 bg-slate-900/40">
                <svg class="w-20 h-20 mx-auto text-slate-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V9a2 2 0 012-2h2a2 2 0 012 2v9a2 2 0 01-2 2h-2z"/></svg>
                <h3 class="font-mono text-xl font-bold text-slate-400 mb-2">Belum Ada Berita</h3>
                <p class="font-mono text-sm text-slate-500">Tidak ada berita yang ditemukan</p>
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 mt-6 px-6 py-3 bg-cyan-500/10 border border-cyan-500/30 rounded-xl text-cyan-400 font-mono text-sm hover:bg-cyan-500/20 transition-all">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    Kembali ke Beranda
                </a>
            </div>
            @endif
        </div>
    </main>
</x-layouts.app>