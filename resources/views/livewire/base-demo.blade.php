<div class="rounded-2xl border border-white/10 bg-slate-950/40 p-5">
    <div class="flex items-center justify-between gap-4">
        <div>
            <p class="text-sm text-slate-400">Current value</p>
            <p class="mt-1 text-4xl font-semibold tracking-tight text-white">{{ $count }}</p>
        </div>

        <div class="flex items-center gap-2">
            <button
                type="button"
                wire:click="decrement"
                class="inline-flex h-11 w-11 items-center justify-center rounded-full border border-white/10 bg-white/5 text-lg text-white transition hover:bg-white/10"
                aria-label="Decrease counter"
            >
                -
            </button>
            <button
                type="button"
                wire:click="increment"
                class="inline-flex h-11 w-11 items-center justify-center rounded-full bg-cyan-400 text-lg font-semibold text-slate-950 transition hover:bg-cyan-300"
                aria-label="Increase counter"
            >
                +
            </button>
        </div>
    </div>

    <p class="mt-4 text-sm leading-6 text-slate-300">
        Ini contoh kecil untuk memastikan Livewire sudah jalan di base project.
    </p>
</div>
