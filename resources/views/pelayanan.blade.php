<x-layouts.app title="Pelayanan - SILATAR">
    <!-- Success Notification -->
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => { show = false }, 4000);" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-4" class="fixed top-24 right-4 z-50 w-80">
        <div class="neo-card" style="border-color: var(--gold);">
            <div style="display: flex; align-items: start; gap: 1rem;">
                <div style="width: 2.5rem; height: 2.5rem; background: var(--gold); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div style="flex: 1;">
                    <p style="font-family: var(--font-mono); font-size: 0.7rem; font-weight: 700; text-transform: uppercase; color: var(--gold); margin: 0 0 0.25rem;">Success</p>
                    <p style="font-weight: 600; color: var(--ink); margin: 0; font-size: 0.9rem;">Berhasil</p>
                    <p style="font-size: 0.8rem; color: var(--ink-soft); margin: 0.25rem 0 0;">{{ session('success') }}</p>
                </div>
                <button @click="show = false" style="background: transparent; border: 1px solid var(--line); width: 1.75rem; height: 1.75rem; display: flex; align-items: center; justify-content: center; cursor: pointer; color: var(--ink-soft);">×</button>
            </div>
        </div>
    </div>
    @endif

        <x-layouts.site-header />

    <main class="neo-mirai"
        x-data="{
            units: @js($kantorUnits),
            selectedUnitId: null,
            generalServices: @js($generalServices),
            specialServicesByUnit: @js($specialServicesByUnit),
            leaders: [],
            unitEmployees: [],
            loadingEmployees: false,
            requestBaseUrl: @js(url('/pelayanan/ajukan')),
            selectedService: null,
            selectedEmployee: null,
            showEmployeeModal: false,
            showPengaduanModal: false,
            get selectedUnit() {
                return this.units.find((unit) => Number(unit.id) === Number(this.selectedUnitId)) ?? null;
            },
            get specialServices() {
                if (! this.selectedUnitId) { return []; }
                return this.specialServicesByUnit[String(this.selectedUnitId)] ?? [];
            },
            get featuredSpecialService() {
                return this.specialServices.find((service) => service.is_spesial) ?? null;
            },
            get specialServicesGrid() {
                if (! this.featuredSpecialService) { return this.specialServices; }
                return this.specialServices.filter((service) => service.id !== this.featuredSpecialService.id);
            },
            get selectedServiceRequirements() {
                if (! this.selectedService) { return []; }
                return this.selectedService.requirements ?? [];
            },
            async selectUnit(unitId) {
                this.selectedUnitId = unitId;
                this.selectedService = null;
                this.leaders = [];
                this.unitEmployees = [];
                this.loadingEmployees = true;
                try {
                    const response = await fetch(`/pelayanan/unit/${unitId}/employees`);
                    const data = await response.json();
                    this.leaders = data.leaders || [];
                    this.unitEmployees = data.employees || [];
                } catch (e) { console.error('Failed to fetch employees', e); }
                this.loadingEmployees = false;
                this.$nextTick(() => { this.$refs.serviceFlow?.scrollIntoView({ behavior: 'smooth', block: 'start' }); });
            },
            changeUnit() {
                this.selectedUnitId = null;
                this.selectedService = null;
                this.selectedEmployee = null;
                this.showEmployeeModal = false;
                this.leaders = [];
                this.unitEmployees = [];
                this.$nextTick(() => { this.$refs.unitSelection?.scrollIntoView({ behavior: 'smooth', block: 'start' }); });
            },
            selectService(kind, service) {
                this.selectedService = service;
                this.showEmployeeModal = false;
                this.showPengaduanModal = false;
                if (service.key === 'janji-temu') {
                    this.showEmployeeModal = true;
                } else if (service.key === 'pengaduan') {
                    this.showPengaduanModal = true;
                } else {
                    if (service.id) { window.location.href = `/pelayanan/ajukan/${service.id}`; }
                }
            },
            selectEmployee(employee) {
                this.selectedEmployee = employee;
                this.showEmployeeModal = false;
                const deptId = this.selectedUnitId;
                window.location.href = `/pelayanan/janji-temu/${deptId}?employee_id=${employee.id}`;
            },
            goDirectlyToSeksi() {
                this.showEmployeeModal = false;
                const deptId = this.selectedUnitId;
                window.location.href = `/pelayanan/janji-temu/${deptId}?direct=1`;
            },
            closeEmployeeModal() { this.showEmployeeModal = false; this.selectedEmployee = null; },
            closeServiceModal() { this.selectedService = null; },
            closePengaduanModal() { this.showPengaduanModal = false; },
            openPengaduanLink(url) { window.open(url, '_blank'); this.showPengaduanModal = false; }
        }"
        @keydown.escape.window="closeEmployeeModal(); closeServiceModal(); closePengaduanModal();"
    >
        <!-- Hero Section -->
        <section class="hero-page" style="background-image: url('/assets/img/template/layanan-bg.webp'); background-size: cover; background-position: center top; padding: 120px 2rem 4rem; min-height: 350px;">
            <div class="hero-page-content" style="max-width: 36rem; text-align: center;">
                <p class="section-label" style="color: var(--gold); font-family: var(--font-mono); font-size: 0.65rem; text-transform: uppercase; margin: 0 0 0.5rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Pelayanan SILATAR
                </p>
                <h1 class="hero-page-title" style="font-family: var(--font-display); font-size: clamp(1.8rem, 4vw, 3rem); font-weight: 400; color: var(--ink); margin: 0 0 1rem; display: flex; align-items: center; justify-content: center; gap: 0.75rem;">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="1.5"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9,22 9,12 15,12 15,22"/></svg>
                    Pilih unit kerja, lalu pilih layanan
                </h1>
                <p style="color: var(--ink-soft); font-size: 1rem; max-width: 28rem; margin: 0 auto;">Halaman ini memisahkan alur pelayanan dari beranda. Pilih unit kerja kantor aktif terlebih dahulu, lalu semua layanan akan muncul dalam bentuk kartu sesuai section-nya.</p>
                <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 1rem; margin-top: 1.5rem;">
                    <a href="{{ url('/') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.7rem 1.25rem; background: transparent; color: var(--ink); font-family: var(--font-mono); font-size: 0.7rem; font-weight: 600; text-transform: uppercase; text-decoration: none; border: 1px solid var(--line);">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                        Beranda
                    </a>
                    <a href="{{ route('satuan-kerja', ['tab' => 'kantor']) }}" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.7rem 1.25rem; background: var(--gold); color: var(--night); font-family: var(--font-mono); font-size: 0.7rem; font-weight: 700; text-transform: uppercase; text-decoration: none;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21h18M9 8h1m-1 4h1m4-4h1m-1 4h1M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16"/></svg>
                        Direktori Kantor
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h12m-5-5 5 5-5 5"/></svg>
                    </a>
                </div>
            </div>
        </section>

        <!-- Section Divider -->
        <div class="section-divider wave-rounded"></div>

        <!-- Unit Selection Section -->
        <section x-ref="unitSelection" x-show="! selectedUnitId" x-cloak class="page-content">
            <div class="neo-card">
                <div class="neo-card-header">
                    <div>
                        <p class="section-label" style="font-size: 0.75rem; margin-bottom: 0.25rem;">Langkah 1</p>
                        <p class="text-sm" style="color: var(--ink-soft);">Pilih unit kerja kantor yang aktif untuk membuka daftar layanan pada unit tersebut.</p>
                    </div>
                    <span class="neo-card-badge">{{ $kantorUnits->count() }} unit aktif</span>
                </div>

                <div class="neo-grid neo-grid-3" style="margin-top: 1.5rem;">
                    @forelse ($kantorUnits as $card)
                        <button type="button" @click="selectUnit({{ $card['id'] }})" class="neo-service-card" style="cursor: pointer;">
                            <div class="neo-service-cover">
                                @if($card['cover_path'])
                                    <img src="{{ $card['cover_path'] }}" alt="{{ $card['title'] }}" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.style.display='none'">
                                @endif
                                <div class="neo-service-cover-overlay"></div>
                                <span class="neo-service-tag">Unit Kerja</span>
                            </div>
                            <div class="neo-service-body">
                                <h3 class="neo-service-title">{{ $card['title'] }}</h3>
                                @if(!empty($card['head_value']))
                                    <p style="font-size: 0.75rem; color: var(--ink-soft); margin: 0.5rem 0;">
                                        {{ $card['head_label'] }}: {{ $card['head_value'] }}
                                    </p>
                                @endif
                                <div class="neo-service-meta">
                                    <div class="neo-service-meta-item">
                                        <span class="neo-service-meta-label">Pegawai</span>
                                        <span class="neo-service-meta-value">{{ $card['extra_value'] }}</span>
                                    </div>
                                </div>
                                <span class="neo-btn" style="margin-top: 1rem; width: 100%; justify-content: center;">
                                    Pilih unit ini →
                                </span>
                            </div>
                        </button>
                    @empty
                        <div class="neo-empty" style="grid-column: 1 / -1;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H3a2 2 0 00-2 2v16m14 0H5m14 0h2m-2 0h-2M5 21h2m-2 0H3m14 0h2m-2 0h-2M7 7h10M7 11h10M7 15h6" />
                            </svg>
                            <p class="neo-empty-title">Belum ada unit kerja</p>
                            <p class="neo-empty-text">Belum ada unit kerja kantor dengan status aktif.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        <!-- Service Selection Section -->
        <section x-ref="serviceFlow" x-show="selectedUnit" x-cloak class="page-content">
            <div class="neo-card">
                <div class="neo-card-header">
                    <div>
                        <p class="section-label" style="font-size: 0.75rem; margin-bottom: 0.25rem;">Langkah 2</p>
                        <h2 class="neo-card-title" x-text="selectedUnit ? selectedUnit.title : ''"></h2>
                        <p class="text-sm" style="color: var(--ink-soft); margin-top: 0.25rem;">Semua layanan untuk unit ini langsung ditampilkan sebagai kartu.</p>
                    </div>
                    <button type="button" @click="changeUnit()" class="neo-btn-secondary" style="white-space: nowrap;">Ganti unit</button>
                </div>

                <div style="margin-top: 2rem;">
                    <!-- General Services -->
                    <div style="margin-bottom: 2rem;">
                        <h3 style="font-family: var(--font-display); font-size: 1rem; font-weight: 600; margin: 0 0 1rem; padding-bottom: 0.75rem; border-bottom: 1px solid var(--line);">Layanan Umum</h3>
                        <div class="neo-grid neo-grid-3">
                            @foreach($generalServices as $service)
                                <button type="button" @click="selectService('umum', @js($service))" class="neo-service-card" style="cursor: pointer;">
                                    <div class="neo-service-cover">
                                        <img src="{{ $service['cover_path'] }}" alt="{{ $service['title'] }}" style="width: 100%; height: 100%; object-fit: cover;">
                                        <div class="neo-service-cover-overlay"></div>
                                        <span class="neo-service-tag">{{ $service['tag'] }}</span>
                                    </div>
                                    <div class="neo-service-body">
                                        <h4 class="neo-service-title">{{ $service['title'] }}</h4>
                                        <p class="neo-service-desc">{{ $service['description'] }}</p>
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Special Services -->
                    <div>
                        <h3 style="font-family: var(--font-display); font-size: 1rem; font-weight: 600; margin: 0 0 1rem; padding-bottom: 0.75rem; border-bottom: 1px solid var(--line);">Layanan Khusus</h3>
                        <template x-if="specialServices.length === 0">
                            <div class="neo-empty">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="neo-empty-title">Belum ada layanan</p>
                                <p class="neo-empty-text">Belum ada layanan khusus pada unit kerja yang dipilih.</p>
                            </div>
                        </template>
                        <div class="neo-grid neo-grid-3">
                            <template x-for="service in specialServices" :key="service.id">
                                <button type="button" @click="selectService('khusus', service)" class="neo-service-card" style="cursor: pointer;">
                                    <div class="neo-service-cover">
                                        <img :src="service.cover_path" :alt="service.title" style="width: 100%; height: 100%; object-fit: cover;">
                                        <div class="neo-service-cover-overlay"></div>
                                        <span class="neo-service-tag" x-text="service.tag || 'Layanan Khusus'"></span>
                                        <span x-show="service.is_spesial" class="neo-service-tag" style="top: auto; bottom: 0.75rem; background: var(--sun);">Spesial</span>
                                    </div>
                                    <div class="neo-service-body">
                                        <h4 class="neo-service-title" x-text="service.title"></h4>
                                        <p class="neo-service-desc" x-text="service.description"></p>
                                        <div class="neo-service-meta">
                                            <div class="neo-service-meta-item">
                                                <span class="neo-service-meta-label">Waktu</span>
                                                <span class="neo-service-meta-value" x-text="service.waktu || '-'"></span>
                                            </div>
                                            <div class="neo-service-meta-item">
                                                <span class="neo-service-meta-label">Biaya</span>
                                                <span class="neo-service-meta-value" x-text="service.biaya || '-'"></span>
                                            </div>
                                        </div>
                                    </div>
                                </button>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Service Modal -->
        <div x-show="selectedService && selectedService.key !== 'janji-temu' && !showEmployeeModal" x-cloak class="neo-modal-backdrop" @click="closeServiceModal()">
            <div class="neo-modal" @click.stop>
                <div class="neo-modal-header">
                    <div>
                        <h3 class="neo-modal-title" x-text="selectedService ? selectedService.title : '-'"></h3>
                        <p class="text-sm" style="color: var(--ink-soft);" x-text="selectedService ? selectedService.description : ''"></p>
                    </div>
                    <button type="button" @click="closeServiceModal()" class="neo-modal-close">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div style="margin-bottom: 1.5rem;">
                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;">
                        <div style="padding: 0.75rem; background: var(--paper-soft); border: 1px solid var(--line);">
                            <p style="font-family: var(--font-mono); font-size: 0.6rem; text-transform: uppercase; color: var(--ink-soft); margin: 0 0 0.25rem;">Jenis</p>
                            <p style="font-weight: 600; margin: 0;" x-text="selectedService && selectedService.kind === 'umum' ? 'Layanan Umum' : 'Layanan Khusus'"></p>
                        </div>
                        <div style="padding: 0.75rem; background: var(--paper-soft); border: 1px solid var(--line);">
                            <p style="font-family: var(--font-mono); font-size: 0.6rem; text-transform: uppercase; color: var(--ink-soft); margin: 0 0 0.25rem;">Waktu</p>
                            <p style="font-weight: 600; margin: 0;" x-text="selectedService && selectedService.waktu ? selectedService.waktu : '-'"></p>
                        </div>
                        <div style="padding: 0.75rem; background: var(--paper-soft); border: 1px solid var(--line);">
                            <p style="font-family: var(--font-mono); font-size: 0.6rem; text-transform: uppercase; color: var(--ink-soft); margin: 0 0 0.25rem;">Biaya</p>
                            <p style="font-weight: 600; margin: 0;" x-text="selectedService && selectedService.biaya ? selectedService.biaya : '-'"></p>
                        </div>
                    </div>
                </div>
                <div style="margin-bottom: 1.5rem;">
                    <h4 style="font-family: var(--font-display); font-size: 0.9rem; font-weight: 600; margin: 0 0 0.75rem;">Syarat-syarat</h4>
                    <ul style="margin: 0; padding-left: 1.25rem;">
                        <template x-for="req in selectedServiceRequirements" :key="req.id">
                            <li style="margin-bottom: 0.5rem; color: var(--ink-soft); font-size: 0.85rem;" x-text="req.title"></li>
                        </template>
                    </ul>
                </div>
                <div style="display: flex; gap: 1rem;">
                    <a :href="selectedService ? requestBaseUrl + '/' + selectedService.id : '#'" class="neo-btn" style="flex: 1; justify-content: center;">Ajukan Sekarang</a>
                    <button type="button" @click="closeServiceModal()" class="neo-btn-secondary">Batal</button>
                </div>
            </div>
        </div>

        <!-- Employee Selection Modal -->
        <div x-show="showEmployeeModal" x-cloak class="neo-modal-backdrop" @click="closeEmployeeModal()">
            <div class="neo-modal" style="max-width: 36rem;" @click.stop>
                <div class="neo-modal-header">
                    <div>
                        <h3 class="neo-modal-title">Pilih Pegawai</h3>
                        <p class="text-sm" style="color: var(--ink-soft);">Unit: <span x-text="selectedUnit ? selectedUnit.title : '-'"></span></p>
                    </div>
                    <button type="button" @click="closeEmployeeModal()" class="neo-modal-close">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <template x-if="loadingEmployees">
                    <div style="text-align: center; padding: 2rem;">
                        <p style="color: var(--ink-soft);">Memuat...</p>
                    </div>
                </template>
                <template x-if="!loadingEmployees">
                    <div>
                        <template x-if="leaders.length > 0">
                            <div style="margin-bottom: 1.5rem;">
                                <h4 style="font-family: var(--font-mono); font-size: 0.7rem; text-transform: uppercase; text-align: center; color: var(--gold); margin: 0 0 1rem;">Pimpinan</h4>
                                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(8rem, 1fr)); gap: 0.75rem;">
                                    <template x-for="leader in leaders" :key="leader.id">
                                        <button type="button" @click="selectEmployee(leader)" style="display: flex; flex-direction: column; align-items: center; gap: 0.5rem; padding: 1rem; background: var(--paper-soft); border: 1px solid var(--line); cursor: pointer; transition: border-color 180ms;">
                                            <div style="width: 3rem; height: 3rem; border-radius: 50%; background: var(--gold); display: flex; align-items: center; justify-content: center; font-family: var(--font-mono); font-weight: 700; color: var(--night);" x-text="leader.avatar_text || leader.name?.substring(0,2).toUpperCase()"></div>
                                            <div style="text-align: center;">
                                                <p style="font-weight: 600; font-size: 0.85rem; margin: 0;" x-text="leader.name"></p>
                                                <p style="font-size: 0.7rem; color: var(--ink-soft); margin: 0.25rem 0 0;" x-text="leader.role_label"></p>
                                            </div>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </template>
                        <button type="button" @click="goDirectlyToSeksi()" class="neo-btn-secondary" style="width: 100%; justify-content: center; margin-bottom: 1rem;">
                            Langsung ke Seksi (Tanpa Pilih Pegawai)
                        </button>
                        <template x-if="unitEmployees.length > 0">
                            <div>
                                <h4 style="font-family: var(--font-mono); font-size: 0.7rem; text-transform: uppercase; text-align: center; color: var(--gold); margin: 0 0 1rem;">Pegawai</h4>
                                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(10rem, 1fr)); gap: 0.75rem;">
                                    <template x-for="employee in unitEmployees" :key="employee.id">
                                        <button type="button" @click="selectEmployee(employee)" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: var(--paper-soft); border: 1px solid var(--line); cursor: pointer; transition: border-color 180ms; text-align: left;">
                                            <div style="width: 2.5rem; height: 2.5rem; border-radius: 50%; background: var(--gold); display: flex; align-items: center; justify-content: center; font-family: var(--font-mono); font-weight: 700; color: var(--night); font-size: 0.8rem; flex-shrink: 0;" x-text="employee.avatar_text || employee.name?.substring(0,2).toUpperCase()"></div>
                                            <div style="min-width: 0;">
                                                <p style="font-weight: 600; font-size: 0.8rem; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" x-text="employee.name"></p>
                                                <p style="font-size: 0.65rem; color: var(--ink-soft); margin: 0.15rem 0 0;" x-text="employee.role_label"></p>
                                            </div>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>
            </div>
        </div>

        <!-- Footer -->
        <footer class="site-footer">
            <a class="brand-lockup brand-lockup-small" href="{{ url("/") }}" aria-label="SILATAR home">
                <span class="brand-mark" aria-hidden="true"><span></span></span>
                <span class="brand-word"><span>SILATAR</span><span>V2</span></span>
            </a>
            <p>Portal Layanan Digital Kementerian Agama Tanah Datar</p>
            <nav aria-label="Footer navigation">
                <a href="{{ url("/") }}">Beranda</a>
                <a href="{{ route('pelayanan') }}">Pelayanan</a>
                <a href="{{ route('satuan-kerja') }}">Unit Kerja</a>
                <a href="{{ route('news.index') }}">Berita</a>
            </nav>
            <div class="footer-copyright"><span>&copy; {{ date("Y") }} SILATAR - Kementerian Agama Tanah Datar</span></div>
        </footer>
    </main>
</x-layouts.app>
