<x-admin.layouts.app>
    <div class="page-header">
        <div class="page-header-content">
            <span class="page-label">// Profil</span>
            <h1 class="page-title">Profil Saya</h1>
            <p class="page-subtitle">Kelola informasi akun Anda</p>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <!-- Profile Card -->
        <div class="card">
            <div class="card-body text-center">
                <div class="mx-auto mb-4 h-24 w-24 overflow-hidden rounded-full" style="background: linear-gradient(135deg, #0891B2 0%, #2563EB 100%);">
                    @if(auth()->user()->pp && auth()->user()->nomor_induk)
                        <img
                            src="{{ asset('storage/users_berkas/' . auth()->user()->nomor_induk . '/' . auth()->user()->pp) }}"
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
                <h2 class="text-xl font-bold" style="color: var(--text-primary);">{{ auth()->user()->name }}</h2>
                <p class="text-sm" style="color: var(--text-muted);">{{ auth()->user()->role }}</p>
                <p class="mt-1 text-xs" style="color: var(--text-muted);">{{ auth()->user()->nomor_induk }}</p>
                <div class="mt-4">
                    <span class="badge badge-info">{{ auth()->user()->role }}</span>
                </div>
            </div>
        </div>

        <!-- Profile Form -->
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

                    <hr class="my-6" style="border-color: var(--border);">

                    <h4 class="mb-4 font-semibold" style="color: var(--text-primary);">Ubah Password</h4>
                    <div class="space-y-4">
                        <div class="form-group" style="max-width: 400px;">
                            <label class="form-label">Password Lama</label>
                            <input type="password" class="form-input" placeholder="Masukkan password lama">
                        </div>
                        <div class="form-group" style="max-width: 400px;">
                            <label class="form-label">Password Baru</label>
                            <input type="password" class="form-input" placeholder="Masukkan password baru">
                        </div>
                        <div class="form-group" style="max-width: 400px;">
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
