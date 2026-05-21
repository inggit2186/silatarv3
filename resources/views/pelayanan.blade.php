<x-layouts.app title="Pelayanan - SILATAR">
    <main
        class="relative"
        x-data="{
            units: @js($kantorUnits),
            selectedUnitId: null,
            generalServices: @js($generalServices),
            specialServicesByUnit: @js($specialServicesByUnit),
            requestBaseUrl: @js(url('/pelayanan/ajukan')),
            selectedService: null,
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
            selectUnit(unitId) {
                this.selectedUnitId = unitId;
                this.selectedService = null;
                this.$nextTick(() => {
                    this.$refs.serviceFlow?.scrollIntoView({ behavior: 'smooth', block: 'start' });
                });
            },
            changeUnit() {
                this.selectedUnitId = null;
                this.selectedService = null;
                this.$nextTick(() => {
                    this.$refs.unitSelection?.scrollIntoView({ behavior: 'smooth', block: 'start' });
                });
            },
            selectService(kind, service) {
                this.selectedService = Object.assign({}, service, { kind });
                this.$nextTick(() => {
                    this.$refs.serviceModal?.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                });
            },
            closeServiceModal() {
                this.selectedService = null;
            }
        }"
        @keydown.escape.window="closeServiceModal()"
    >
        <x-ui.page-hero badge="Pelayanan SILATAR" title="Pilih unit kerja, lalu pilih layanan">
            <p class="max-w-3xl text-sm leading-7 text-slate-600 sm:text-base">
                Halaman ini memisahkan alur pelayanan dari beranda. Pilih unit kerja kantor aktif terlebih dahulu,
                lalu semua layanan akan muncul dalam bentuk kartu sesuai section-nya.
            </p>
            <div class="mt-6 flex flex-wrap justify-center gap-3">
                <a href="{{ url('/') }}" class="rounded-full border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-cyan-200 hover:text-slate-900">
                    Kembali ke beranda
                </a>
                <a href="{{ route('satuan-kerja', ['tab' => 'kantor']) }}" class="rounded-full bg-cyan-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-cyan-700">
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
            <div class="rounded-[2rem] border border-cyan-100 bg-white/85 p-5 shadow-sm">
                <div class="flex flex-col gap-3 rounded-[1.5rem] bg-cyan-50/70 p-5 md:flex-row md:items-center md:justify-between">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-cyan-700">Langkah 1</p>
                        <p class="mt-2 text-sm leading-6 text-slate-600">
                            Pilih unit kerja kantor yang aktif untuk membuka daftar layanan pada unit tersebut.
                        </p>
                    </div>
                    <span class="rounded-full bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm">
                        {{ $kantorUnits->count() }} unit aktif
                    </span>
                </div>

                <div class="mt-5 grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                    @forelse ($kantorUnits as $card)
                        <button
                            type="button"
                            @click="selectUnit({{ $card['id'] }})"
                            class="silatar-unit-card text-left"
                        >
                            <div class="silatar-unit-cover bg-[linear-gradient(135deg,_#eff6ff_0%,_#dbeafe_100%)]">
                                <img
                                    src="{{ $card['cover_path'] }}"
                                    alt="{{ $card['title'] }}"
                                    loading="lazy"
                                    decoding="async"
                                    class="silatar-unit-cover-img"
                                    onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');"
                                >

                                <div class="silatar-unit-cover-overlay">
                                    <div class="absolute -left-14 top-8 h-44 w-44 rounded-full bg-white/55 blur-3xl"></div>
                                    <div class="absolute -right-10 top-24 h-52 w-52 rounded-full bg-white/40 blur-3xl"></div>
                                    <div class="absolute bottom-0 left-1/2 h-40 w-[120%] -translate-x-1/2 rounded-[100%_100%_0_0] bg-white/35 blur-2xl"></div>
                                </div>

                                <div class="silatar-unit-cover-fallback">
                                    <div class="silatar-unit-cover-fallback-chip">
                                        {{ $card['cover'] }}
                                    </div>
                                </div>

                                <div class="silatar-unit-cover-chip bg-cyan-600">
                                    Unit Kerja Kantor
                                </div>

                                <div class="silatar-unit-cover-label">
                                    <div class="silatar-unit-cover-label-chip">
                                        <p class="silatar-unit-cover-label-text">
                                            {{ $card['title'] }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="silatar-unit-body">
                                <div class="silatar-unit-info">
                                    <span class="inline-flex items-center gap-2 font-medium text-slate-500">
                                        Pegawai
                                    </span>
                                    <span class="max-w-[11rem] truncate rounded-full bg-white px-3 py-1 text-right font-semibold text-slate-800 shadow-sm">
                                        {{ $card['extra_value'] }}
                                    </span>
                                </div>

                                @if (! empty($card['head_value']))
                                    <div class="silatar-unit-head">
                                        <div class="min-w-0">
                                            <p class="text-[0.6rem] font-semibold uppercase tracking-[0.28em] text-cyan-600">
                                                {{ $card['head_label'] }}
                                            </p>
                                            <p class="truncate font-semibold text-cyan-950">
                                                {{ $card['head_value'] }}
                                            </p>
                                        </div>
                                    </div>
                                @endif

                                <div class="flex items-center justify-between gap-3 text-sm text-slate-500">
                                    <span class="inline-flex items-center gap-2">
                                        <span class="silatar-unit-meta-chip bg-cyan-50 text-cyan-700">
                                            {{ $card['subtitle'] }}
                                        </span>
                                    </span>
                                    <span class="inline-flex items-center gap-2 font-medium text-slate-400">
                                        Pilih unit ini
                                    </span>
                                </div>
                            </div>
                        </button>
                    @empty
                        <div class="rounded-[1.5rem] border border-dashed border-slate-300 bg-slate-50 p-6 text-sm text-slate-500">
                            Belum ada unit kerja kantor dengan status aktif.
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
            <div class="rounded-[2rem] border border-slate-200 bg-white/90 p-5 shadow-sm">
                <div class="flex flex-col gap-4 rounded-[1.5rem] bg-slate-50 p-5 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-cyan-700">Langkah 2</p>
                        <h3 class="mt-2 text-2xl font-semibold text-slate-900" x-text="selectedUnit ? selectedUnit.title : ''"></h3>
                        <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
                            Semua layanan untuk unit ini langsung ditampilkan sebagai kartu, dipisah hanya berdasarkan section.
                        </p>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <div class="rounded-[1.25rem] border border-slate-200 bg-white px-4 py-3 text-sm text-slate-600 shadow-sm">
                            <p class="font-semibold text-slate-900">Unit terpilih</p>
                            <p class="mt-1" x-text="selectedUnit ? selectedUnit.title : '-'"></p>
                        </div>
                        <button
                            type="button"
                            @click="changeUnit()"
                            class="rounded-full border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-cyan-200 hover:text-slate-900"
                        >
                            Ganti unit
                        </button>
                    </div>
                </div>

                <div class="mt-6 space-y-6">
                    <section class="rounded-[1.75rem] border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-cyan-700">Section 1</p>
                                <h3 class="mt-1 text-xl font-semibold text-slate-900">Layanan umum</h3>
                            </div>
                            <span class="rounded-full bg-cyan-50 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-cyan-700">
                                4 layanan
                            </span>
                        </div>

                        <div class="mt-5 grid gap-5 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">
                            @foreach ($generalServices as $service)
                                <button
                                    type="button"
                                    @click="selectService('umum', @js($service))"
                                    class="overflow-hidden rounded-[1.5rem] border text-left shadow-sm transition hover:-translate-y-1 hover:shadow-lg"
                                    :class="selectedService && selectedService.kind === 'umum' && selectedService.key === '{{ $service['key'] }}'
                                        ? 'border-cyan-300 bg-cyan-50'
                                        : 'border-slate-200 bg-white hover:border-cyan-200'"
                                >
                                    <div class="relative aspect-[16/10] overflow-hidden bg-slate-50">
                                        <div class="absolute inset-0 flex items-center justify-center p-2">
                                            <img src="{{ $service['cover_path'] }}" alt="{{ $service['title'] }}" class="h-auto w-auto max-h-[96%] max-w-[96%] object-contain object-center">
                                        </div>
                                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/20 via-transparent to-transparent"></div>
                                        <div class="absolute left-4 top-4 rounded-full bg-white/90 px-3 py-1 text-[0.62rem] font-semibold uppercase tracking-[0.22em] text-cyan-700 shadow-sm">
                                            {{ $service['tag'] }}
                                        </div>
                                    </div>

                                    <div class="p-4">
                                        <h4 class="text-base font-semibold text-slate-900">
                                            {{ $service['title'] }}
                                        </h4>
                                        <p class="mt-2 text-sm leading-5 text-slate-600">
                                            {{ $service['description'] }}
                                        </p>
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    </section>

                    <section class="rounded-[1.75rem] border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-cyan-700">Section 2</p>
                                <h3 class="mt-1 text-xl font-semibold text-slate-900">Layanan khusus</h3>
                            </div>
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-slate-600" x-text="specialServices.length + ' layanan'"></span>
                        </div>

                        <template x-if="featuredSpecialService">
                            <button
                                type="button"
                                @click="selectService('khusus', featuredSpecialService)"
                                class="mt-5 relative overflow-hidden rounded-[1.75rem] border border-amber-300 bg-gradient-to-br from-amber-50 via-white to-cyan-50 text-left shadow-lg shadow-amber-100 transition hover:-translate-y-1 hover:border-amber-400 hover:shadow-xl"
                                :class="selectedService && selectedService.kind === 'khusus' && selectedService.id === featuredSpecialService.id
                                    ? 'ring-2 ring-amber-200'
                                    : ''"
                            >
                                <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(251,191,36,0.22),_transparent_34%),radial-gradient(circle_at_bottom_left,_rgba(6,182,212,0.12),_transparent_30%)]"></div>
                                <div class="relative grid gap-5 p-5 lg:grid-cols-[1.15fr_.85fr] lg:items-center lg:p-6">
                                    <div>
                                        <div class="flex flex-wrap items-center gap-3">
                                            <span class="inline-flex items-center rounded-full bg-amber-500 px-3 py-1 text-[0.62rem] font-semibold uppercase tracking-[0.22em] text-white shadow-lg shadow-amber-200">
                                                Layanan spesial
                                            </span>
                                            <span class="inline-flex items-center rounded-full bg-white/90 px-3 py-1 text-[0.62rem] font-semibold uppercase tracking-[0.18em] text-amber-700 shadow-sm">
                                                Sorotan utama
                                            </span>
                                            <span
                                                class="inline-flex items-center rounded-full px-3 py-1 text-[0.62rem] font-semibold uppercase tracking-[0.18em]"
                                                :class="featuredSpecialService.status_label === 'Aktif' ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700'"
                                                x-text="featuredSpecialService.status_label"
                                            ></span>
                                        </div>

                                        <h4 class="mt-4 text-2xl font-semibold text-slate-950" x-text="featuredSpecialService.title"></h4>
                                        <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-600" x-text="featuredSpecialService.description"></p>

                                        <div class="mt-5 grid gap-3 sm:grid-cols-3">
                                            <div class="rounded-2xl bg-white/85 px-4 py-3 shadow-sm">
                                                <p class="text-[0.62rem] font-semibold uppercase tracking-[0.22em] text-slate-400">Waktu</p>
                                                <p class="mt-1 text-sm font-medium text-slate-800" x-text="featuredSpecialService.waktu"></p>
                                            </div>
                                            <div class="rounded-2xl bg-white/85 px-4 py-3 shadow-sm">
                                                <p class="text-[0.62rem] font-semibold uppercase tracking-[0.22em] text-slate-400">Biaya</p>
                                                <p class="mt-1 text-sm font-medium text-slate-800" x-text="featuredSpecialService.biaya"></p>
                                            </div>
                                            <div class="rounded-2xl bg-white/85 px-4 py-3 shadow-sm">
                                                <p class="text-[0.62rem] font-semibold uppercase tracking-[0.22em] text-slate-400">Output</p>
                                                <p class="mt-1 text-sm font-medium text-slate-800" x-text="featuredSpecialService.output"></p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="relative overflow-hidden rounded-[1.5rem] border border-white/80 bg-white/80 p-4 shadow-sm">
                                        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(251,191,36,0.14),_transparent_40%)]"></div>
                                        <div class="relative aspect-[16/11] overflow-hidden rounded-[1.25rem] bg-slate-50">
                                            <div class="absolute inset-0 flex items-center justify-center p-4">
                                                <img :src="featuredSpecialService.cover_path" :alt="featuredSpecialService.title" class="h-auto w-auto max-h-[96%] max-w-[96%] object-contain object-center">
                                            </div>
                                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/15 via-transparent to-transparent"></div>
                                            <div class="absolute left-4 top-4 rounded-full bg-white/90 px-3 py-1 text-[0.62rem] font-semibold uppercase tracking-[0.22em] text-cyan-700 shadow-sm">
                                                Layanan khusus
                                            </div>
                                            <div class="absolute bottom-4 left-4 rounded-full bg-amber-500 px-3 py-1 text-[0.62rem] font-semibold uppercase tracking-[0.22em] text-white shadow-lg shadow-amber-200">
                                                Diprioritaskan
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </button>
                        </template>

                        <template x-if="specialServicesGrid.length">
                            <div class="mt-5 grid gap-5 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">
                                <template x-for="service in specialServicesGrid" :key="service.id">
                                    <button
                                        type="button"
                                        @click="selectService('khusus', service)"
                                        class="overflow-hidden rounded-[1.5rem] border text-left shadow-sm transition hover:-translate-y-1 hover:shadow-lg"
                                        :class="selectedService && selectedService.kind === 'khusus' && selectedService.id === service.id
                                            ? (service.is_spesial
                                                ? 'border-cyan-400 bg-cyan-50 ring-2 ring-cyan-200 shadow-lg shadow-cyan-100'
                                                : 'border-cyan-300 bg-cyan-50')
                                            : (service.is_spesial
                                                ? 'border-amber-300 bg-gradient-to-br from-amber-50 via-white to-cyan-50 hover:border-amber-400 hover:shadow-xl'
                                                : 'border-slate-200 bg-white hover:border-cyan-200')"
                                    >
                                        <div class="relative aspect-[16/10] overflow-hidden bg-slate-50">
                                            <div class="absolute inset-0 flex items-center justify-center p-2">
                                                <img :src="service.cover_path" :alt="service.title" class="h-auto w-auto max-h-[96%] max-w-[96%] object-contain object-center">
                                            </div>
                                            <div
                                                class="absolute inset-0"
                                                :class="service.is_spesial
                                                    ? 'bg-[radial-gradient(circle_at_top,_rgba(251,191,36,0.18),_transparent_42%),linear-gradient(180deg,_rgba(15,23,42,0.08)_0%,_rgba(15,23,42,0.35)_100%)]'
                                                    : 'bg-gradient-to-t from-slate-950/20 via-transparent to-transparent'"
                                            ></div>
                                            <div class="absolute left-4 top-4 rounded-full bg-white/90 px-3 py-1 text-[0.62rem] font-semibold uppercase tracking-[0.22em] text-cyan-700 shadow-sm">
                                                Layanan khusus
                                            </div>
                                            <div
                                                class="absolute right-4 top-4 rounded-full px-3 py-1 text-[0.62rem] font-semibold uppercase tracking-[0.18em]"
                                                :class="service.status_label === 'Aktif' ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700'"
                                                x-text="service.status_label"
                                            ></div>
                                            <div
                                                x-show="service.is_spesial"
                                                x-cloak
                                                class="absolute bottom-4 left-4 rounded-full bg-amber-500 px-3 py-1 text-[0.62rem] font-semibold uppercase tracking-[0.22em] text-white shadow-lg shadow-amber-200"
                                            >
                                                Spesial
                                            </div>
                                        </div>

                                        <div class="p-4" :class="service.is_spesial ? 'relative' : ''">
                                            <div
                                                x-show="service.is_spesial"
                                                x-cloak
                                                class="absolute inset-x-5 -top-2 h-px bg-gradient-to-r from-transparent via-amber-300 to-transparent"
                                            ></div>
                                            <h4 class="text-base font-semibold text-slate-900" x-text="service.title"></h4>
                                            <p class="mt-2 text-sm leading-5 text-slate-600" x-text="service.description"></p>

                                            <div class="mt-4 grid gap-2 text-xs text-slate-500">
                                                <div class="rounded-xl bg-slate-50 px-3 py-2">
                                                    <p class="font-semibold uppercase tracking-[0.18em] text-slate-400">Waktu</p>
                                                    <p class="mt-1 text-sm font-medium text-slate-700" x-text="service.waktu"></p>
                                                </div>
                                                <div class="rounded-xl bg-slate-50 px-3 py-2">
                                                    <p class="font-semibold uppercase tracking-[0.18em] text-slate-400">Biaya</p>
                                                    <p class="mt-1 text-sm font-medium text-slate-700" x-text="service.biaya"></p>
                                                </div>
                                                <div class="rounded-xl bg-slate-50 px-3 py-2">
                                                    <p class="font-semibold uppercase tracking-[0.18em] text-slate-400">Output</p>
                                                    <p class="mt-1 text-sm font-medium text-slate-700" x-text="service.output"></p>
                                                </div>
                                            </div>
                                        </div>
                                    </button>
                                </template>
                            </div>
                        </template>

                        <div x-show="specialServices.length === 0" x-cloak class="mt-5 rounded-[1.35rem] border border-dashed border-slate-300 bg-slate-50 p-5 text-sm leading-6 text-slate-500">
                            Belum ada layanan khusus pada unit kerja yang dipilih.
                        </div>
                    </section>
                </div>

                <div
                    x-ref="serviceModal"
                    x-show="selectedService"
                    x-cloak
                    class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6"
                    role="dialog"
                    aria-modal="true"
                    x-transition.opacity.duration.180ms
                >
                    <div class="absolute inset-0 bg-slate-950/70 backdrop-blur-md" @click="closeServiceModal()"></div>

                    <div class="relative w-full max-w-[calc(100vw-1.5rem)] lg:max-w-5xl">
                        <div class="rounded-[2.15rem] bg-gradient-to-br from-cyan-100 via-white to-amber-100 p-px shadow-[0_30px_90px_rgba(15,23,42,0.28)]">
                            <div class="relative overflow-hidden rounded-[2.1rem] border border-white/70 bg-white/95 shadow-[inset_0_1px_0_rgba(255,255,255,0.75)] backdrop-blur-xl"
                                x-transition.scale.95.duration.220ms
                            >
                                <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(6,182,212,0.12),_transparent_28%),radial-gradient(circle_at_bottom_left,_rgba(251,191,36,0.11),_transparent_28%)]"></div>
                                <div class="absolute right-0 top-0 h-44 w-44 rounded-full bg-cyan-100/50 blur-3xl"></div>
                                <div class="absolute -left-8 bottom-0 h-40 w-40 rounded-full bg-amber-100/50 blur-3xl"></div>

                                <div class="relative max-h-[calc(100vh-3rem)] overflow-y-auto overflow-x-hidden">
                                    <div class="sticky top-0 z-10 border-b border-slate-200/70 bg-white/85 px-5 py-4 backdrop-blur-xl sm:px-6">
                                        <div class="flex items-start justify-between gap-4">
                                            <div class="min-w-0">
                                                <div class="flex flex-wrap items-center gap-2">
                                                    <span class="inline-flex items-center rounded-full bg-cyan-600 px-3 py-1 text-[0.62rem] font-semibold uppercase tracking-[0.22em] text-white shadow-sm">
                                                        Detail layanan
                                                    </span>
                                                    <span
                                                        class="inline-flex items-center rounded-full px-3 py-1 text-[0.62rem] font-semibold uppercase tracking-[0.18em]"
                                                        :class="selectedService && selectedService.status_label === 'Aktif' ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700'"
                                                        x-text="selectedService && selectedService.status_label ? selectedService.status_label : 'Aktif'"
                                                    ></span>
                                                    <span
                                                        class="inline-flex items-center rounded-full bg-amber-50 px-3 py-1 text-[0.62rem] font-semibold uppercase tracking-[0.18em] text-amber-700"
                                                        x-text="selectedService && selectedService.is_spesial ? 'Sorotan' : 'Standar'"
                                                    ></span>
                                                </div>
                                                <h4 class="mt-3 text-2xl font-semibold tracking-tight text-slate-950 sm:text-3xl" x-text="selectedService ? selectedService.title : '-'"></h4>
                                                <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-600 sm:text-base" x-text="selectedService ? selectedService.description : ''"></p>
                                            </div>

                                            <button
                                                type="button"
                                                @click="closeServiceModal()"
                                                class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-200/80 bg-white text-slate-500 shadow-sm transition hover:border-cyan-200 hover:text-slate-900"
                                                aria-label="Tutup modal"
                                            >
                                                <span class="text-xl leading-none">&times;</span>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="relative grid gap-6 px-5 py-5 sm:px-6 lg:grid-cols-[minmax(0,1.12fr)_minmax(280px,0.88fr)] lg:gap-8 lg:px-8 lg:py-8">
                            <div class="min-w-0 space-y-5">
                                <div class="grid gap-3 sm:grid-cols-3">
                                    <div class="rounded-[1.25rem] border border-slate-200 bg-white px-4 py-3 shadow-sm">
                                        <p class="text-[0.62rem] font-semibold uppercase tracking-[0.22em] text-slate-400">Jenis</p>
                                        <p class="mt-1 text-sm font-semibold text-slate-900" x-text="selectedService && selectedService.kind === 'umum' ? 'Layanan umum' : 'Layanan khusus'"></p>
                                    </div>
                                    <div class="rounded-[1.25rem] border border-slate-200 bg-white px-4 py-3 shadow-sm">
                                        <p class="text-[0.62rem] font-semibold uppercase tracking-[0.22em] text-slate-400">Waktu</p>
                                        <p class="mt-1 text-sm font-semibold text-slate-900" x-text="selectedService && selectedService.waktu ? selectedService.waktu : '-'"></p>
                                    </div>
                                    <div class="rounded-[1.25rem] border border-slate-200 bg-white px-4 py-3 shadow-sm">
                                        <p class="text-[0.62rem] font-semibold uppercase tracking-[0.22em] text-slate-400">Biaya</p>
                                        <p class="mt-1 text-sm font-semibold text-slate-900" x-text="selectedService && selectedService.biaya ? selectedService.biaya : '-'"></p>
                                    </div>
                                </div>

                                <div class="rounded-[1.5rem] border border-slate-200 bg-white/95 p-4 shadow-sm">
                                    <div class="flex flex-wrap items-center justify-between gap-3">
                                        <div>
                                            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-cyan-700">Syarat-syarat</p>
                                            <p class="mt-1 text-xs text-slate-500">
                                                Ringkasan syarat dari data layanan
                                            </p>
                                        </div>
                                        <span
                                            class="rounded-full bg-cyan-50 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-cyan-700"
                                            x-text="selectedServiceRequirements.length + ' syarat'"
                                        ></span>
                                    </div>

                                    <div class="mt-4 max-h-72 overflow-auto pr-1">
                                        <ul class="space-y-3">
                                            <template x-for="requirement in selectedServiceRequirements" :key="requirement.id">
                                                <li class="flex items-start gap-3 rounded-[1rem] border border-slate-100 bg-slate-50 px-4 py-3 shadow-sm transition hover:border-cyan-200 hover:bg-cyan-50/70">
                                                    <span class="mt-1 inline-flex h-5 w-5 flex-none items-center justify-center rounded-full bg-cyan-600 text-[0.7rem] font-bold text-white">&bull;</span>
                                                    <div class="min-w-0 flex-1">
                                                        <div class="flex flex-wrap items-center gap-2">
                                                            <span class="truncate text-sm font-medium text-slate-900" x-text="requirement.title"></span>
                                                            <span
                                                                x-show="requirement.is_required"
                                                                x-cloak
                                                                class="rounded-full bg-rose-50 px-2 py-0.5 text-[0.6rem] font-semibold uppercase tracking-[0.18em] text-rose-700"
                                                            >
                                                                Wajib
                                                            </span>
                                                            <span
                                                                x-show="requirement.is_primary"
                                                                x-cloak
                                                                class="rounded-full bg-amber-50 px-2 py-0.5 text-[0.6rem] font-semibold uppercase tracking-[0.18em] text-amber-700"
                                                            >
                                                                Utama
                                                            </span>
                                                        </div>
                                                        <p x-show="requirement.note" x-cloak class="mt-1 text-[0.72rem] leading-5 text-slate-500" x-text="requirement.note"></p>
                                                    </div>
                                                </li>
                                            </template>
                                        </ul>
                                    </div>

                                    <p x-show="selectedServiceRequirements.length === 0" x-cloak class="mt-4 rounded-[1rem] border border-dashed border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-500">
                                        Belum ada syarat yang terdaftar untuk layanan ini.
                                    </p>
                                </div>
                            </div>

                            <div class="min-w-0 rounded-[1.5rem] border border-slate-200 bg-gradient-to-br from-cyan-50 via-white to-amber-50 p-5 shadow-sm lg:sticky lg:top-6 self-start">
                                <div class="relative overflow-hidden rounded-[1.4rem] border border-white/80 bg-white/90 p-4 shadow-sm">
                                    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(6,182,212,0.10),_transparent_40%)]"></div>
                                    <div class="relative flex items-center gap-4">
                                        <div class="flex h-16 w-16 flex-none items-center justify-center rounded-[1.4rem] bg-slate-50 shadow-sm ring-1 ring-slate-200/70">
                                            <img
                                                :src="selectedService ? selectedService.cover_path : ''"
                                                :alt="selectedService ? selectedService.title : ''"
                                                class="h-auto w-auto max-h-[90%] max-w-[90%] object-contain object-center"
                                            >
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-[0.62rem] font-semibold uppercase tracking-[0.22em] text-amber-700">Unit terpilih</p>
                                            <p class="truncate text-base font-semibold text-slate-950" x-text="selectedUnit ? selectedUnit.title : '-'"></p>
                                            <p class="mt-1 text-sm text-slate-600" x-text="selectedService && selectedService.status_label ? selectedService.status_label : 'Aktif'"></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4 rounded-[1.25rem] border border-white/80 bg-white/85 p-4 text-sm leading-6 text-slate-600 shadow-sm">
                                    <p class="font-semibold text-slate-900">Sebelum mengajukan</p>
                                    <p class="mt-2">
                                        Pastikan semua syarat sudah lengkap. Jika ada dokumen yang belum siap, tutup modal ini dulu dan lengkapi berkasnya.
                                    </p>
                                </div>

                                <div class="mt-5 flex flex-col gap-3 sm:flex-row">
                                    <a
                                        :href="selectedService ? requestBaseUrl + '/' + selectedService.id : '#'"
                                        class="inline-flex flex-1 items-center justify-center rounded-full bg-slate-950 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-950/15 transition hover:-translate-y-0.5 hover:bg-cyan-600"
                                    >
                                        Ajukan
                                    </a>
                                    <button
                                        type="button"
                                        @click="closeServiceModal()"
                                        class="inline-flex flex-1 items-center justify-center rounded-full border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-cyan-200 hover:text-slate-900"
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
        </section>
    </main>
</x-layouts.app>
