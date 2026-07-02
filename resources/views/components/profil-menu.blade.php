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
    <div class="flex flex-col items-center gap-1 py-4 px-2" style="background: var(--paper); border-right: 1px solid var(--line); border-radius: 0 0.75rem 0.75rem 0; box-shadow: var(--shadow);">

        <!-- Section Label -->
        <div class="mb-3 px-2">
            <span class="font-mono text-[9px] font-bold uppercase tracking-[0.2em] block text-center" style="color: var(--gold);">Menu</span>
            <div class="w-full h-px mt-2" style="background: linear-gradient(90deg, var(--gold), transparent);"></div>
        </div>

        <!-- Profil -->
        <a href="{{ $profilUrl ?? '#' }}" class="group relative w-12 h-12 flex items-center justify-center rounded-lg transition-all duration-300" title="Profil" style="color: var(--ink-soft);" onmouseover="this.style.background='var(--gold)'" onmouseout="this.style.background='transparent'">
            <svg class="w-5 h-5 transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                <circle cx="12" cy="7" r="4"/>
            </svg>
            <div class="absolute left-full ml-3 px-3 py-1.5 rounded-lg shadow-xl opacity-0 pointer-events-none group-hover:opacity-100 group-hover:pointer-events-auto transition-all duration-200 whitespace-nowrap" style="background: var(--paper); border: 1px solid var(--line);">
                <span class="font-mono text-xs font-bold uppercase tracking-wider" style="color: var(--ink);">Profil Kantor</span>
                <div class="absolute right-full top-1/2 -translate-y-1/2 border-4 border-transparent" style="border-right-color: var(--line);"></div>
            </div>
        </a>

        <!-- Sejarah -->
        <a href="{{ $sejarahUrl ?? '#' }}" class="group relative w-12 h-12 flex items-center justify-center rounded-lg transition-all duration-300" title="Sejarah" style="color: var(--ink-soft);" onmouseover="this.style.background='var(--gold)'" onmouseout="this.style.background='transparent'">
            <svg class="w-5 h-5 transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M12 8v4l3 3"/>
                <circle cx="12" cy="12" r="9"/>
            </svg>
            <div class="absolute left-full ml-3 px-3 py-1.5 rounded-lg shadow-xl opacity-0 pointer-events-none group-hover:opacity-100 group-hover:pointer-events-auto transition-all duration-200 whitespace-nowrap" style="background: var(--paper); border: 1px solid var(--line);">
                <span class="font-mono text-xs font-bold uppercase tracking-wider" style="color: var(--ink);">Sejarah Singkat</span>
                <div class="absolute right-full top-1/2 -translate-y-1/2 border-4 border-transparent" style="border-right-color: var(--line);"></div>
            </div>
        </a>

        <!-- Struktur -->
        <a href="{{ $strukturUrl ?? '#' }}" class="group relative w-12 h-12 flex items-center justify-center rounded-lg transition-all duration-300" title="Struktur" style="color: var(--ink-soft);" onmouseover="this.style.background='var(--gold)'" onmouseout="this.style.background='transparent'">
            <svg class="w-5 h-5 transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <rect x="9" y="2" width="6" height="6" rx="1"/>
                <path d="M4 22v-4a2 2 0 012-2h12a2 2 0 012 2v4"/>
                <path d="M12 12v4"/>
                <path d="M6 12v4a2 2 0 002 2h8a2 2 0 002-2v-4"/>
            </svg>
            <div class="absolute left-full ml-3 px-3 py-1.5 rounded-lg shadow-xl opacity-0 pointer-events-none group-hover:opacity-100 group-hover:pointer-events-auto transition-all duration-200 whitespace-nowrap" style="background: var(--paper); border: 1px solid var(--line);">
                <span class="font-mono text-xs font-bold uppercase tracking-wider" style="color: var(--ink);">Struktur Organisasi</span>
                <div class="absolute right-full top-1/2 -translate-y-1/2 border-4 border-transparent" style="border-right-color: var(--line);"></div>
            </div>
        </a>

        <!-- Divider -->
        <div class="w-8 h-px my-2" style="background: linear-gradient(90deg, transparent, var(--line), transparent);"></div>

        <!-- Unit Kerja -->
        <a href="{{ $unitUrl ?? '#' }}" class="group relative w-12 h-12 flex items-center justify-center rounded-lg transition-all duration-300" title="Unit Kerja" style="color: var(--ink-soft);" onmouseover="this.style.background='var(--gold)'" onmouseout="this.style.background='transparent'">
            <svg class="w-5 h-5 transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16"/>
                <path d="M3 21h18"/>
                <path d="M9 7h1m-1 4h1m4-4h1m-1 4h1"/>
                <path d="M5 21v-4a2 2 0 012-2h4a2 2 0 012 2v4"/>
            </svg>
            <div class="absolute left-full ml-3 px-3 py-1.5 rounded-lg shadow-xl opacity-0 pointer-events-none group-hover:opacity-100 group-hover:pointer-events-auto transition-all duration-200 whitespace-nowrap" style="background: var(--paper); border: 1px solid var(--line);">
                <span class="font-mono text-xs font-bold uppercase tracking-wider" style="color: var(--ink);">Unit Kerja</span>
                <div class="absolute right-full top-1/2 -translate-y-1/2 border-4 border-transparent" style="border-right-color: var(--line);"></div>
            </div>
        </a>

    </div>
</div>
