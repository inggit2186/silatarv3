<div>
    @if ($submitted)
        <div class="rounded-3xl border border-emerald-200 bg-emerald-50 p-5 text-sm leading-6 text-emerald-800">
            Terima kasih, pesanmu sudah masuk. Kami akan lanjutkan dari sini.
        </div>
    @endif

    <form wire:submit="submit" class="space-y-4">
        <div class="grid gap-4 sm:grid-cols-2">
            <label class="block">
                <span class="mb-2 block text-sm font-medium text-slate-700">Nama</span>
                <input
                    type="text"
                    wire:model.defer="name"
                    class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-cyan-300 focus:ring-2 focus:ring-cyan-100"
                    placeholder="Nama kamu"
                >
                @error('name')
                    <span class="mt-2 block text-sm text-rose-600">{{ $message }}</span>
                @enderror
            </label>

            <label class="block">
                <span class="mb-2 block text-sm font-medium text-slate-700">Email</span>
                <input
                    type="email"
                    wire:model.defer="email"
                    class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-cyan-300 focus:ring-2 focus:ring-cyan-100"
                    placeholder="nama@domain.com"
                >
                @error('email')
                    <span class="mt-2 block text-sm text-rose-600">{{ $message }}</span>
                @enderror
            </label>
        </div>

        <label class="block">
            <span class="mb-2 block text-sm font-medium text-slate-700">Company / Project</span>
            <input
                type="text"
                wire:model.defer="company"
                class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-cyan-300 focus:ring-2 focus:ring-cyan-100"
                placeholder="Nama bisnis atau produk"
            >
            @error('company')
                <span class="mt-2 block text-sm text-rose-600">{{ $message }}</span>
            @enderror
        </label>

        <label class="block">
            <span class="mb-2 block text-sm font-medium text-slate-700">Message</span>
            <textarea
                rows="4"
                wire:model.defer="message"
                class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-cyan-300 focus:ring-2 focus:ring-cyan-100"
                placeholder="Ceritakan kebutuhan landing page kamu"
            ></textarea>
            @error('message')
                <span class="mt-2 block text-sm text-rose-600">{{ $message }}</span>
            @enderror
        </label>

        <button
            type="submit"
            class="inline-flex w-full items-center justify-center rounded-2xl bg-cyan-500 px-5 py-3 text-sm font-semibold text-white transition hover:bg-cyan-600"
        >
            Kirim request
        </button>
    </form>
</div>
