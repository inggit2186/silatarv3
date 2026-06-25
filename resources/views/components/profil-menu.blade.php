<!-- Left Sidebar Navigation Menu -->
<div x-data="{ show: false, lastScroll: 0, scrollY: 0 }"
     x-init="window.addEventListener('scroll', () => { scrollY = window.scrollY; show = scrollY > 300; }, { passive: true })"
     x-show="show"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 -translate-x-full"
     x-transition:enter-end="opacity-100 translate-x-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 translate-x-0"
     x-transition:leave-end="opacity-0 -translate-x-full"
     class="fixed left-0 top-1/2 -translate-y-1/2 z-40"
>
    <div class="flex flex-col items-center gap-1 py-4 px-2 bg-slate-950/90 backdrop-blur-md border-r border-cyan-500/20 rounded-r-xl shadow-2xl shadow-cyan-500/5">

        <!-- Section Label -->
        <div class="mb-3 px-2">
            <span class="font-mono text-[9px] font-bold uppercase tracking-[0.2em] text-cyan-400 block text-center">Menu</span>
            <div class="w-full h-px bg-gradient-to-r from-cyan-400 to-transparent mt-2"></div>
        </div>

        <!-- Profil -->
        <a href="{{ $profilUrl ?? '#' }}" class="group relative w-12 h-12 flex items-center justify-center rounded-lg hover:bg-cyan-500/10 transition-all duration-300" title="Profil">
            <svg class="w-5 h-5 text-cyan-400/60 group-hover:text-cyan-400 transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                <circle cx="12" cy="7" r="4"/>
            </svg>
            <div class="absolute left-full ml-3 px-3 py-1.5 bg-slate-900 border border-cyan-500/30 rounded-lg shadow-xl opacity-0 pointer-events-none group-hover:opacity-100 group-hover:pointer-events-auto transition-all duration-200 whitespace-nowrap">
                <span class="font-mono text-xs font-bold uppercase tracking-wider text-white">Profil Kantor</span>
                <div class="absolute right-full top-1/2 -translate-y-1/2 border-8 border-transparent border-r-cyan-500/30"></div>
            </div>
        </a>

        <!-- Sejarah -->
        <a href="{{ $sejarahUrl ?? '#' }}" class="group relative w-12 h-12 flex items-center justify-center rounded-lg hover:bg-emerald-500/10 transition-all duration-300" title="Sejarah">
            <svg class="w-5 h-5 text-emerald-400/60 group-hover:text-emerald-400 transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M12 8v4l3 3"/>
                <circle cx="12" cy="12" r="9"/>
            </svg>
            <div class="absolute left-full ml-3 px-3 py-1.5 bg-slate-900 border border-emerald-500/30 rounded-lg shadow-xl opacity-0 pointer-events-none group-hover:opacity-100 group-hover:pointer-events-auto transition-all duration-200 whitespace-nowrap">
                <span class="font-mono text-xs font-bold uppercase tracking-wider text-white">Sejarah Singkat</span>
                <div class="absolute right-full top-1/2 -translate-y-1/2 border-8 border-transparent border-r-emerald-500/30"></div>
            </div>
        </a>

        <!-- Struktur -->
        <a href="{{ $strukturUrl ?? '#' }}" class="group relative w-12 h-12 flex items-center justify-center rounded-lg hover:bg-purple-500/10 transition-all duration-300" title="Struktur">
            <svg class="w-5 h-5 text-purple-400/60 group-hover:text-purple-400 transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <rect x="9" y="2" width="6" height="6" rx="1"/>
                <path d="M4 22v-4a2 2 0 012-2h12a2 2 0 012 2v4"/>
                <path d="M12 12v4"/>
                <path d="M6 12v4a2 2 0 002 2h8a2 2 0 002-2v-4"/>
            </svg>
            <div class="absolute left-full ml-3 px-3 py-1.5 bg-slate-900 border border-purple-500/30 rounded-lg shadow-xl opacity-0 pointer-events-none group-hover:opacity-100 group-hover:pointer-events-auto transition-all duration-200 whitespace-nowrap">
                <span class="font-mono text-xs font-bold uppercase tracking-wider text-white">Struktur Organisasi</span>
                <div class="absolute right-full top-1/2 -translate-y-1/2 border-8 border-transparent border-r-purple-500/30"></div>
            </div>
        </a>

        <!-- Divider -->
        <div class="w-8 h-px bg-gradient-to-r from-transparent via-slate-700 to-transparent my-2"></div>

        <!-- Unit Kerja -->
        <a href="{{ $unitUrl ?? '#' }}" class="group relative w-12 h-12 flex items-center justify-center rounded-lg hover:bg-amber-500/10 transition-all duration-300" title="Unit Kerja">
            <svg class="w-5 h-5 text-amber-400/60 group-hover:text-amber-400 transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16"/>
                <path d="M3 21h18"/>
                <path d="M9 7h1m-1 4h1m4-4h1m-1 4h1"/>
                <path d="M5 21v-4a2 2 0 012-2h4a2 2 0 012 2v4"/>
            </svg>
            <div class="absolute left-full ml-3 px-3 py-1.5 bg-slate-900 border border-amber-500/30 rounded-lg shadow-xl opacity-0 pointer-events-none group-hover:opacity-100 group-hover:pointer-events-auto transition-all duration-200 whitespace-nowrap">
                <span class="font-mono text-xs font-bold uppercase tracking-wider text-white">Unit Kerja</span>
                <div class="absolute right-full top-1/2 -translate-y-1/2 border-8 border-transparent border-r-amber-500/30"></div>
            </div>
        </a>

    </div>
</div>
