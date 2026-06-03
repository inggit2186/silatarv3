<x-admin.layouts.app>
    <div class="admin-page-header">
        <div>
            <h1 class="admin-page-title">Profil Saya</h1>
            <p class="admin-page-subtitle">Kelola informasi akun Anda</p>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        {{-- Profile Card --}}
        <div class="card">
            <div class="card-body text-center">
                <div class="mx-auto mb-4 h-24 w-24 overflow-hidden rounded-full bg-gradient-to-br from-cyan-500 to-blue-600">
                    @if(auth()->user()->pp && auth()->user()->nomor_induk)
                        <img
                            src="{{ asset('assets/img/users/' . auth()->user()->nomor_induk . '/' . auth()->user()->pp) }}"
                            alt="{{ auth()->user()->name }}"
                            class="h-full w-full object-cover"
                            onerror="this.style.display='none'; this.parentElement.textContent='{{ substr(auth()->user()->name, 0, 2) }}';"
                        >
                    @else
                        <div class="flex h-full items-center justify-center text-3xl font-bold text-white">
                            {{ substr(auth()->user()->name, 0, 2) }}
                        </div>
                    @endif
                </div>
                <h2 class="text-xl font-bold text-slate-900">{{ auth()->user()->name }}</h2>
                <p class="text-sm text-slate-500">{{ auth()->user()->role }}</p>
                <p class="mt-1 text-xs text-slate-400">{{ auth()->user()->nomor_induk }}</p>
                <div class="mt-4">
                    <span class="badge badge-cyan">{{ auth()->user()->role }}</span>
                </div>
            </div>
        </div>

        {{-- Profile Form --}}
        <div class="card lg:col-span-2">
            <div class="card-header">
                <h3 class="card-title">Informasi Akun</h3>
            </div>
            <div class="card-body">
                <form>
                    <div class="grid gap-5 md:grid-cols-2">
                        <div class="form-group">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-input" value="{{ auth()->user()->name }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-input" value="{{ auth()->user()->email ?? '-' }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">NIP / Nomor Induk</label>
                            <input type="text" class="form-input" value="{{ auth()->user()->nomor_induk }}" readonly>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Unit Kerja</label>
                            <input type="text" class="form-input" value="{{ auth()->user()->satker ?? '-' }}" readonly>
                        </div>
                    </div>

                    <hr class="my-6 border-slate-100">

                    <h4 class="mb-4 font-semibold text-slate-900">Ubah Password</h4>
                    <div class="space-y-4">
                        <div class="form-group max-w-md">
                            <label class="form-label">Password Lama</label>
                            <input type="password" class="form-input" placeholder="Masukkan password lama">
                        </div>
                        <div class="form-group max-w-md">
                            <label class="form-label">Password Baru</label>
                            <input type="password" class="form-input" placeholder="Masukkan password baru">
                        </div>
                        <div class="form-group max-w-md">
                            <label class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" class="form-input" placeholder="Konfirmasi password baru">
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin.layouts.app>