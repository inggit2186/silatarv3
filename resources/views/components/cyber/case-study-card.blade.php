@props([
    'level' => '01',
    'title' => '',
    'description' => '',
    'progress' => 0,
    'unlocked' => false,
    'videoUrl' => '',
    'thumbnail' => '',
    'tags' => [],
])

<div
    x-data="{
        expanded: false,
        progress: {{ $progress }},
        unlocked: {{ $unlocked ? 'true' : 'false' }},
        videoUrl: '{{ $videoUrl }}',
        title: '{{ $title }}',
        toggleVideo() {
            if (!this.videoUrl) return;
            this.expanded = !this.expanded;
            document.body.style.overflow = this.expanded ? 'hidden' : '';
        },
        closeVideo() {
            this.expanded = false;
            document.body.style.overflow = '';
        }
    }"
    class="group relative rounded-2xl border border-cyan-500/20 bg-gradient-to-br from-slate-900/95 to-slate-950/95 p-6 transition-all duration-500 hover:border-cyan-400/60 hover:shadow-[0_0_40px_rgba(0,212,255,0.15)]"
>
    <!-- Level Badge -->
    <div class="mb-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl border border-cyan-500/40 bg-gradient-to-br from-cyan-500/20 to-cyan-600/10 shadow-[0_0_20px_rgba(0,212,255,0.3)]">
                <span class="font-mono text-lg font-bold text-cyan-400">{{ $level }}</span>
            </div>
            @if($unlocked)
                <span class="rounded-full border border-emerald-500/40 bg-emerald-500/10 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-emerald-400">
                    Unlocked
                </span>
            @endif
        </div>

        <div class="flex items-center gap-2">
            <span class="font-mono text-sm text-cyan-400/70">{{ $progress }}%</span>
        </div>
    </div>

    <!-- Title & Description -->
    <h3 class="mb-3 text-xl font-bold text-white group-hover:text-cyan-300 transition-colors">
        {{ $title }}
    </h3>
    <p class="mb-5 text-sm leading-relaxed text-slate-400">
        {{ $description }}
    </p>

    <!-- Progress Bar -->
    <div class="mb-5 h-2 w-full overflow-hidden rounded-full bg-slate-800/50">
        <div
            class="h-full rounded-full bg-gradient-to-r from-cyan-500 via-cyan-400 to-emerald-400 transition-all duration-1000 ease-out"
            style="width: {{ $progress }}%"
        ></div>
    </div>

    <!-- Tags -->
    @if(count($tags) > 0)
        <div class="mb-5 flex flex-wrap gap-2">
            @foreach($tags as $tag)
                <span class="rounded-full border border-cyan-500/20 bg-cyan-500/10 px-2.5 py-1 text-xs font-medium text-cyan-300/80">
                    {{ $tag }}
                </span>
            @endforeach
        </div>
    @endif

    <!-- Video Preview Area -->
    <div class="relative aspect-video overflow-hidden rounded-xl border border-slate-700/50 bg-slate-900/80">
        <!-- Thumbnail -->
        <div class="absolute inset-0 flex items-center justify-center">
            @if($thumbnail)
                <img src="{{ $thumbnail }}" alt="" class="h-full w-full object-cover opacity-60">
            @else
                <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-cyan-900/30 to-slate-900">
                    <svg class="h-16 w-16 text-cyan-500/30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                </div>
            @endif
        </div>

        <!-- Play Button Overlay -->
        @if($videoUrl)
            <button
                @click="toggleVideo()"
                class="absolute inset-0 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm transition-all duration-300 hover:bg-slate-900/60 opacity-80 group-hover:opacity-100"
            >
                <div class="flex h-20 w-20 items-center justify-center rounded-full bg-cyan-500/20 border border-cyan-400/40 shadow-[0_0_40px_rgba(0,212,255,0.4)] transition-transform duration-300 group-hover:scale-110">
                    <svg class="h-8 w-8 text-cyan-400 ml-1" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M8 5v14l11-7z"/>
                    </svg>
                </div>
            </button>
        @endif

        <!-- Expanded Video Modal -->
        <template x-if="expanded">
            <div
                class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/95 backdrop-blur-lg p-8"
                @keydown.escape.window="closeVideo()"
            >
                <button
                    @click="closeVideo()"
                    class="absolute right-4 top-4 z-50 flex h-12 w-12 items-center justify-center rounded-full border border-slate-700 bg-slate-900/80 text-slate-400 transition-all hover:border-cyan-500 hover:text-cyan-400"
                >
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                <div class="relative w-full max-w-5xl aspect-video rounded-2xl overflow-hidden border border-cyan-500/30 shadow-[0_0_60px_rgba(0,212,255,0.2)]">
                    <iframe
                        src="{{ $videoUrl }}"
                        class="h-full w-full"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen
                    ></iframe>
                </div>
            </div>
        </template>
    </div>

    <!-- Action Button -->
    @if($unlocked)
        <a
            href="#"
            class="mt-5 flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-cyan-600 to-cyan-500 px-6 py-3.5 font-semibold text-white shadow-[0_0_20px_rgba(0,212,255,0.4)] transition-all duration-300 hover:shadow-[0_0_30px_rgba(0,212,255,0.6)] hover:-translate-y-0.5 active:translate-y-0"
        >
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.554z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Lihat Detail
        </a>
    @endif

    <!-- Decorative Corner Elements -->
    <div class="absolute -left-px -top-px h-6 w-6 rounded-bl-xl border-l-2 border-t-2 border-cyan-500/40"></div>
    <div class="absolute -right-px -top-px h-6 w-6 rounded-br-xl border-r-2 border-t-2 border-cyan-500/40"></div>
    <div class="absolute -bottom-px -left-px h-6 w-6 rounded-bl-xl border-b-2 border-l-2 border-cyan-500/40"></div>
    <div class="absolute -bottom-px -right-px h-6 w-6 rounded-br-xl border-b-2 border-r-2 border-cyan-500/40"></div>
</div>