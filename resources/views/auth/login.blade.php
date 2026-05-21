<x-layouts.app title="Login - SILATAR">
    <main class="mx-auto flex min-h-[calc(100vh-2rem)] max-w-7xl items-center px-6 py-10 lg:px-8">
        <div class="grid w-full gap-8 lg:grid-cols-[.95fr_1.05fr]">
            <section class="rounded-[2rem] border border-slate-200 bg-white p-8 shadow-sm lg:p-10">
                <p class="text-sm font-semibold uppercase tracking-[0.24em] text-cyan-700">Masuk</p>
                <h1 class="mt-3 text-3xl font-semibold tracking-tight text-slate-900">Selamat datang kembali</h1>
                <p class="mt-3 max-w-xl text-sm leading-7 text-slate-600">
                    Masuk untuk melanjutkan ke dashboard layanan, melihat status pengajuan, dan mengelola data akun.
                </p>

                @if ($errors->any())
                    <div class="mt-6 rounded-3xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-700">
                        <p class="font-semibold">Login gagal</p>
                        <ul class="mt-2 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form class="mt-8 space-y-4" method="POST" action="{{ route('login.submit') }}">
                    @csrf
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700" for="login">Email / Nomor Induk</label>
                        <input
                            id="login"
                            name="login"
                            type="text"
                            value="{{ old('login') }}"
                            class="w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-cyan-400 focus:ring-cyan-400"
                            placeholder="nama@contoh.com atau 1978xxxx"
                            autocomplete="username"
                            required
                        >
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700" for="password">Password</label>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            class="w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-cyan-400 focus:ring-cyan-400"
                            placeholder="........"
                            autocomplete="current-password"
                            required
                        >
                    </div>
                    <div class="flex items-center justify-between gap-3">
                        <label class="flex items-center gap-2 text-sm text-slate-600">
                            <input name="remember" value="1" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-cyan-600 focus:ring-cyan-500">
                            Ingat saya
                        </label>
                        <a href="{{ route('register') }}" class="text-sm font-medium text-cyan-700 hover:text-cyan-800">Buat akun baru</a>
                    </div>
                    <div class="flex flex-wrap gap-3 pt-2">
                        <button type="submit" class="rounded-full bg-cyan-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-cyan-700">
                            Masuk
                        </button>
                        <a href="{{ url('/') }}" class="rounded-full border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:border-cyan-200 hover:text-slate-900">
                            Kembali
                        </a>
                    </div>
                </form>
            </section>

            <aside class="rounded-[2rem] border border-cyan-100 bg-[linear-gradient(180deg,_#ecfeff_0%,_#ffffff_100%)] p-8 shadow-sm lg:p-10">
                <p class="text-sm font-semibold uppercase tracking-[0.24em] text-cyan-700">Akses cepat</p>
                <h2 class="mt-3 text-3xl font-semibold text-slate-900">Layanan digital dalam satu portal</h2>
                <p class="mt-4 text-sm leading-7 text-slate-600">
                    Gunakan akun untuk mengakses informasi yang lebih personal, memantau layanan, dan membaca pembaruan terbaru.
                </p>

                <div class="mt-8 space-y-4">
                    <div class="rounded-3xl border border-white bg-white p-5 shadow-sm">
                        <p class="text-sm font-semibold text-slate-900">Informasi terpusat</p>
                        <p class="mt-2 text-sm leading-6 text-slate-600">Semua info layanan dikemas rapi dan mudah dicari.</p>
                    </div>
                    <div class="rounded-3xl border border-white bg-white p-5 shadow-sm">
                        <p class="text-sm font-semibold text-slate-900">Tampilan bersih</p>
                        <p class="mt-2 text-sm leading-6 text-slate-600">Tema terang dengan hierarki yang mudah dipindai.</p>
                    </div>
                </div>
            </aside>
        </div>
    </main>
</x-layouts.app>
