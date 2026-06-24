<x-admin.layouts.app title="{{ $title }}">
    <div class="min-h-screen">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-white flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-cyan-500/20 border border-cyan-500/30">
                            <svg class="h-5 w-5 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V9a2 2 0 012-2h2a2 2 0 012 2v9a2 2 0 01-2 2h-2z"/>
                            </svg>
                        </div>
                        Manajemen Berita
                    </h1>
                    <p class="mt-2 text-sm text-slate-400">Kelola semua berita dan pengumuman portal SILATAR</p>
                </div>
                @if($canCreate)
                <a href="{{ route('admin.news.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-cyan-600 to-cyan-500 px-5 py-3 font-mono text-sm font-bold uppercase tracking-wider text-white shadow-[0_0_25px_rgba(0,212,255,0.4)] transition-all hover:shadow-[0_0_35px_rgba(0,212,255,0.6)] hover:-translate-y-0.5">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Berita
                </a>
                @endif
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
            <div class="rounded-xl border border-cyan-500/30 bg-slate-900/80 p-5">
                <div class="flex items-center gap-3 mb-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-cyan-500/20">
                        <svg class="h-5 w-5 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V9a2 2 0 012-2h2a2 2 0 012 2v9a2 2 0 01-2 2h-2z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-mono uppercase tracking-wider text-slate-400">Total Berita</span>
                </div>
                <p class="font-mono text-3xl font-black text-cyan-400">{{ $stats['total'] }}</p>
            </div>
            <div class="rounded-xl border border-emerald-500/30 bg-slate-900/80 p-5">
                <div class="flex items-center gap-3 mb-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-500/20">
                        <svg class="h-5 w-5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <span class="text-xs font-mono uppercase tracking-wider text-slate-400">Published</span>
                </div>
                <p class="font-mono text-3xl font-black text-emerald-400">{{ $stats['published'] }}</p>
            </div>
            <div class="rounded-xl border border-amber-500/30 bg-slate-900/80 p-5">
                <div class="flex items-center gap-3 mb-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-500/20">
                        <svg class="h-5 w-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-mono uppercase tracking-wider text-slate-400">Draft</span>
                </div>
                <p class="font-mono text-3xl font-black text-amber-400">{{ $stats['draft'] }}</p>
            </div>
            <div class="rounded-xl border border-purple-500/30 bg-slate-900/80 p-5">
                <div class="flex items-center gap-3 mb-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-purple-500/20">
                        <svg class="h-5 w-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                        </svg>
                    </div>
                    <span class="text-xs font-mono uppercase tracking-wider text-slate-400">Archived</span>
                </div>
                <p class="font-mono text-3xl font-black text-purple-400">{{ $stats['archived'] }}</p>
            </div>
            <div class="rounded-xl border border-rose-500/30 bg-slate-900/80 p-5">
                <div class="flex items-center gap-3 mb-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-rose-500/20">
                        <svg class="h-5 w-5 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-mono uppercase tracking-wider text-slate-400">Total View</span>
                </div>
                <p class="font-mono text-3xl font-black text-rose-400">{{ number_format($stats['total_views'] ?? 0) }}</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="mb-6 rounded-xl border border-cyan-500/20 bg-slate-900/60 p-4">
            <form method="GET" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label class="mb-2 block text-xs font-mono uppercase tracking-wider text-slate-400">Pencarian</label>
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul berita..." class="w-full rounded-lg border border-cyan-500/30 bg-slate-900/80 py-2.5 pl-10 pr-4 font-mono text-sm text-white placeholder-slate-500 focus:border-cyan-400 focus:outline-none focus:shadow-[0_0_15px_rgba(0,212,255,0.3)]">
                    </div>
                </div>
                <div class="w-40">
                    <label class="mb-2 block text-xs font-mono uppercase tracking-wider text-slate-400">Kategori</label>
                    <select name="category" class="w-full rounded-lg border border-cyan-500/30 bg-slate-900/80 py-2.5 px-3 font-mono text-sm text-white focus:border-cyan-400 focus:outline-none">
                        <option value="">Semua</option>
                        @foreach($categories as $key => $label)
                        <option value="{{ $key }}" {{ request('category') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w-40">
                    <label class="mb-2 block text-xs font-mono uppercase tracking-wider text-slate-400">Status</label>
                    <select name="status" class="w-full rounded-lg border border-cyan-500/30 bg-slate-900/80 py-2.5 px-3 font-mono text-sm text-white focus:border-cyan-400 focus:outline-none">
                        <option value="">Semua</option>
                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="rounded-lg border border-cyan-500/30 bg-cyan-500/20 px-4 py-2.5 font-mono text-sm font-semibold uppercase tracking-wider text-cyan-400 hover:bg-cyan-500/30 transition-all">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                    </button>
                    <a href="{{ route('admin.news.index') }}" class="rounded-lg border border-slate-500/30 bg-slate-500/20 px-4 py-2.5 font-mono text-sm font-semibold uppercase tracking-wider text-slate-400 hover:bg-slate-500/30 transition-all">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- News List -->
        <div class="space-y-4">
            @forelse($news as $item)
            <div class="group rounded-xl border border-cyan-500/20 bg-slate-900/80 p-5 hover:border-cyan-400/50 hover:shadow-[0_0_30px_rgba(0,212,255,0.1)] transition-all duration-300">
                <div class="flex flex-col md:flex-row gap-4">
                    @if($item->image)
                    <div class="flex-shrink-0">
                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" class="h-32 w-full rounded-lg object-cover md:w-48">
                    </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-3 mb-2">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full font-mono text-[10px] font-bold uppercase tracking-wider
                                @if($item->category == 'featured') bg-cyan-500/20 text-cyan-400 border border-cyan-500/30
                                @elseif($item->category == 'pengumuman') bg-purple-500/20 text-purple-400 border border-purple-500/30
                                @elseif($item->category == 'kegiatan') bg-emerald-500/20 text-emerald-400 border border-emerald-500/30
                                @elseif($item->category == 'layanan') bg-amber-500/20 text-amber-400 border border-amber-500/30
                                @else bg-blue-500/20 text-blue-400 border border-blue-500/30 @endif">
                                @if($item->category == 'featured')
                                <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                                @elseif($item->category == 'pengumuman')
                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                                @elseif($item->category == 'kegiatan')
                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                @elseif($item->category == 'layanan')
                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                @else
                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                @endif
                                {{ $categories[$item->category] ?? $item->category }}
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full font-mono text-[10px] font-bold uppercase tracking-wider
                                @if($item->status == 'published') bg-emerald-500/20 text-emerald-400 border border-emerald-500/30
                                @elseif($item->status == 'draft') bg-amber-500/20 text-amber-400 border border-amber-500/30
                                @else bg-slate-500/20 text-slate-400 border border-slate-500/30 @endif">
                                @if($item->status == 'published')
                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                Published
                                @elseif($item->status == 'draft')
                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Draft
                                @else
                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                                Archived
                                @endif
                            </span>
                            @if($item->is_featured)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full font-mono text-[10px] font-bold uppercase tracking-wider bg-rose-500/20 text-rose-400 border border-rose-500/30">
                                <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                Featured
                            </span>
                            @endif
                        </div>
                        <h3 class="font-mono text-lg font-bold text-white group-hover:text-cyan-300 transition-colors line-clamp-2 mb-2">
                            {{ $item->title }}
                        </h3>
                        <p class="text-sm text-slate-400 line-clamp-2 mb-3">{{ $item->excerpt }}</p>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4 text-xs text-slate-500">
                                <span class="flex items-center gap-1.5">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}
                                </span>
                                @if($item->publish_date)
                                <span class="flex items-center gap-1.5">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ \Carbon\Carbon::parse($item->publish_date)->format('d M Y') }}
                                </span>
                                @endif
                                <span class="flex items-center gap-1.5 text-cyan-400/70">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    {{ number_format($item->view_count ?? 0) }} view
                                </span>
                                <span class="flex items-center gap-1.5 text-emerald-400/70">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    {{ number_format($item->unique_view_count ?? 0) }} pembaca
                                </span>
                            </div>
                            @if($canCreate)
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.news.edit', $item->id) }}" class="rounded-lg border border-cyan-500/30 bg-cyan-500/10 p-2 text-cyan-400 hover:bg-cyan-500/20 transition-all">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form method="POST" action="{{ route('admin.news.destroy', $item->id) }}" onsubmit="return confirm('Yakin ingin menghapus berita ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-lg border border-rose-500/30 bg-rose-500/10 p-2 text-rose-400 hover:bg-rose-500/20 transition-all">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="rounded-xl border border-cyan-500/20 bg-slate-900/60 p-12 text-center">
                <svg class="mx-auto h-16 w-16 text-slate-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V9a2 2 0 012-2h2a2 2 0 012 2v9a2 2 0 01-2 2h-2z"/>
                </svg>
                <h3 class="font-mono text-lg font-bold text-white mb-2">Belum Ada Berita</h3>
                <p class="text-sm text-slate-400 mb-6">Mulai tambahkan berita pertama untuk portal SILATAR</p>
                @if($canCreate)
                <a href="{{ route('admin.news.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-cyan-600 to-cyan-500 px-5 py-3 font-mono text-sm font-bold uppercase tracking-wider text-white shadow-[0_0_25px_rgba(0,212,255,0.4)] transition-all hover:shadow-[0_0_35px_rgba(0,212,255,0.6)]">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Tambah Berita
                </a>
                @endif
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($news->hasPages())
        <div class="mt-8">
            {{ $news->links() }}
        </div>
        @endif
    </div>
</x-admin.layouts.app>