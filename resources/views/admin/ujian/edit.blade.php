<x-admin.layouts.app title="{{ $title ?? 'Kelola Ujian' }}">
    <div class="page-header">
        <div class="page-header-content">
            <span class="page-label">// {{ $ujian->kode }}</span>
            <h1 class="page-title">{{ $ujian->judul }}</h1>
            <p class="page-subtitle">
                {{ $ujian->soal->count() }} soal · {{ $ujian->peserta->count() }} peserta · {{ $ujian->durasi_menit }} menit
            </p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.ujian.hasil', $ujian->id) }}" class="btn btn-secondary">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 17v-2m4 2v-2m4 2v-2M3 5h18v14H3z"/></svg>
                Hasil Ujian
            </a>
            <a href="{{ route('admin.ujian.index') }}" class="btn btn-secondary">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5m7 7l-7-7 7-7"/></svg>
                Kembali
            </a>
        </div>
    </div>

    @include('admin.ujian._partials.flash')

    <!-- ============ IDENTITAS UJIAN ============ -->
    <form method="POST" action="{{ route('admin.ujian.update', $ujian->id) }}" class="mb-6">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-header">
                <div class="flex items-center gap-3">
                    <div class="stat-icon primary" style="width: 36px; height: 36px;">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14,2 14,8 20,8"/></svg>
                    </div>
                    <div>
                        <h3 class="card-title">Identitas Ujian</h3>
                        <p class="text-sm text-muted">Ujian dapat diterbitkan setelah minimal 1 soal dan 1 peserta tersedia</p>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-group">
                        <label class="form-label">Kode Ujian</label>
                        <input type="text" name="kode" value="{{ old('kode', $ujian->kode) }}" class="form-input" required>
                        @error('kode')
                        <p class="form-error text-danger text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            @foreach($statuses as $key => $label)
                            <option value="{{ $key }}" {{ old('status', $ujian->status) === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('status')
                        <p class="form-error text-danger text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group md:col-span-2">
                        <label class="form-label">Judul Ujian</label>
                        <input type="text" name="judul" value="{{ old('judul', $ujian->judul) }}" class="form-input" required>
                        @error('judul')
                        <p class="form-error text-danger text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group md:col-span-2">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" rows="2" class="form-textarea">{{ old('deskripsi', $ujian->deskripsi) }}</textarea>
                    </div>
                    <div class="form-group md:col-span-2">
                        <label class="form-label">Materi / Referensi</label>
                        <textarea name="materi" rows="3" class="form-textarea">{{ old('materi', $ujian->materi) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Durasi (menit)</label>
                        <input type="number" name="durasi_menit" value="{{ old('durasi_menit', $ujian->durasi_menit) }}" min="1" max="600" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Acak Urutan Soal</label>
                        <select name="acak_soal" class="form-select">
                            <option value="0" {{ old('acak_soal', $ujian->acak_soal) ? '' : 'selected' }}>Tidak</option>
                            <option value="1" {{ old('acak_soal', $ujian->acak_soal) ? 'selected' : '' }}>Ya</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Mulai Ujian</label>
                        <input type="datetime-local" name="mulai_at" value="{{ old('mulai_at', $ujian->mulai_at?->format('Y-m-d\TH:i')) }}" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Batas Ujian Selesai</label>
                        <input type="datetime-local" name="selesai_at" value="{{ old('selesai_at', $ujian->selesai_at?->format('Y-m-d\TH:i')) }}" class="form-input">
                    </div>
                </div>
                <div class="flex items-center gap-3 mt-6">
                    <button type="submit" class="btn btn-primary">Simpan Identitas</button>
                </div>
            </div>
        </div>
    </form>

    <!-- ============ TABS ============ -->
    <div x-data="{ tab: 'soal', tipe: 'pilihan_ganda' }">
        <div class="flex items-center gap-2 mb-6 border-b" style="border-color: var(--line);">
            <button type="button" @click="tab = 'soal'"
                :class="tab === 'soal' ? 'border-b-2 font-semibold' : 'text-muted'"
                :style="tab === 'soal' ? 'border-color: var(--gold); color: var(--gold);' : ''"
                class="px-4 py-3 flex items-center gap-2 transition">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 11l3 3L22 4M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
                Soal ({{ $ujian->soal->count() }})
            </button>
            <button type="button" @click="tab = 'peserta'"
                :class="tab === 'peserta' ? 'border-b-2 font-semibold' : 'text-muted'"
                :style="tab === 'peserta' ? 'border-color: var(--gold); color: var(--gold);' : ''"
                class="px-4 py-3 flex items-center gap-2 transition">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
                Peserta ({{ $ujian->peserta->count() }})
            </button>
        </div>

        <!-- ================= TAB SOAL ================= -->
        <div x-show="tab === 'soal'" x-cloak>
            <!-- Form tambah soal -->
            <form method="POST" action="{{ route('admin.ujian.soal.store', $ujian->id) }}" class="card mb-6" x-data="{ tipe: 'pilihan_ganda' }">
                @csrf
                <div class="card-header">
                    <div class="flex items-center gap-3">
                        <div class="stat-icon emerald" style="width: 36px; height: 36px;">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 4v16m8-8H4"/></svg>
                        </div>
                        <div>
                            <h3 class="card-title">Tambah Soal</h3>
                            <p class="text-sm text-muted">Soal baru ditambahkan di urutan terakhir</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="form-group">
                            <label class="form-label">Tipe Soal</label>
                            <select name="tipe" x-model="tipe" class="form-select">
                                @foreach($tipeSoal as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Poin</label>
                            <input type="number" name="poin" value="1" min="1" max="100" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Petunjuk</label>
                            <input type="text" name="petunjuk" placeholder="Petunjuk singkat (opsional)" class="form-input">
                        </div>
                        <div class="form-group md:col-span-3">
                            <label class="form-label">Pertanyaan</label>
                            <textarea name="pertanyaan" rows="3" placeholder="Tulis pertanyaan ujian" class="form-textarea" required></textarea>
                            @error('pertanyaan')
                            <p class="form-error text-danger text-sm">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div x-show="tipe === 'pilihan_ganda'" x-cloak class="mt-4">
                        <label class="form-label">Pilihan Jawaban</label>
                        @include('admin.ujian._partials.pilihan-rows', ['soal' => null, 'jumlah' => 5])
                    </div>

                    <div class="flex items-center gap-3 mt-6">
                        <button type="submit" class="btn btn-primary">Simpan Soal</button>
                    </div>
                </div>
            </form>

            <!-- Daftar soal -->
            <div class="space-y-4">
                @forelse($ujian->soal as $index => $soal)
                    <div class="card" x-data="{ edit: false }">
                        <div class="card-header">
                            <div class="flex items-start gap-3 flex-1">
                                <div class="stat-icon primary" style="width: 36px; height: 36px; flex-shrink: 0;">
                                    <span class="text-sm font-bold">{{ $index + 1 }}</span>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="badge badge-info">{{ $tipeSoal[$soal->tipe] ?? $soal->tipe }}</span>
                                        <span class="badge badge-gray">{{ $soal->poin }} poin</span>
                                    </div>
                                    <p class="font-medium mt-2">{{ $soal->pertanyaan }}</p>
                                    @if($soal->petunjuk)
                                        <p class="text-sm text-muted mt-1">{{ $soal->petunjuk }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="action-buttons">
                                <button type="button" @click="edit = !edit" class="action-btn" title="Edit soal">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <form method="POST" action="{{ route('admin.ujian.soal.destroy', $soal->id) }}"
                                    onsubmit="return confirm('Yakin ingin menghapus soal ini?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn delete" title="Hapus soal">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 2 0 00-1-1h-4a1 2 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </div>

                        @if($soal->tipe === 'pilihan_ganda')
                            <div class="card-body">
                                <div class="space-y-2">
                                    @foreach($soal->pilihan as $pilihan)
                                        <div class="flex items-center gap-3">
                                            <span class="w-7 h-7 rounded-lg flex items-center justify-center text-xs font-bold flex-shrink-0"
                                                style="background: {{ $pilihan->benar ? 'var(--gold)' : 'rgba(212,168,83,0.12)' }}; color: {{ $pilihan->benar ? 'var(--night)' : 'var(--gold)' }};">{{ $pilihan->label }}</span>
                                            <span class="{{ $pilihan->benar ? 'font-semibold' : '' }}">{{ $pilihan->teks }}</span>
                                            @if($pilihan->benar)
                                                <span class="badge badge-success">Kunci</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Form edit -->
                        <div x-show="edit" x-cloak class="card-body" style="border-top: 1px solid var(--line);">
                            <form method="POST" action="{{ route('admin.ujian.soal.update', $soal->id) }}">
                                @csrf
                                @method('PUT')
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="form-group">
                                        <label class="form-label">Tipe Soal</label>
                                        <select name="tipe" x-model="tipe" class="form-select">
                                            @foreach($tipeSoal as $key => $label)
                                            <option value="{{ $key }}" {{ $soal->tipe === $key ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Poin</label>
                                        <input type="number" name="poin" value="{{ $soal->poin }}" min="1" max="100" class="form-input" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Petunjuk</label>
                                        <input type="text" name="petunjuk" value="{{ $soal->petunjuk }}" class="form-input">
                                    </div>
                                    <div class="form-group md:col-span-3">
                                        <label class="form-label">Pertanyaan</label>
                                        <textarea name="pertanyaan" rows="3" class="form-textarea" required>{{ $soal->pertanyaan }}</textarea>
                                    </div>
                                </div>

                                <div x-show="tipe === 'pilihan_ganda'" x-cloak class="mt-4">
                                    <label class="form-label">Pilihan Jawaban</label>
                                    @include('admin.ujian._partials.pilihan-rows', ['soal' => $soal, 'jumlah' => 5])
                                </div>

                                <div class="flex items-center gap-3 mt-6">
                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                    <button type="button" @click="edit = false" class="btn btn-secondary">Batal</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="card">
                        <div class="empty-state">
                            <svg class="w-12 h-12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <p class="empty-state-title">Belum ada soal</p>
                            <p class="empty-state-text">Tambahkan soal pertama melalui form di atas.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- ================= TAB PESERTA ================= -->
        <div x-show="tab === 'peserta'" x-cloak>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Tambah peserta -->
                <div class="card" x-data="cariPeserta()">
                    <div class="card-header">
                        <div class="flex items-center gap-3">
                            <div class="stat-icon violet" style="width: 36px; height: 36px;">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14m7-7H5"/></svg>
                            </div>
                            <div>
                                <h3 class="card-title">Tambah Peserta</h3>
                                <p class="text-sm text-muted">Cari pegawai berdasarkan nama atau NIP</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.ujian.peserta.store', $ujian->id) }}">
                            @csrf
                            <div class="form-group">
                                <label class="form-label">Cari Pegawai</label>
                                <input type="text" x-model="q"
                                    @input.debounce.400ms="cari()"
                                    placeholder="Ketik nama atau NIP..." class="form-input">
                            </div>

                            <div class="mt-3 space-y-2" x-show="searched">
                                <template x-for="u in results" :key="u.id">
                                    <label class="flex items-center gap-3 p-3 rounded-lg cursor-pointer"
                                        style="border: 1px solid var(--line);">
                                        <input type="checkbox" name="user_ids[]" :value="u.id"
                                            :disabled="u.terdaftar"
                                            x-model="selected">
                                        <span class="flex-1">
                                            <span class="font-medium block" x-text="u.name"></span>
                                            <span class="text-sm text-muted" x-text="u.nip || u.role"></span>
                                        </span>
                                        <span class="badge badge-gray" x-show="u.terdaftar">Terdaftar</span>
                                    </label>
                                </template>
                                <p class="text-sm text-muted" x-show="results.length === 0">Tidak ada pegawai ditemukan.</p>
                            </div>

                            <input type="hidden" x-ref="selectedBox">
                            <div class="flex items-center gap-3 mt-6">
                                <button type="submit" class="btn btn-primary"
                                    :disabled="!selected || selected.length === 0">
                                    Tambahkan Peserta
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Daftar peserta -->
                <div class="card">
                    <div class="card-header">
                        <div class="flex items-center gap-3">
                            <div class="stat-icon emerald" style="width: 36px; height: 36px;">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 2 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                            </div>
                            <div>
                                <h3 class="card-title">Peserta Terdaftar</h3>
                                <p class="text-sm text-muted">{{ $ujian->peserta->count() }} pegawai ditugaskan</p>
                            </div>
                        </div>
                    </div>
                    <div class="table-wrapper">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Peserta</th>
                                    <th>Status</th>
                                    <th>Nilai</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ujian->peserta as $peserta)
                                    <tr>
                                        <td>
                                            <span class="table-user-name">{{ $peserta->user?->name ?? 'Pengguna dihapus' }}</span>
                                            <span class="table-user-email">{{ $peserta->user?->nip }}</span>
                                        </td>
                                        <td>
                                            <span class="badge @if($peserta->status === 'selesai') badge-success @elseif($peserta->status === 'berjalan') badge-warning @else badge-gray @endif">
                                                {{ ['belum_mulai' => 'Belum Mulai', 'berjalan' => 'Berjalan', 'selesai' => 'Selesai'][$peserta->status] ?? $peserta->status }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-primary font-medium">{{ $peserta->nilai !== null ? $peserta->nilai : '-' }}</span>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <form method="POST" action="{{ route('admin.ujian.peserta.destroy', $peserta->id) }}"
                                                    onsubmit="return confirm('Yakin ingin menghapus peserta ini?')" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="action-btn delete" title="Hapus peserta">
                                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 2 0 00-1-1h-4a1 2 0 00-1 1v3M4 7h16"/></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4">
                                            <div class="empty-state">
                                                <svg class="w-12 h-12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17 21v-2a4 4 0 00-4-4H5a2 2 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                                                <p class="empty-state-title">Belum ada peserta</p>
                                                <p class="empty-state-text">Tambahkan peserta melalui kolom pencarian di samping.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('cariPeserta', () => ({
                q: '',
                results: [],
                selected: [],
                loading: false,
                searched: false,
                async cari() {
                    this.loading = true;
                    try {
                        const res = await fetch(@js(route('admin.ujian.peserta.cari', $ujian->id)) + '?q=' + encodeURIComponent(this.q), {
                            headers: { 'Accept': 'application/json' },
                        });
                        const data = await res.json();
                        this.results = data.results;
                        this.searched = true;
                    } catch (e) {
                        this.results = [];
                    } finally {
                        this.loading = false;
                    }
                },
            }));
        });
    </script>
</x-admin.layouts.app>
