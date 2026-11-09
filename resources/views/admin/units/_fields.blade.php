@php
    $d = $department ?? null;
    $v = fn ($field, $default = '') => old($field, $d->{$field} ?? $default);
@endphp

<div class="grid gap-6 lg:grid-cols-2">
    <!-- Informasi Dasar -->
    <div class="card">
        <div class="card-header">
            <div class="flex items-center gap-3">
                <div class="stat-icon amber" style="width: 36px; height: 36px;">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div>
                    <h3 class="card-title">Informasi Dasar</h3>
                    <p class="text-sm text-muted">Data utama unit kerja</p>
                </div>
            </div>
        </div>
        <div class="card-body space-y-4">
            <div class="form-group">
                <label class="form-label">Nama Unit Kerja <span class="text-danger">*</span></label>
                <input type="text" name="nama" value="{{ $v('nama') }}" required class="form-input" placeholder="Contoh: KUA Kecamatan X">
                @error('nama')
                <p class="text-sm mt-1" style="color: var(--danger);">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Kode Unit <span class="text-danger">*</span></label>
                <input type="text" name="kode" value="{{ $v('kode') }}" required class="form-input" placeholder="Contoh: KUA01" maxlength="25">
                @error('kode')
                <p class="text-sm mt-1" style="color: var(--danger);">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Kategori <span class="text-danger">*</span></label>
                <select name="kategori" required class="form-select">
                    <option value="">Pilih Kategori</option>
                    @foreach($kategoriOptions as $key => $label)
                        <option value="{{ $key }}" {{ $v('kategori') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('kategori')
                <p class="text-sm mt-1" style="color: var(--danger);">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    @foreach($options['statusUnit'] as $key => $label)
                        <option value="{{ $key }}" {{ $v('status', 1) == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('status')
                <p class="text-sm mt-1" style="color: var(--danger);">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" rows="3" class="form-input" style="height: auto; padding: 10px 12px;" placeholder="Keterangan singkat unit kerja">{{ $v('deskripsi') }}</textarea>
                @error('deskripsi')
                <p class="text-sm mt-1" style="color: var(--danger);">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Identitas Lembaga -->
    <div class="card">
        <div class="card-header">
            <div class="flex items-center gap-3">
                <div class="stat-icon violet" style="width: 36px; height: 36px;">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="card-title">Identitas Lembaga</h3>
                    <p class="text-sm text-muted">Data Watch Word &amp; akreditasi</p>
                </div>
            </div>
        </div>
        <div class="card-body space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div class="form-group">
                    <label class="form-label">NSM</label>
                    <input type="text" name="nsm" value="{{ $v('nsm') }}" class="form-input" placeholder="Nomor Surat Madrasah">
                    @error('nsm')
                    <p class="text-sm mt-1" style="color: var(--danger);">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">NPSM / NPS</label>
                    <input type="text" name="npsm" value="{{ $v('npsm') }}" class="form-input" placeholder="Contoh: 1234567890">
                    @error('npsm')
                    <p class="text-sm mt-1" style="color: var(--danger);">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="form-group">
                    <label class="form-label">Status Lembaga</label>
                    <select name="status_lembaga" class="form-select">
                        <option value="">Pilih Status</option>
                        @foreach($options['statusLembaga'] as $label)
                            <option value="{{ $label }}" {{ $v('status_lembaga') == $label ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('status_lembaga')
                    <p class="text-sm mt-1" style="color: var(--danger);">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Waktu Belajar</label>
                    <select name="waktu_belajar" class="form-select">
                        <option value="">Pilih Waktu Belajar</option>
                        @foreach($options['waktuBelajar'] as $label)
                            <option value="{{ $label }}" {{ $v('waktu_belajar') == $label ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('waktu_belajar')
                    <p class="text-sm mt-1" style="color: var(--danger);">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="form-group">
                    <label class="form-label">Komite Lembaga</label>
                    <select name="komite_lembaga" class="form-select">
                        <option value="">Pilih Status</option>
                        @foreach($options['komiteLembaga'] as $label)
                            <option value="{{ $label }}" {{ $v('komite_lembaga') == $label ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('komite_lembaga')
                    <p class="text-sm mt-1" style="color: var(--danger);">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Status KKM</label>
                    <select name="status_kkm" class="form-select">
                        <option value="">Pilih Status</option>
                        @foreach($options['statusKKM'] as $label)
                            <option value="{{ $label }}" {{ $v('status_kkm') == $label ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('status_kkm')
                    <p class="text-sm mt-1" style="color: var(--danger);">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="form-group">
                    <label class="form-label">Akreditasi</label>
                    <input type="text" name="akreditasi" value="{{ $v('akreditasi') }}" class="form-input" placeholder="Contoh: A" maxlength="10">
                    @error('akreditasi')
                    <p class="text-sm mt-1" style="color: var(--danger);">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Tanggal Akreditasi</label>
                    <input type="date" name="tanggal_akreditasi" value="{{ $v('tanggal_akreditasi') }}" class="form-input">
                    @error('tanggal_akreditasi')
                    <p class="text-sm mt-1" style="color: var(--danger);">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="form-group">
                    <label class="form-label">SK Pendirian</label>
                    <input type="text" name="sk_pendirian" value="{{ $v('sk_pendirian') }}" class="form-input" placeholder="Nomor SK">
                    @error('sk_pendirian')
                    <p class="text-sm mt-1" style="color: var(--danger);">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Tanggal SK</label>
                    <input type="date" name="tanggal_sk" value="{{ $v('tanggal_sk') }}" class="form-input">
                    @error('tanggal_sk')
                    <p class="text-sm mt-1" style="color: var(--danger);">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Visi</label>
                <textarea name="visi" rows="2" class="form-input" style="height: auto; padding: 10px 12px;" placeholder="Visi lembaga">{{ $v('visi') }}</textarea>
                @error('visi')
                <p class="text-sm mt-1" style="color: var(--danger);">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Alamat & Lokasi -->
    <div class="card">
        <div class="card-header">
            <div class="flex items-center gap-3">
                <div class="stat-icon cyan" style="width: 36px; height: 36px;">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 12.414a4 4 0 00-5.657 0L4 16.343V20h3.657l3.757-3.757a2 2 0 012.829 2.829L11 21.414V20h4l1.657-3.343zM7.5 9a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="card-title">Alamat &amp; Lokasi</h3>
                    <p class="text-sm text-muted">Alamat lengkap dan titik lokasi</p>
                </div>
            </div>
        </div>
        <div class="card-body space-y-4">
            <div class="form-group">
                <label class="form-label">Jalan</label>
                <input type="text" name="jalan" value="{{ $v('jalan') }}" class="form-input" placeholder="Nama jalan / dusun">
                @error('jalan')
                <p class="text-sm mt-1" style="color: var(--danger);">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="form-group">
                    <label class="form-label">Jorong</label>
                    <input type="text" name="jorong" value="{{ $v('jorong') }}" class="form-input" placeholder="Jorong">
                    @error('jorong')
                    <p class="text-sm mt-1" style="color: var(--danger);">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Nagari</label>
                    <input type="text" name="nagari" value="{{ $v('nagari') }}" class="form-input" placeholder="Nagari">
                    @error('nagari')
                    <p class="text-sm mt-1" style="color: var(--danger);">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="form-group">
                    <label class="form-label">Kecamatan</label>
                    <input type="text" name="kecamatan" value="{{ $v('kecamatan') }}" class="form-input" placeholder="Kecamatan">
                    @error('kecamatan')
                    <p class="text-sm mt-1" style="color: var(--danger);">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Koordinat</label>
                    <input type="text" name="koordinat" value="{{ $v('koordinat') }}" class="form-input" placeholder="Contoh: -0.4723, 100.6052">
                    @error('koordinat')
                    <p class="text-sm mt-1" style="color: var(--danger);">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div class="form-group">
                    <label class="form-label">Latitude</label>
                    <input type="number" step="any" name="latitude" value="{{ $v('latitude') }}" class="form-input" placeholder="-0.472370">
                    @error('latitude')
                    <p class="text-sm mt-1" style="color: var(--danger);">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Longitude</label>
                    <input type="number" step="any" name="longitude" value="{{ $v('longitude') }}" class="form-input" placeholder="100.605256">
                    @error('longitude')
                    <p class="text-sm mt-1" style="color: var(--danger);">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Radius (meter)</label>
                    <input type="number" name="radius" value="{{ $v('radius') }}" class="form-input" placeholder="200">
                    @error('radius')
                    <p class="text-sm mt-1" style="color: var(--danger);">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Gateway IP</label>
                <input type="text" name="gateway_ip" value="{{ $v('gateway_ip') }}" class="form-input" placeholder="127.0.0.1">
                @error('gateway_ip')
                <p class="text-sm mt-1" style="color: var(--danger);">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Kontak & Operasional -->
    <div class="card">
        <div class="card-header">
            <div class="flex items-center gap-3">
                <div class="stat-icon emerald" style="width: 36px; height: 36px;">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="card-title">Kontak &amp; Operasional</h3>
                    <p class="text-sm text-muted">Kontak dan jam kerja unit</p>
                </div>
            </div>
        </div>
        <div class="card-body space-y-4">
            <div class="form-group">
                <label class="form-label">Telepon</label>
                <input type="text" name="telepon" value="{{ $v('telepon') }}" class="form-input" placeholder="Contoh: 0752-123456">
                @error('telepon')
                <p class="text-sm mt-1" style="color: var(--danger);">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" value="{{ $v('email') }}" class="form-input" placeholder="email@example.com">
                @error('email')
                <p class="text-sm mt-1" style="color: var(--danger);">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Website</label>
                <input type="text" name="website" value="{{ $v('website') }}" class="form-input" placeholder="https://contoh.go.id">
                @error('website')
                <p class="text-sm mt-1" style="color: var(--danger);">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="form-group">
                    <label class="form-label">Jam Masuk</label>
                    <input type="time" name="jam_masuk" value="{{ $v('jam_masuk') }}" class="form-input">
                    @error('jam_masuk')
                    <p class="text-sm mt-1" style="color: var(--danger);">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Jam Pulang</label>
                    <input type="time" name="jam_pulang" value="{{ $v('jam_pulang') }}" class="form-input">
                    @error('jam_pulang')
                    <p class="text-sm mt-1" style="color: var(--danger);">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Jumlah Hari Kerja</label>
                <input type="number" name="hari_kerja" value="{{ $v('hari_kerja', 5) }}" min="1" max="7" class="form-input" placeholder="5">
                @error('hari_kerja')
                <p class="text-sm mt-1" style="color: var(--danger);">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
</div>

<!-- Data Jarak -->
<div class="card mt-6">
    <div class="card-header">
        <div class="flex items-center gap-3">
            <div class="stat-icon blue" style="width: 36px; height: 36px;">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894l9 4.5a1 1 0 010 1.788l-9 4.5a1 1 0 01-.447.1zM15 7l2 2m4 0l-6 6"/>
                </svg>
            </div>
            <div>
                <h3 class="card-title">Data Jarak Terdekat</h3>
                <p class="text-sm text-muted">Jarak dari unit kerja ke kantor/lembaga terdekat</p>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="grid gap-4 md:grid-cols-3 lg:grid-cols-4">
            @foreach([
                'jarak_pusat_provinsi' => 'Pusat Provinsi',
                'jarak_pusat_kabupaten' => 'Pusat Kabupaten',
                'jarak_kecamatan' => 'Pusat Kecamatan',
                'jarak_kanwil_kemenag' => 'Kanwil Kemenag',
                'jarak_kemenag_kab' => 'Kemenag Kabupaten',
                'jarak_kua' => 'KUA Terdekat',
                'jarak_ra_terdekat' => 'RA Terdekat',
                'jarak_mi_terdekat' => 'MI Terdekat',
                'jarak_mts_terdekat' => 'MTs Terdekat',
                'jarak_ma_terdekat' => 'MA Terdekat',
                'jarak_pontren_terdekat' => 'Pontren Terdekat',
                'jarak_tk_terdekat' => 'TK Terdekat',
                'jarak_sd_terdekat' => 'SD Terdekat',
                'jarak_smp_terdekat' => 'SMP Terdekat',
                'jarak_sma_terdekat' => 'SMA Terdekat',
            ] as $field => $label)
                <div class="form-group">
                    <label class="form-label">{{ $label }}</label>
                    <input type="text" name="{{ $field }}" value="{{ $v($field) }}" class="form-input" placeholder="Contoh: 12 km">
                    @error($field)
                    <p class="text-sm mt-1" style="color: var(--danger);">{{ $message }}</p>
                    @enderror
                </div>
            @endforeach
        </div>
    </div>
</div>
