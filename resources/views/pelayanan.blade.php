<x-layouts.app title="Pelayanan - SILATAR">
    <!-- Cyberpunk Success Notification -->
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="
            setTimeout(() => { show = false }, 4000);
        " x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-8 -translate-y-4" x-transition:enter-end="opacity-100 translate-x-0 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-x-0 translate-y-0" x-transition:leave-end="opacity-0 translate-x-8 -translate-y-4" class="fixed top-20 right-4 z-50 w-80">
            <div class="relative bg-[#0a0f1a] border-2 border-cyan-500 rounded-xl overflow-hidden shadow-[0_0_30px_rgba(0,212,255,0.3)]">
                <!-- Animated neon border top -->
                <div class="absolute top-0 left-0 right-0 h-[2px] bg-gradient-to-r from-transparent via-cyan-400 to-transparent animate-pulse"></div>

                <!-- Corner decorations -->
                <div class="absolute top-0 right-0 w-16 h-16 bg-gradient-to-bl from-cyan-500/20 to-transparent"></div>
                <div class="absolute bottom-0 left-0 w-12 h-12 bg-gradient-to-tr from-purple-500/20 to-transparent"></div>

                <div class="relative p-4">
                    <div class="flex items-start gap-3">
                        <!-- Icon with glow -->
                        <div class="relative flex-shrink-0">
                            <div class="absolute inset-0 bg-cyan-400 blur-lg opacity-50"></div>
                            <div class="relative w-12 h-12 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-lg flex items-center justify-center shadow-[0_0_20px_rgba(0,212,255,0.5)]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-slate-900" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="px-2 py-0.5 bg-cyan-500/20 border border-cyan-500/30 rounded text-[10px] font-bold text-cyan-400 uppercase tracking-wider">SUCCESS</span>
                            </div>
                            <p class="text-white font-bold text-sm">Janji Temu Diajukan!</p>
                            <p class="text-slate-400 text-xs mt-1 leading-relaxed">{{ session('success') }}</p>
                        </div>

                        <button @click="show = false" class="flex-shrink-0 w-7 h-7 bg-slate-800/50 hover:bg-slate-700/50 border border-slate-600/30 rounded-lg flex items-center justify-center transition-all hover:border-cyan-500/50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Animated scan line -->
                <div class="absolute inset-0 pointer-events-none overflow-hidden">
                    <div class="absolute w-full h-[2px] bg-gradient-to-r from-transparent via-cyan-400/50 to-transparent" style="animation: scanDown 2s linear infinite;"></div>
                </div>
            </div>
        </div>
    @endif

    <main
        class="relative cyber-bg"
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
            pengaduanOptions: [
                {
                    key: 'whistleblowing',
                    title: 'WHISTLEBLOWING',
                    description: 'Sistem pelaporan gratifikasi dan korupsi di Kementerian Agama',
                    cover_path: '{{ asset('assets/img/ikon/510.png') }}',
                    url: 'https://www.kemenag.go.id/whistleblowing'
                },
                {
                    key: 'lapor',
                    title: 'LAPOR.GO.ID',
                    description: 'Layanan aspirasi dan pengaduan masyarakat ke pemerintah',
                    cover_path: '{{ asset('assets/img/ikon/509.png') }}',
                    url: 'https://lapor.go.id'
                }
            ],
            get selectedUnit() {
                return this.units.find((unit) => Number(unit.id) === Number(this.selectedUnitId)) ?? null;
            },
            get specialServices() {
                if (! this.selectedUnitId) {
                    return [];
                }
                return this.specialServicesByUnit[String(this.selectedUnitId)] ?? [];
            },
            get featuredSpecialService() {
                return this.specialServices.find((service) => service.is_spesial) ?? null;
            },
            get specialServicesGrid() {
                if (! this.featuredSpecialService) {
                    return this.specialServices;
                }
                return this.specialServices.filter((service) => service.id !== this.featuredSpecialService.id);
            },
            get selectedServiceRequirements() {
                if (! this.selectedService) {
                    return [];
                }
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
                } catch (e) {
                    console.error('Failed to fetch employees', e);
                }
                this.loadingEmployees = false;
                this.$nextTick(() => {
                    this.$refs.serviceFlow?.scrollIntoView({ behavior: 'smooth', block: 'start' });
                });
            },
            changeUnit() {
                this.selectedUnitId = null;
                this.selectedService = null;
                this.selectedEmployee = null;
                this.showEmployeeModal = false;
                this.leaders = [];
                this.unitEmployees = [];
                this.$nextTick(() => {
                    this.$refs.unitSelection?.scrollIntoView({ behavior: 'smooth', block: 'start' });
                });
            },
            selectService(kind, service) {
                this.selectedService = service;
                this.showEmployeeModal = false;
                this.showPengaduanModal = false;
                // Check if this is Janji Temu - show employee selection modal
                if (service.key === 'janji-temu') {
                    this.showEmployeeModal = true;
                } else if (service.key === 'pengaduan') {
                    // Show pengaduan options modal
                    this.showPengaduanModal = true;
                } else {
                    // Navigate to regular service request
                    if (service.id) {
                        window.location.href = `/pelayanan/ajukan/${service.id}`;
                    }
                }
            },
            selectEmployee(employee) {
                this.selectedEmployee = employee;
                this.showEmployeeModal = false;
                // Navigate to appointment form
                const deptId = this.selectedUnitId;
                window.location.href = `/pelayanan/janji-temu/${deptId}?employee_id=${employee.id}`;
            },
            goDirectlyToSeksi() {
                this.showEmployeeModal = false;
                // Navigate to appointment form with direct flag
                const deptId = this.selectedUnitId;
                window.location.href = `/pelayanan/janji-temu/${deptId}?direct=1`;
            },
            closeEmployeeModal() {
                this.showEmployeeModal = false;
                this.selectedEmployee = null;
            },
            closeServiceModal() {
                this.selectedService = null;
            },
            closePengaduanModal() {
                this.showPengaduanModal = false;
            },
            openPengaduanLink(url) {
                window.open(url, '_blank');
 this.showPengaduanModal = false;
            }
        }"
        @keydown.escape.window="closeEmployeeModal(); closeServiceModal(); closePengaduanModal();"
    >
        <x-ui.page-hero badge="Pelayanan SILATAR" title="Pilih unit kerja, lalu pilih layanan">
            <p class="cyber-text-subtle max-w-3xl text-sm leading-7 sm:text-base">
                Halaman ini memisahkan alur pelayanan dari beranda. Pilih unit kerja kantor aktif terlebih dahulu,
                lalu semua layanan akan muncul dalam bentuk kartu sesuai section-nya.
            </p>
            <div class="mt-6 flex flex-wrap justify-center gap-3">
                <a href="{{ url('/') }}" class="cyber-btn-secondary">
                    Kembali ke beranda
                </a>
                <a href="{{ route('satuan-kerja', ['tab' => 'kantor']) }}" class="cyber-btn">
                    Buka direktori kantor
                </a>
            </div>
        </x-ui.page-hero>

        <section
            x-ref="unitSelection"
            x-show="! selectedUnitId"
            x-cloak
            class="mx-auto max-w-7xl px-6 pb-10 lg:px-8"
        >
            <div class="cyber-card">
                <div class="cyber-card-header">
                    <div class="flex items-center gap-3">
                        <div class="cyber-icon">
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H3a2 2 0 00-2 2v16m14 0H5m14 0h2m-2 0h-2M5 21h2m-2 0H3m14 0h2m-2 0h-2M7 7h10M7 11h10M7 15h6" />
                            </svg>
                        </div>
                        <div>
                            <p class="cyber-step-label">Langkah 1</p>
                            <p class="cyber-text-subtle text-sm">
                                Pilih unit kerja kantor yang aktif untuk membuka daftar layanan pada unit tersebut.
                            </p>
                        </div>
                    </div>
                    <span class="cyber-badge-cyber">
                        {{ $kantorUnits->count() }} unit aktif
                    </span>
                </div>

                <div class="mt-6 grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                    @forelse ($kantorUnits as $card)
                        <button
                            type="button"
                            @click="selectUnit({{ $card['id'] }})"
                            class="cyber-unit-card"
                        >
                            <div class="cyber-unit-cover">
                                <img
                                    src="{{ $card['cover_path'] }}"
                                    alt="{{ $card['title'] }}"
                                    loading="lazy"
                                    decoding="async"
                                    class="cyber-unit-cover-img"
                                    onerror="this.style.display='none'"
                                >
                                <div class="cyber-unit-cover-overlay"></div>
                                <div class="cyber-unit-cover-chip">
                                    Unit Kerja Kantor
                                </div>
                                <div class="cyber-unit-cover-label">
                                    <p class="cyber-unit-cover-label-text">
                                        {{ $card['title'] }}
                                    </p>
                                </div>
                            </div>

                            <div class="cyber-unit-body">
                                @if (! empty($card['head_photo']) || ! empty($card['head_value']))
                                    <div class="cyber-unit-head-profile">
                                        <div class="cyber-unit-head-photo">
                                            @if (! empty($card['head_photo']))
                                                <img
                                                    src="{{ $card['head_photo'] }}"
                                                    alt="{{ $card['head_value'] }}"
                                                    class="cyber-unit-head-img"
                                                    onerror="this.style.display='none'; this.parentElement.textContent='{{ $card['head_initials'] }}';"
                                                >
                                            @else
                                                <span class="cyber-unit-head-initials">{{ $card['head_initials'] }}</span>
                                            @endif
                                        </div>
                                        <div class="cyber-unit-head-info">
                                            <p class="cyber-unit-head-label">{{ $card['head_label'] }}</p>
                                            <p class="cyber-unit-head-value">{{ $card['head_value'] }}</p>
                                        </div>
                                    </div>
                                @endif

                                <div class="cyber-unit-info">
                                    <span class="cyber-unit-meta-label">Pegawai</span>
                                    <span class="cyber-unit-meta-value">
                                        {{ $card['extra_value'] }}
                                    </span>
                                </div>

                                <div class="cyber-unit-footer">
                                    <span class="cyber-unit-subtitle">
                                        {{ $card['subtitle'] }}
                                    </span>
                                    <span class="cyber-unit-action">
                                        Pilih unit ini
                                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 5.5l4 4-4 4" />
                                        </svg>
                                    </span>
                                </div>
                            </div>

                            <div class="cyber-card-glow-line"></div>
                        </button>
                    @empty
                        <div class="cyber-empty-state">
                            <svg class="h-16 w-16 text-cyan-500/30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H3a2 2 0 00-2 2v16m14 0H5m14 0h2m-2 0h-2M5 21h2m-2 0H3m14 0h2m-2 0h-2M7 7h10M7 11h10M7 15h6" />
                            </svg>
                            <p class="cyber-empty-title">Belum ada unit kerja</p>
                            <p class="cyber-empty-text">Belum ada unit kerja kantor dengan status aktif.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        <section
            x-ref="serviceFlow"
            x-show="selectedUnit"
            x-cloak
            class="mx-auto max-w-7xl px-6 pb-10 lg:px-8"
        >
            <div class="cyber-card">
                <div class="cyber-card-header">
                    <div class="flex items-center gap-3">
                        <div class="cyber-icon">
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h8a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V6z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 6h2M9 10h2" />
                            </svg>
                        </div>
                        <div>
                            <p class="cyber-step-label">Langkah 2</p>
                            <h3 class="cyber-title-lg" x-text="selectedUnit ? selectedUnit.title : ''"></h3>
                            <p class="cyber-text-subtle text-sm mt-1">
                                Semua layanan untuk unit ini langsung ditampilkan sebagai kartu.
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="cyber-unit-badge" x-text="selectedUnit ? selectedUnit.title : '-'"></div>
                        <button
                            type="button"
                            @click="changeUnit()"
                            class="cyber-btn-secondary-sm"
                        >
                            Ganti unit
                        </button>
                    </div>
                </div>

                <div class="mt-6 space-y-6">
                    <section class="cyber-section">
                        <div class="cyber-section-header">
                            <div class="flex items-center gap-3">
                                <div class="cyber-section-icon">
                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="cyber-section-step">Section 1</p>
                                    <h3 class="cyber-section-title">Layanan umum</h3>
                                </div>
                            </div>
                            <span class="cyber-badge" x-text="generalServices.length + ' layanan'"></span>
                        </div>

                        <div class="mt-5 grid gap-5 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">
                            @foreach ($generalServices as $service)
                                <button
                                    type="button"
                                    @click="selectService('umum', @js($service))"
                                    class="cyber-service-card group"
                                    :class="selectedService && selectedService.kind === 'umum' && selectedService.key === '{{ $service['key'] }}'
                                        ? 'is-selected'
                                        : ''"
                                >
                                    <div class="cyber-service-cover">
                                        <img src="{{ $service['cover_path'] }}" alt="{{ $service['title'] }}" class="cyber-service-cover-img">
                                        <div class="cyber-service-cover-overlay"></div>
                                        <span class="cyber-service-tag">{{ $service['tag'] }}</span>
                                    </div>

                                    <div class="cyber-service-body">
                                        <h4 class="cyber-service-title">{{ $service['title'] }}</h4>
                                        <p class="cyber-service-desc">{{ $service['description'] }}</p>
                                    </div>

                                    <div class="cyber-service-hover-indicator">
                                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 5.5l4 4-4 4" />
                                        </svg>
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    </section>

                    <section class="cyber-section">
                        <div class="cyber-section-header">
                            <div class="flex items-center gap-3">
                                <div class="cyber-section-icon cyber-section-icon-special">
                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="cyber-section-step">Section 2</p>
                                    <h3 class="cyber-section-title">Layanan khusus</h3>
                                </div>
                            </div>
                            <span class="cyber-badge" x-text="specialServices.length + ' layanan'"></span>
                        </div>

                        <template x-if="featuredSpecialService">
                            <button
                                type="button"
                                @click="selectService('khusus', featuredSpecialService)"
                                class="cyber-featured-card group"
                            >
                                <div class="cyber-featured-badge">
                                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    Sorotan Utama
                                </div>

                                <div class="cyber-featured-content">
                                    <div class="cyber-featured-info">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="cyber-badge-special">Layanan spesial</span>
                                            <span
                                                class="cyber-badge-status"
                                                :class="featuredSpecialService.status_label === 'Aktif' ? 'cyber-status-active' : 'cyber-status-inactive'"
                                                x-text="featuredSpecialService.status_label"
                                            ></span>
                                        </div>

                                        <h4 class="cyber-featured-title" x-text="featuredSpecialService.title"></h4>
                                        <p class="cyber-featured-desc" x-text="featuredSpecialService.description"></p>

                                        <div class="cyber-featured-stats">
                                            <div class="cyber-stat-card">
                                                <p class="cyber-stat-label">Waktu</p>
                                                <p class="cyber-stat-value" x-text="featuredSpecialService.waktu"></p>
                                            </div>
                                            <div class="cyber-stat-card">
                                                <p class="cyber-stat-label">Biaya</p>
                                                <p class="cyber-stat-value" x-text="featuredSpecialService.biaya"></p>
                                            </div>
                                            <div class="cyber-stat-card">
                                                <p class="cyber-stat-label">Output</p>
                                                <p class="cyber-stat-value" x-text="featuredSpecialService.output"></p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="cyber-featured-image">
                                        <img :src="featuredSpecialService.cover_path" :alt="featuredSpecialService.title" class="cyber-featured-img">
                                        <div class="cyber-featured-img-overlay"></div>
                                    </div>
                                </div>

                                <div class="cyber-card-glow-line"></div>
                            </button>
                        </template>

                        <template x-if="specialServicesGrid.length">
                            <div class="mt-5 grid gap-5 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">
                                <template x-for="service in specialServicesGrid" :key="service.id">
                                    <button
                                        type="button"
                                        @click="selectService('khusus', service)"
                                        class="cyber-service-card group"
                                        :class="[
                                            selectedService && selectedService.kind === 'khusus' && selectedService.id === service.id ? 'is-selected' : '',
                                            service.is_spesial ? 'is-special' : ''
                                        ]"
                                    >
                                        <div class="cyber-service-cover">
                                            <img :src="service.cover_path" :alt="service.title" class="cyber-service-cover-img">
                                            <div
                                                class="cyber-service-cover-overlay"
                                                :class="service.is_spesial ? 'cyber-service-cover-overlay-special' : ''"
                                            ></div>
                                            <span class="cyber-service-tag">Layanan khusus</span>
                                            <span
                                                class="cyber-service-status"
                                                :class="service.status_label === 'Aktif' ? 'cyber-status-active' : 'cyber-status-inactive'"
                                                x-text="service.status_label"
                                            ></span>
                                            <span x-show="service.is_spesial" class="cyber-service-special-badge">
                                                Spesial
                                            </span>
                                        </div>

                                        <div class="cyber-service-body">
                                            <h4 class="cyber-service-title" x-text="service.title"></h4>
                                            <p class="cyber-service-desc" x-text="service.description"></p>

                                            <div class="cyber-service-meta">
                                                <div class="cyber-meta-item">
                                                    <span class="cyber-meta-label">Waktu</span>
                                                    <span class="cyber-meta-value" x-text="service.waktu"></span>
                                                </div>
                                                <div class="cyber-meta-item">
                                                    <span class="cyber-meta-label">Biaya</span>
                                                    <span class="cyber-meta-value" x-text="service.biaya"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="cyber-service-hover-indicator">
                                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 5.5l4 4-4 4" />
                                            </svg>
                                        </div>
                                    </button>
                                </template>
                            </div>
                        </template>

                        <div x-show="specialServices.length === 0" x-cloak class="cyber-empty-state mt-5">
                            <svg class="h-12 w-12 text-cyan-500/30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="cyber-empty-title">Belum ada layanan</p>
                            <p class="cyber-empty-text">Belum ada layanan khusus pada unit kerja yang dipilih.</p>
                        </div>
                    </section>
                </div>

                <div
                    x-ref="serviceModal"
                    x-show="selectedService && selectedService.key !== 'janji-temu' && !showEmployeeModal"
                    x-cloak
                    class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6"
                    role="dialog"
                    aria-modal="true"
                    x-transition.opacity.duration.180ms
                >
                    <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-md" @click="closeServiceModal()"></div>

                    <div class="relative w-full max-w-[calc(100vw-1.5rem)] lg:max-w-5xl">
                        <div class="cyber-modal-container">
                            <div class="cyber-modal" x-transition.scale.duration.220ms>
                                <div class="cyber-modal-glow cyber-modal-glow-tr"></div>
                                <div class="cyber-modal-glow cyber-modal-glow-bl"></div>

                                <div class="relative max-h-[calc(100vh-3rem)] overflow-y-auto overflow-x-hidden">
                                    <div class="cyber-modal-header">
                                        <div class="flex items-start justify-between gap-4">
                                            <div class="min-w-0">
                                                <div class="flex flex-wrap items-center gap-2">
                                                    <span class="cyber-badge-cyber">Detail layanan</span>
                                                    <span
                                                        class="cyber-badge-status"
                                                        :class="selectedService && selectedService.status_label === 'Aktif' ? 'cyber-status-active' : 'cyber-status-inactive'"
                                                        x-text="selectedService && selectedService.status_label ? selectedService.status_label : 'Aktif'"
                                                    ></span>
                                                    <span
                                                        class="cyber-badge-special"
                                                        x-text="selectedService && selectedService.is_spesial ? 'Sorotan' : 'Standar'"
                                                    ></span>
                                                </div>
                                                <h4 class="cyber-modal-title" x-text="selectedService ? selectedService.title : '-'"></h4>
                                                <p class="cyber-modal-desc" x-text="selectedService ? selectedService.description : ''"></p>
                                            </div>

                                            <button
                                                type="button"
                                                @click="closeServiceModal()"
                                                class="cyber-modal-close"
                                                aria-label="Tutup modal"
                                            >
                                                <span class="text-xl leading-none">&times;</span>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="relative grid gap-6 px-5 py-5 sm:px-6 lg:grid-cols-[minmax(0,1.12fr)_minmax(280px,0.88fr)] lg:gap-8 lg:px-8 lg:py-8">
                                        <div class="min-w-0 space-y-5">
                                            <div class="grid gap-3 sm:grid-cols-3">
                                                <div class="cyber-info-card">
                                                    <p class="cyber-info-label">Jenis</p>
                                                    <p class="cyber-info-value" x-text="selectedService && selectedService.kind === 'umum' ? 'Layanan umum' : 'Layanan khusus'"></p>
                                                </div>
                                                <div class="cyber-info-card">
                                                    <p class="cyber-info-label">Waktu</p>
                                                    <p class="cyber-info-value" x-text="selectedService && selectedService.waktu ? selectedService.waktu : '-'"></p>
                                                </div>
                                                <div class="cyber-info-card">
                                                    <p class="cyber-info-label">Biaya</p>
                                                    <p class="cyber-info-value" x-text="selectedService && selectedService.biaya ? selectedService.biaya : '-'"></p>
                                                </div>
                                            </div>

                                            <div class="cyber-requirements-card">
                                                <div class="cyber-requirements-header">
                                                    <div>
                                                        <p class="cyber-requirements-title">Syarat-syarat</p>
                                                        <p class="cyber-requirements-subtitle">Ringkasan syarat dari data layanan</p>
                                                    </div>
                                                    <span
                                                        class="cyber-badge"
                                                        x-text="selectedServiceRequirements.length + ' syarat'"
                                                    ></span>
                                                </div>

                                                <div class="mt-4 max-h-72 overflow-auto pr-1">
                                                    <ul class="space-y-3">
                                                        <template x-for="requirement in selectedServiceRequirements" :key="requirement.id">
                                                            <li class="cyber-requirement-item">
                                                                <span class="cyber-requirement-bullet">&bull;</span>
                                                                <div class="min-w-0 flex-1">
                                                                    <div class="flex flex-wrap items-center gap-2">
                                                                        <span class="cyber-requirement-title" x-text="requirement.title"></span>
                                                                        <span
                                                                            x-show="requirement.is_required"
                                                                            x-cloak
                                                                            class="cyber-badge-required"
                                                                        >
                                                                            Wajib
                                                                        </span>
                                                                        <span
                                                                            x-show="requirement.is_primary"
                                                                            x-cloak
                                                                            class="cyber-badge-special"
                                                                        >
                                                                            Utama
                                                                        </span>
                                                                    </div>
                                                                    <p x-show="requirement.note" x-cloak class="cyber-requirement-note" x-text="requirement.note"></p>
                                                                </div>
                                                            </li>
                                                        </template>
                                                    </ul>
                                                </div>

                                                <p x-show="selectedServiceRequirements.length === 0" x-cloak class="cyber-empty-inline">
                                                    Belum ada syarat yang terdaftar untuk layanan ini.
                                                </p>
                                            </div>
                                        </div>

                                        <div class="min-w-0 lg:sticky lg:top-6 self-start">
                                            <div class="cyber-modal-sidebar">
                                                <div class="cyber-sidebar-unit">
                                                    <div class="flex items-center gap-4">
                                                        <div class="cyber-sidebar-cover">
                                                            <img
                                                                :src="selectedService ? selectedService.cover_path : ''"
                                                                :alt="selectedService ? selectedService.title : ''"
                                                                class="cyber-sidebar-cover-img"
                                                            >
                                                        </div>
                                                        <div class="min-w-0">
                                                            <p class="cyber-sidebar-unit-label">Unit terpilih</p>
                                                            <p class="cyber-sidebar-unit-name" x-text="selectedUnit ? selectedUnit.title : '-'"></p>
                                                            <p class="cyber-sidebar-unit-status" x-text="selectedService && selectedService.status_label ? selectedService.status_label : 'Aktif'"></p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="cyber-sidebar-notice">
                                                    <p class="cyber-sidebar-notice-title">Sebelum mengajukan</p>
                                                    <p class="cyber-sidebar-notice-text">
                                                        Pastikan semua syarat sudah lengkap. Jika ada dokumen yang belum siap, tutup modal ini dulu dan lengkapi berkasnya.
                                                    </p>
                                                </div>

                                                <div class="cyber-sidebar-actions">
                                                    <a
                                                        :href="selectedService ? requestBaseUrl + '/' + selectedService.id : '#'"
                                                        class="cyber-btn w-full justify-center"
                                                    >
                                                        Ajukan Sekarang
                                                    </a>
                                                    <button
                                                        type="button"
                                                        @click="closeServiceModal()"
                                                        class="cyber-btn-secondary w-full justify-center"
                                                    >
                                                        Batal
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Employee Selection Modal for Janji Temu --}}
                <div
                    x-ref="employeeModal"
                    x-show="showEmployeeModal"
                    x-cloak
                    class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6"
                    role="dialog"
                    aria-modal="true"
                    x-transition.opacity.duration.180ms
                >
                    <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-md" @click="closeEmployeeModal()"></div>

                    <div class="relative w-full max-w-3xl">
                        <div class="cyber-modal-container">
                            <div class="cyber-modal" x-transition.scale.duration.220ms>
                                <div class="cyber-modal-glow cyber-modal-glow-tr"></div>
                                <div class="cyber-modal-glow cyber-modal-glow-bl"></div>

                                <div class="relative max-h-[calc(100vh-3rem)] overflow-y-auto overflow-x-hidden">
                                    <div class="cyber-modal-header">
                                        <div class="flex items-start justify-between gap-4">
                                            <div class="min-w-0">
                                                <div class="flex flex-wrap items-center gap-2">
                                                    <span class="cyber-badge-cyber">Janji Temu</span>
                                                </div>
                                                <h4 class="cyber-modal-title">Pilih Pegawai</h4>
                                                <p class="cyber-modal-desc">
                                                    Pilih pegawai yang ingin Anda buat janji temu pada unit <span x-text="selectedUnit ? selectedUnit.title : '-'"></span>
                                                </p>
                                            </div>

                                            <button
                                                type="button"
                                                @click="closeEmployeeModal()"
                                                class="cyber-modal-close"
                                                aria-label="Tutup modal"
                                            >
                                                <span class="text-xl leading-none">&times;</span>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="p-5">
                                        {{-- Prominent Leaders Section --}}
                                        <template x-if="!loadingEmployees && leaders.length > 0">
                                            <div class="mb-6">
                                                <p class="mb-4 text-center text-sm font-semibold uppercase tracking-[0.2em] text-cyan-400">
                                                    Pertemuan dengan Pimpinan
                                                </p>
                                                <div class="cyber-leader-prominent-grid">
                                                    <template x-for="leader in leaders" :key="leader.id">
                                                        <button
                                                            type="button"
                                                            @click="selectEmployee(leader)"
                                                            class="cyber-leader-prominent-card"
                                                        >
                                                            <div class="cyber-leader-prominent-photo">
                                                                <template x-if="leader.photo_path">
                                                                    <img :src="leader.photo_path" :alt="leader.name" class="cyber-leader-prominent-img">
                                                                </template>
                                                                <template x-if="!leader.photo_path">
                                                                    <span class="cyber-leader-prominent-initials" x-text="leader.avatar_text"></span>
                                                                </template>
                                                                <div class="cyber-leader-prominent-glow"></div>
                                                            </div>
                                                            <p class="cyber-leader-prominent-name" x-text="leader.name"></p>
                                                            <p class="cyber-leader-prominent-role">
                                                                <span x-show="leader.is_plt" class="text-amber-400">PLT </span>
                                                                <span x-text="leader.role_label"></span>
                                                            </p>
                                                        </button>
                                                    </template>
                                                </div>
                                            </div>
                                        </template>

                                        {{-- Langsung ke Seksi Option --}}
                                        <template x-if="!loadingEmployees">
                                            <div class="mb-6">
                                                <button
                                                    type="button"
                                                    @click="goDirectlyToSeksi()"
                                                    class="cyber-direct-btn"
                                                >
                                                    <img src="{{ asset('assets/img/ikon/505.png') }}" alt="Seksi" class="cyber-direct-btn-img">
                                                    <div class="text-left">
                                                        <p class="cyber-direct-btn-title">Langsung ke Seksi</p>
                                                        <p class="cyber-direct-btn-desc">Ajukan janji temu langsung ke bagian/unit kerja tanpa memilih pegawai tertentu</p>
                                                    </div>
                                                    <svg class="h-5 w-5 ml-auto flex-shrink-0" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 5.5l4 4-4 4" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </template>

                                        <template x-if="loadingEmployees">
                                            <div class="flex items-center justify-center py-10">
                                                <div class="h-8 w-8 animate-spin rounded-full border-2 border-cyan-500 border-t-transparent"></div>
                                                <span class="ml-3 text-cyan-400">Memuat daftar pegawai...</span>
                                            </div>
                                        </template>

                                        <template x-if="!loadingEmployees && leaders.length === 0 && unitEmployees.length === 0">
                                            <div class="cyber-empty-state">
                                                <svg class="h-12 w-12 text-cyan-500/30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                                </svg>
                                                <p class="cyber-empty-title">Belum ada pegawai</p>
                                                <p class="cyber-empty-text">Tidak ada pegawai yang tersedia pada unit ini.</p>
                                            </div>
                                        </template>

                                        <template x-if="!loadingEmployees && unitEmployees.length > 0">
                                            <div>
                                                <p class="mb-4 text-center text-sm font-semibold uppercase tracking-[0.2em] text-slate-400">
                                                    Pertemuan dengan Pegawai
                                                </p>
                                                <div class="cyber-employee-select-grid">
                                                    <template x-for="employee in unitEmployees" :key="employee.id">
                                                        <button
                                                            type="button"
                                                            @click="selectEmployee(employee)"
                                                            class="cyber-employee-select-card"
                                                        >
                                                            <div class="cyber-employee-select-photo">
                                                                <template x-if="employee.photo_path">
                                                                    <img :src="employee.photo_path" :alt="employee.name" class="cyber-employee-select-img">
                                                                </template>
                                                                <template x-if="!employee.photo_path">
                                                                    <span class="cyber-employee-select-initials" x-text="employee.avatar_text"></span>
                                                                </template>
                                                            </div>
                                                            <div class="cyber-employee-select-info">
                                                                <p class="cyber-employee-select-name" x-text="employee.name"></p>
                                                                <p class="cyber-employee-select-role" x-text="employee.role_label"></p>
                                                                <p class="cyber-employee-select-nip" x-text="employee.nomor_induk"></p>
                                                            </div>
                                                        </button>
                                                    </template>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Pengaduan Options Modal --}}
                <div
                    x-ref="pengaduanModal"
                    x-show="showPengaduanModal"
                    x-cloak
                    class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6"
                    role="dialog"
                    aria-modal="true"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                >
                    {{-- Backdrop with animated grid --}}
                    <div class="absolute inset-0 bg-slate-950/90 backdrop-blur-sm">
                        <div class="absolute inset-0 opacity-[0.03]" style="background-image: linear-gradient(rgba(0, 212, 255, 0.3) 1px, transparent 1px), linear-gradient(90deg, rgba(0, 212, 255, 0.3) 1px, transparent 1px); background-size: 40px 40px;"></div>
                    </div>

                    <div class="relative w-full max-w-3xl">
                        {{-- Modal Card --}}
                        <div class="relative bg-[#0a0f1a] border-2 border-cyan-500 rounded-2xl overflow-hidden shadow-[0_0_60px_rgba(0,212,255,0.2)]">

                            {{-- Animated neon border --}}
                            <div class="absolute top-0 left-0 right-0 h-[2px] bg-gradient-to-r from-transparent via-cyan-400 to-transparent animate-pulse"></div>
                            <div class="absolute bottom-0 left-0 right-0 h-[2px] bg-gradient-to-r from-transparent via-purple-500 to-transparent animate-pulse" style="animation-delay: 0.5s;"></div>

                            {{-- Corner decorations --}}
                            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-cyan-500/10 to-transparent rounded-bl-full"></div>
                            <div class="absolute bottom-0 left-0 w-32 h-32 bg-gradient-to-tr from-purple-500/10 to-transparent rounded-tr-full"></div>

                            {{-- Floating particles --}}
                            <div class="absolute top-1/4 left-1/4 w-2 h-2 bg-cyan-400 rounded-full animate-pulse opacity-40"></div>
                            <div class="absolute top-3/4 right-1/4 w-3 h-3 bg-purple-500 rounded-full animate-pulse opacity-30" style="animation-delay: 1s;"></div>
                            <div class="absolute top-1/2 right-1/3 w-2 h-2 bg-pink-500 rounded-full animate-pulse opacity-40" style="animation-delay: 2s;"></div>

                            {{-- Header --}}
                            <div class="relative p-6 border-b border-cyan-500/20">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-4">
                                        <div class="relative">
                                            <div class="absolute inset-0 bg-cyan-400 blur-xl opacity-30"></div>
                                            <div class="relative w-14 h-14 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-xl flex items-center justify-center shadow-[0_0_25px_rgba(0,212,255,0.4)]">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-slate-900" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9.75c.621-.504 1.397-.753 2.028-.753.63 0 1.406.249 2.028.753M12 12h3.75M12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM4.5 9.75a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0zM4.5 12a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0zm0 2.25c.621-.504 1.397-.753 2.028-.753.63 0 1.406.249 2.028.753M19.5 9.75a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0zm0 2.25a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0zM12 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="px-2 py-0.5 bg-cyan-500/20 border border-cyan-500/30 rounded text-[10px] font-bold text-cyan-400 uppercase tracking-wider">PENGADUAN</span>
                                                <span class="px-2 py-0.5 bg-purple-500/20 border border-purple-500/30 rounded text-[10px] font-bold text-purple-400 uppercase tracking-wider">EXTERNAL</span>
                                            </div>
                                            <h4 class="text-xl font-bold text-white tracking-tight">Pilih Sistem Pengaduan</h4>
                                            <p class="text-sm text-slate-400 mt-1">Sampaikan keluhan atau aspirasi Anda melalui sistem resmi</p>
                                        </div>
                                    </div>

                                    <button
                                        type="button"
                                        @click="closePengaduanModal()"
                                        class="w-10 h-10 bg-slate-800/50 hover:bg-slate-700/50 border border-slate-600/30 hover:border-cyan-500/50 rounded-xl flex items-center justify-center transition-all"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            {{-- Content --}}
                            <div class="relative p-6">
                                <div class="grid gap-6 md:grid-cols-2">
                                    {{-- Whistleblowing Option --}}
                                    <button
                                        type="button"
                                        @click="window.location.href = '{{ route('whistleblowing') }}'"
                                        class="cyber-pengaduan-card group"
                                    >
                                        {{-- Glow effect on hover --}}
                                        <div class="cyber-pengaduan-glow-cyan"></div>

                                        <div class="relative z-10 flex flex-col items-center text-center p-6">
                                            {{-- Icon Container --}}
                                            <div class="relative mb-6">
                                                <div class="absolute inset-0 bg-cyan-400 blur-2xl opacity-0 group-hover:opacity-40 transition-opacity duration-500"></div>
                                                <div class="relative w-28 h-28 bg-gradient-to-br from-cyan-500/20 to-blue-600/20 border-2 border-cyan-500/50 rounded-2xl flex items-center justify-center group-hover:border-cyan-400 transition-all duration-300 group-hover:shadow-[0_0_40px_rgba(0,212,255,0.5)]">
                                                    <img src="{{ asset('assets/img/ikon/510.png') }}" alt="Whistleblowing" class="w-16 h-16 object-contain">
                                                </div>
                                            </div>

                                            {{-- Title --}}
                                            <div class="mb-3">
                                                <div class="flex items-center justify-center gap-2 mb-1">
                                                    <span class="w-2 h-2 bg-cyan-400 rounded-full animate-pulse"></span>
                                                    <span class="text-[10px] font-bold text-cyan-400 uppercase tracking-widest">Sistem 1</span>
                                                </div>
                                                <h5 class="text-lg font-bold text-white tracking-wide group-hover:text-cyan-400 transition-colors">WHISTLEBLOWING</h5>
                                            </div>

                                            {{-- Description --}}
                                            <p class="text-xs text-slate-400 leading-relaxed mb-4 px-2">
                                                Sistem pelaporan gratifikasi dan korupsi di Kementerian Agama. Laporkan pelanggaran ASN secara anonim.
                                            </p>

                                            {{-- Features --}}
                                            <div class="flex flex-wrap justify-center gap-2 mb-4">
                                                <span class="px-2 py-1 bg-cyan-500/10 border border-cyan-500/20 rounded text-[10px] text-cyan-400 font-semibold">ANONIM</span>
                                                <span class="px-2 py-1 bg-cyan-500/10 border border-cyan-500/20 rounded text-[10px] text-cyan-400 font-semibold">RAHASIA</span>
                                                <span class="px-2 py-1 bg-cyan-500/10 border border-cyan-500/20 rounded text-[10px] text-cyan-400 font-semibold">TERENCRYPT</span>
                                            </div>

                                            {{-- Action --}}
                                            <div class="flex items-center gap-2 text-cyan-400 font-semibold text-sm group-hover:gap-3 transition-all">
                                                <span>Buka Sistem</span>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                                </svg>
                                            </div>
                                        </div>

                                        {{-- Animated border --}}
                                        <div class="absolute inset-0 rounded-2xl border-2 border-transparent group-hover:border-cyan-500/50 transition-all duration-300"></div>
                                    </button>

                                    {{-- Lapor.go.id Option --}}
                                    <button
                                        type="button"
                                        @click="openPengaduanLink('https://lapor.go.id')"
                                        class="cyber-pengaduan-card group"
                                    >
                                        {{-- Glow effect on hover --}}
                                        <div class="cyber-pengaduan-glow-purple"></div>

                                        <div class="relative z-10 flex flex-col items-center text-center p-6">
                                            {{-- Icon Container --}}
                                            <div class="relative mb-6">
                                                <div class="absolute inset-0 bg-purple-400 blur-2xl opacity-0 group-hover:opacity-40 transition-opacity duration-500"></div>
                                                <div class="relative w-28 h-28 bg-gradient-to-br from-purple-500/20 to-pink-600/20 border-2 border-purple-500/50 rounded-2xl flex items-center justify-center group-hover:border-purple-400 transition-all duration-300 group-hover:shadow-[0_0_40px_rgba(168,85,247,0.5)]">
                                                    <img src="{{ asset('assets/img/ikon/509.png') }}" alt="Lapor.go.id" class="w-16 h-16 object-contain">
                                                </div>
                                            </div>

                                            {{-- Title --}}
                                            <div class="mb-3">
                                                <div class="flex items-center justify-center gap-2 mb-1">
                                                    <span class="w-2 h-2 bg-purple-400 rounded-full animate-pulse"></span>
                                                    <span class="text-[10px] font-bold text-purple-400 uppercase tracking-widest">Sistem 2</span>
                                                </div>
                                                <h5 class="text-lg font-bold text-white tracking-wide group-hover:text-purple-400 transition-colors">LAPOR.GO.ID</h5>
                                            </div>

                                            {{-- Description --}}
                                            <p class="text-xs text-slate-400 leading-relaxed mb-4 px-2">
                                                Layanan aspirasi dan pengaduan masyarakat ke pemerintah. Sampaikan masukan langsung ke instansi terkait.
                                            </p>

                                            {{-- Features --}}
                                            <div class="flex flex-wrap justify-center gap-2 mb-4">
                                                <span class="px-2 py-1 bg-purple-500/10 border border-purple-500/20 rounded text-[10px] text-purple-400 font-semibold">RESMI</span>
                                                <span class="px-2 py-1 bg-purple-500/10 border border-purple-500/20 rounded text-[10px] text-purple-400 font-semibold">NASIONAL</span>
                                                <span class="px-2 py-1 bg-purple-500/10 border border-purple-500/20 rounded text-[10px] text-purple-400 font-semibold">VERIFIKASI</span>
                                            </div>

                                            {{-- Action --}}
                                            <div class="flex items-center gap-2 text-purple-400 font-semibold text-sm group-hover:gap-3 transition-all">
                                                <span>Buka Sistem</span>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                                </svg>
                                            </div>
                                        </div>

                                        {{-- Animated border --}}
                                        <div class="absolute inset-0 rounded-2xl border-2 border-transparent group-hover:border-purple-500/50 transition-all duration-300"></div>
                                    </button>
                                </div>

                                {{-- Info footer --}}
                                <div class="mt-6 p-4 bg-slate-800/30 border border-slate-700/50 rounded-xl">
                                    <div class="flex items-start gap-3">
                                        <div class="w-8 h-8 bg-cyan-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.863l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-white mb-1">Informasi</p>
                                            <p class="text-xs text-slate-400 leading-relaxed">
                                                Anda akan diarahkan ke sistem pengaduan eksternal. Pastikan Anda memiliki koneksi internet yang stabil.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Animated scan line --}}
                            <div class="absolute inset-0 pointer-events-none overflow-hidden rounded-2xl">
                                <div class="absolute w-full h-[2px] bg-gradient-to-r from-transparent via-cyan-400/30 to-transparent" style="animation: scanDown 4s linear infinite;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</x-layouts.app>