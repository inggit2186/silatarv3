<x-layouts.app title="Pelayanan - SILATAR">
    <!-- Success Notification -->
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => { show = false }, 4000);" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-4" class="fixed top-24 right-4 z-50 w-80">
        <div class="neo-card border-gold">
            <div class="neo-success-alert">
                <div class="neo-success-alert-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div class="neo-success-alert-content">
                    <p class="neo-success-alert-label">Success</p>
                    <p class="neo-success-alert-title">Berhasil</p>
                    <p class="neo-success-alert-text">{{ session('success') }}</p>
                </div>
                <button @click="show = false" class="neo-success-alert-close">×</button>
            </div>
        </div>
    </div>
    @endif

    <main class="neo-mirai"
        x-data="{
            units: {{ json_encode($kantorUnits) }},
            selectedUnitId: null,
            generalServices: {{ json_encode($generalServices) }},
            specialServicesByUnit: {{ json_encode($specialServicesByUnit) }},
            leaders: [],
            unitEmployees: [],
            loadingEmployees: false,
            requestBaseUrl: '{{ url('/pelayanan/ajukan') }}',
            selectedService: null,
            selectedEmployee: null,
            showEmployeeModal: false,
            showPengaduanModal: false,
            selectedTahunPelajaran: '',
            selectedSemester: '',
            selectedBulan: '',
            selectedTahun: '',
            tahunPelajaranOptions: [
                @php
                    $currentYear = (int) date('Y');
                    for ($y = $currentYear; $y >= $currentYear - 5; $y--) {
                        echo "'{$y}/" . ($y + 1) . "', ";
                    }
                @endphp
            ],
            tahunOptions: [
                @php
                    $currentYear = (int) date('Y');
                    for ($y = $currentYear; $y >= $currentYear - 5; $y--) {
                        echo "'{$y}', ";
                    }
                @endphp
            ],
            semesterOptions: ['Ganjil', 'Genap'],
            bulanOptions: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
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
                }
                if (service.id === 1037) {
                    this.selectedTahunPelajaran = this.tahunPelajaranOptions[0] || '';
                    this.selectedSemester = this.semesterOptions[0] || '';
                } else if (service.id === 1038 || service.id === 1081 || service.id === 1082) {
                    this.selectedTahun = this.tahunOptions[0] || '';
                    this.selectedBulan = this.bulanOptions[0] || '';
                } else {
                    this.selectedTahunPelajaran = '';
                    this.selectedSemester = '';
                    this.selectedBulan = '';
                    this.selectedTahun = '';
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
            submitTpgSelection() {
                if (!this.selectedService || !this.selectedTahunPelajaran || !this.selectedSemester) return;
                const url = `${this.requestBaseUrl}/${this.selectedService.id}?tahun_pelajaran=${encodeURIComponent(this.selectedTahunPelajaran)}&semester=${encodeURIComponent(this.selectedSemester)}`;
                window.location.href = url;
            },
            proceedToRequest() {
                if (!this.selectedService) return;
                if (this.selectedService.id === 1037) {
                    if (!this.selectedTahunPelajaran || !this.selectedSemester) return;
                    const url = `${this.requestBaseUrl}/${this.selectedService.id}?tahun_pelajaran=${encodeURIComponent(this.selectedTahunPelajaran)}&semester=${encodeURIComponent(this.selectedSemester)}`;
                    window.location.href = url;
                } else if (this.selectedService.id === 1038 || this.selectedService.id === 1081 || this.selectedService.id === 1082) {
                    if (!this.selectedTahun || !this.selectedBulan) return;
                    const url = `${this.requestBaseUrl}/${this.selectedService.id}?tahun=${encodeURIComponent(this.selectedTahun)}&bulan=${encodeURIComponent(this.selectedBulan)}`;
                    window.location.href = url;
                } else {
                    window.location.href = `${this.requestBaseUrl}/${this.selectedService.id}`;
                }
            },
            closeEmployeeModal() { this.showEmployeeModal = false; this.selectedEmployee = null; },
            closeServiceModal() { this.selectedService = null; this.selectedTahunPelajaran = ''; this.selectedSemester = ''; },
            closePengaduanModal() { this.showPengaduanModal = false; },
            openPengaduanLink(url) { window.open(url, '_blank'); this.showPengaduanModal = false; }
        }"
        @keydown.escape.window="closeEmployeeModal(); closeServiceModal(); closePengaduanModal();"
    >
        <!-- Hero Section -->
        <section class="hero-page bg-cover bg-center" style="background-image: url('/assets/img/template/layanan-bg.webp'); padding: 120px 2rem 4rem; min-height: 350px;">
            <div class="hero-page-content">
                <p class="section-label-gold section-label-sm">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Pelayanan SILATAR
                </p>
                <h1 class="hero-page-title">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="1.5"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9,22 9,12 15,12 15,22"/></svg>
                    Pilih unit kerja, lalu pilih layanan
                </h1>
                <p class="text-ink-soft text-base mx-auto max-w-md">Halaman ini memisahkan alur pelayanan dari beranda. Pilih unit kerja kantor aktif terlebih dahulu, lalu semua layanan akan muncul dalam bentuk kartu sesuai section-nya.</p>
                <div class="hero-actions">
                    <a href="{{ url('/') }}" class="neo-hero-cta">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                        Beranda
                    </a>
                    <a href="{{ route('satuan-kerja', ['tab' => 'kantor']) }}" class="neo-hero-cta neo-hero-cta-primary">
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
                        <p class="section-label-gold section-label-sm">Langkah 1</p>
                        <p class="text-sm text-ink-soft">Pilih unit kerja kantor yang aktif untuk membuka daftar layanan pada unit tersebut.</p>
                    </div>
                    <span class="neo-card-badge">{{ $kantorUnits->count() }} unit aktif</span>
                </div>

                <div class="neo-grid neo-grid-unit-lg mt-6">
                    @forelse ($kantorUnits as $card)
                        <button type="button" @click="selectUnit({{ $card['id'] }})" class="neo-unit-card cursor-pointer">
                            <div class="neo-unit-card-visual">
                                @if($card['cover_path'])
                                    <img src="{{ $card['cover_path'] }}" alt="{{ $card['title'] }}" class="neo-unit-card-img">
                                @else
                                    <div class="neo-unit-card-placeholder">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z"/>
                                        </svg>
                                    </div>
                                @endif
                                <div class="neo-unit-card-overlay"></div>
                                <div class="neo-unit-card-header">
                                    <span class="neo-unit-card-badge">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="12" height="12"><path d="M19 21V5a2 2 0 00-2-2H3a2 2 0 00-2 2v16m14 0H5m14 0h2m-2 0h-2M5 21h2m-2 0H3m14 0h2m-2 0h-2M7 7h10M7 11h10M7 15h6"/></svg>
                                        Unit Kerja
                                    </span>
                                </div>
                                <div class="neo-unit-card-footer">
                                    <h3 class="neo-unit-card-title">{{ $card['title'] }}</h3>
                                </div>
                            </div>
                            <div class="neo-unit-card-leader-section">
                                <div class="neo-unit-card-leader-photo">
                                    @if(!empty($card['head_photo']))
                                        <img src="{{ $card['head_photo'] }}" alt="{{ $card['head_value'] }}" class="neo-unit-card-leader-img">
                                    @else
                                        <div class="neo-unit-card-leader-initials">{{ $card['head_initials'] ?? '' }}</div>
                                    @endif
                                </div>
                                <div class="neo-unit-card-leader-info">
                                    <span class="neo-unit-card-leader-jabatan">{{ $card['head_label'] }}</span>
                                    <span class="neo-unit-card-leader-name">{{ $card['head_value'] ?? 'Vacant' }}</span>
                                </div>
                            </div>
                            <div class="neo-unit-card-stats">
                                <div class="neo-unit-card-stat">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                                    </svg>
                                    <div>
                                        <span class="neo-unit-card-stat-value">{{ $card['extra_value'] }}</span>
                                        <span class="neo-unit-card-stat-label">Pegawai Aktif</span>
                                    </div>
                                </div>
                                <div class="neo-unit-card-action">
                                    <span>Pilih Unit</span>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75"/></svg>
                                </div>
                            </div>
                        </button>
                    @empty
                        <div class="neo-empty col-full">
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
                        <p class="section-label-gold section-label-sm">Langkah 2</p>
                        <h2 class="neo-card-title" x-text="selectedUnit ? selectedUnit.title : ''"></h2>
                        <p class="text-sm text-ink-soft mt-1">Semua layanan untuk unit ini langsung ditampilkan sebagai kartu.</p>
                    </div>
                    <button type="button" @click="changeUnit()" class="neo-btn-secondary whitespace-nowrap">Ganti unit</button>
                </div>

                <div class="mt-8">
                    <!-- General Services -->
                    <div class="mb-8">
                        <h3 class="section-heading">Layanan Umum</h3>
                        <div class="neo-grid neo-grid-3">
                            @foreach($generalServices as $service)
                                <button type="button" @click="selectService('umum', @js($service))" class="neo-service-card cursor-pointer">
                                    <div class="neo-service-cover">
                                        <img src="{{ $service['cover_path'] }}" alt="{{ $service['title'] }}" class="neo-service-img">
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
                        <h3 class="section-heading">Layanan Khusus</h3>
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
                                <button type="button" @click="selectService('khusus', service)" class="neo-service-card cursor-pointer">
                                    <div class="neo-service-cover">
                                        <img :src="service.cover_path" :alt="service.title" class="neo-service-img">
                                        <div class="neo-service-cover-overlay"></div>
                                        <span class="neo-service-tag" x-text="service.tag || 'Layanan Khusus'"></span>
                                        <span x-show="service.is_spesial" class="neo-service-tag neo-service-tag-special" style="background: var(--sun);">Spesial</span>
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
                <!-- Header -->
                <div class="neo-modal-header">
                    <div>
                        <h3 class="neo-modal-title" x-text="selectedService ? selectedService.title : '-'"></h3>
                        <p class="text-sm text-ink-soft" x-text="selectedService ? selectedService.description : ''"></p>
                    </div>
                    <button type="button" @click="closeServiceModal()" class="neo-modal-close">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Meta Info -->
                <div class="neo-modal-meta">
                    <div class="neo-modal-meta-item">
                        <span class="neo-modal-meta-label">Jenis</span>
                        <span class="neo-modal-meta-value" x-text="selectedService && selectedService.kind === 'umum' ? 'Layanan Umum' : 'Layanan Khusus'"></span>
                    </div>
                    <div class="neo-modal-meta-item">
                        <span class="neo-modal-meta-label">Waktu</span>
                        <span class="neo-modal-meta-value" x-text="selectedService ? (selectedService.waktu || '-') : '-'"></span>
                    </div>
                    <div class="neo-modal-meta-item">
                        <span class="neo-modal-meta-label">Biaya</span>
                        <span class="neo-modal-meta-value" x-text="selectedService ? (selectedService.biaya || 'Gratis') : '-'"></span>
                    </div>
                </div>

                <!-- TPG Semester Selection Section (for service 1037) -->
                <div x-show="selectedService && selectedService.id === 1037" x-cloak class="neo-modal-section neo-modal-section-accent">
                    <div class="neo-modal-section-header">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/>
                            <line x1="8" y1="2" x2="8" y2="6"/>
                            <line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                        <span>Pilih Periode Pencairan</span>
                    </div>
                    <div class="neo-modal-tpg-grid">
                        <div class="neo-modal-field">
                            <label for="tahun_pelajaran" class="neo-modal-label">Tahun Pelajaran</label>
                            <select x-model="selectedTahunPelajaran" id="tahun_pelajaran" class="neo-form-select">
                                <template x-for="tahun in tahunPelajaranOptions" :key="tahun">
                                    <option :value="tahun" x-text="tahun"></option>
                                </template>
                            </select>
                        </div>
                        <div class="neo-modal-field">
                            <label for="semester" class="neo-modal-label">Semester</label>
                            <select x-model="selectedSemester" id="semester" class="neo-form-select">
                                <template x-for="sem in semesterOptions" :key="sem">
                                    <option :value="sem" x-text="sem"></option>
                                </template>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- TPG Bulanan Selection Section (for service 1038, 1081, 1082) -->
                <div x-show="selectedService && (selectedService.id === 1038 || selectedService.id === 1081 || selectedService.id === 1082)" x-cloak class="neo-modal-section neo-modal-section-accent">
                    <div class="neo-modal-section-header">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/>
                            <line x1="8" y1="2" x2="8" y2="6"/>
                            <line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                        <span>Pilih Periode Pencairan</span>
                    </div>
                    <div class="neo-modal-tpg-grid">
                        <div class="neo-modal-field">
                            <label for="tahun" class="neo-modal-label">Tahun</label>
                            <select x-model="selectedTahun" id="tahun" class="neo-form-select">
                                <template x-for="tahun in tahunOptions" :key="tahun">
                                    <option :value="tahun" x-text="tahun"></option>
                                </template>
                            </select>
                        </div>
                        <div class="neo-modal-field">
                            <label for="bulan" class="neo-modal-label">Bulan</label>
                            <select x-model="selectedBulan" id="bulan" class="neo-form-select">
                                <template x-for="(bulan, index) in bulanOptions" :key="bulan">
                                    <option :value="bulan" x-text="bulan"></option>
                                </template>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Requirements Section - Card Style -->
                <div class="neo-modal-section">
                    <div class="neo-modal-section-header">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                        <span>Siapkan Dokumen Ini</span>
                    </div>
                    <div class="neo-modal-requirements-grid">
                        <template x-for="(req, index) in selectedServiceRequirements" :key="req.id">
                            <div class="neo-modal-req-card">
                                <div class="neo-modal-req-header">
                                    <div class="neo-modal-req-number" x-text="(index + 1).toString().padStart(2, '0')"></div>
                                    <span
                                        class="neo-modal-req-badge"
                                        :class="req.is_required ? 'neo-modal-req-wajib' : 'neo-modal-req-opsional'"
                                        x-text="req.is_required ? 'Wajib' : 'Opsional'"
                                    ></span>
                                </div>
                                <div class="neo-modal-req-content">
                                    <span class="neo-modal-req-title" x-text="req.title"></span>
                                    <span class="neo-modal-req-note" x-show="req.note" x-text="'(' + req.note + ')'"></span>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Action Button -->
                <div class="neo-modal-actions">
                    <button
                        type="button"
                        @click="proceedToRequest()"
                        :disabled="(selectedService && selectedService.id === 1037 && (!selectedTahunPelajaran || !selectedSemester)) || (selectedService && (selectedService.id === 1038 || selectedService.id === 1081 || selectedService.id === 1082) && (!selectedTahun || !selectedBulan))"
                        class="neo-btn-action"
                        :class="{ 'neo-btn-disabled': (selectedService && selectedService.id === 1037 && (!selectedTahunPelajaran || !selectedSemester)) || (selectedService && (selectedService.id === 1038 || selectedService.id === 1081 || selectedService.id === 1082) && (!selectedTahun || !selectedBulan)) }"
                    >
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 12h14M12 5l7 7-7 7"/>
                        </svg>
                        Ajukan Sekarang
                    </button>
                    <button type="button" @click="closeServiceModal()" class="neo-btn-cancel">Batal</button>
                </div>
            </div>
        </div>

        <!-- Employee Selection Modal -->
        <div x-show="showEmployeeModal" x-cloak class="neo-modal-backdrop" @click="closeEmployeeModal()">
            <div class="neo-modal neo-modal-sm" @click.stop>
                <div class="neo-modal-header">
                    <div>
                        <h3 class="neo-modal-title">Pilih Pegawai</h3>
                        <p class="text-sm text-ink-soft">Unit: <span x-text="selectedUnit ? selectedUnit.title : '-'"></span></p>
                    </div>
                    <button type="button" @click="closeEmployeeModal()" class="neo-modal-close">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <template x-if="loadingEmployees">
                    <div class="loading-text">
                        <p class="text-ink-soft">Memuat...</p>
                    </div>
                </template>
                <template x-if="!loadingEmployees">
                    <div>
                        <template x-if="leaders.length > 0">
                            <div class="mb-6">
                                <h4 class="section-label-gold section-label-left text-center">Pimpinan</h4>
                                <div class="neo-employee-grid neo-employee-grid-leader">
                                    <template x-for="leader in leaders" :key="leader.id">
                                        <button type="button" @click="selectEmployee(leader)" class="neo-employee-card neo-employee-card-center">
                                            <div class="neo-avatar neo-avatar-md" x-text="leader.avatar_text || leader.name?.substring(0,2).toUpperCase()"></div>
                                            <div class="neo-employee-info text-center">
                                                <p class="neo-employee-name" x-text="leader.name"></p>
                                                <p class="neo-employee-role" x-text="leader.role_label"></p>
                                            </div>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </template>
                        <button type="button" @click="goDirectlyToSeksi()" class="neo-btn-secondary w-full flex justify-center mb-4">
                            Langsung ke Seksi (Tanpa Pilih Pegawai)
                        </button>
                        <template x-if="unitEmployees.length > 0">
                            <div>
                                <h4 class="section-label-gold section-label-left text-center">Pegawai</h4>
                                <div class="neo-employee-grid">
                                    <template x-for="employee in unitEmployees" :key="employee.id">
                                        <button type="button" @click="selectEmployee(employee)" class="neo-employee-card">
                                            <div class="neo-avatar neo-avatar-sm" x-text="employee.avatar_text || employee.name?.substring(0,2).toUpperCase()"></div>
                                            <div class="neo-employee-info">
                                                <p class="neo-employee-name" x-text="employee.name"></p>
                                                <p class="neo-employee-role neo-employee-role-sm" x-text="employee.role_label"></p>
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
