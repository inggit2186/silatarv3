<x-layouts.app title="Daftar - SILATAR">
    <main class="mx-auto flex min-h-[calc(100vh-2rem)] max-w-7xl items-center px-6 py-10 lg:px-8">
        <div class="grid w-full gap-8 lg:grid-cols-[1.05fr_.95fr]">
            <aside class="rounded-[2rem] border border-cyan-100 bg-[linear-gradient(180deg,_#f0fdfa_0%,_#ffffff_100%)] p-8 shadow-sm lg:p-10">
                <p class="text-sm font-semibold uppercase tracking-[0.24em] text-cyan-700">Daftar akun</p>
                <h1 class="mt-3 text-3xl font-semibold tracking-tight text-slate-900">Buat akun baru</h1>
                <p class="mt-3 max-w-xl text-sm leading-7 text-slate-600">
                    Daftarkan akun untuk mulai memakai layanan digital, melihat riwayat, dan menerima pembaruan penting.
                </p>

                <div class="mt-8 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-3xl border border-white bg-white p-5 shadow-sm">
                        <p class="text-sm font-semibold text-slate-900">Cepat</p>
                        <p class="mt-2 text-sm leading-6 text-slate-600">Form dibuat ringkas agar mudah diisi.</p>
                    </div>
                    <div class="rounded-3xl border border-white bg-white p-5 shadow-sm">
                        <p class="text-sm font-semibold text-slate-900">Aman</p>
                        <p class="mt-2 text-sm leading-6 text-slate-600">Siap dihubungkan ke autentikasi Laravel.</p>
                    </div>
                </div>
            </aside>

            <section class="rounded-[2rem] border border-slate-200 bg-white p-8 shadow-sm lg:p-10">
                <p class="text-sm font-semibold uppercase tracking-[0.24em] text-cyan-700">Registrasi</p>
                <h2 class="mt-3 text-3xl font-semibold tracking-tight text-slate-900">Lengkapi data akun</h2>
                <p class="mt-3 text-sm leading-7 text-slate-600">
                    Halaman ini sudah tersedia sebagai pintu masuk, dan nanti bisa langsung kita sambungkan ke proses registrasi.
                </p>

                <form class="mt-8 space-y-4">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700" for="name">Nama</label>
                            <input id="name" type="text" class="w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-cyan-400 focus:ring-cyan-400" placeholder="Nama lengkap">
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700" for="phone">Telepon</label>
                            <input id="phone" type="text" class="w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-cyan-400 focus:ring-cyan-400" placeholder="08xxxxxxxxxx">
                        </div>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700" for="reg-email">Email</label>
                        <input id="reg-email" type="email" class="w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-cyan-400 focus:ring-cyan-400" placeholder="nama@contoh.com">
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700" for="reg-password">Password</label>
                            <input id="reg-password" type="password" class="w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-cyan-400 focus:ring-cyan-400" placeholder="........">
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700" for="confirm-password">Konfirmasi</label>
                            <input id="confirm-password" type="password" class="w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-cyan-400 focus:ring-cyan-400" placeholder="........">
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-3 pt-2">
                        <button type="button" class="rounded-full bg-cyan-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-cyan-700">
                            Daftar
                        </button>
                        <a href="{{ route('login') }}" class="rounded-full border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:border-cyan-200 hover:text-slate-900">
                            Sudah punya akun
                        </a>
                    </div>
                </form>
            </section>
        </div>
    </main>
</x-layouts.app>
