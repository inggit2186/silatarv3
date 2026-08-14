<x-layouts.app title="{{ $ogTitle ?? 'Presensi Acara - SILATAR' }}">

    @push('styles')
    <meta property="og:title" content="{{ $ogTitle ?? 'Presensi Acara - SILATAR' }}">
    <meta property="og:description" content="{{ $ogDescription ?? 'Presensi Acara' }}">
    <meta property="og:image" content="{{ $ogImage ?? asset('favicon.webp') }}">
    <meta property="og:url" content="{{ $ogUrl ?? url()->current() }}">
    <meta property="og:type" content="website">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $ogTitle ?? 'Presensi Acara - SILATAR' }}">
    <meta name="twitter:description" content="{{ $ogDescription ?? 'Presensi Acara' }}">
    <meta name="twitter:image" content="{{ $ogImage ?? asset('favicon.webp') }}">
    @endpush

    <main class="neo-mirai min-h-screen bg-[var(--paper)] pt-20 lg:pt-24">
        <!-- Content -->
        <section class="page-content px-6 py-8 lg:px-8">
            <div class="max-w-2xl mx-auto">
                @if(session('error'))
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ session('error') }}
                    </div>
                @endif

                {{-- Cover Photo --}}
                @if($acara->filename)
                    <div class="mb-6 neo-card overflow-hidden">
                        <img src="{{ asset('storage/acara/' . $acara->filename) }}" alt="{{ $acara->judul }}" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <h2 class="text-lg font-bold text-[var(--ink)]">{{ $acara->judul }}</h2>
                            <p class="text-sm text-[var(--ink-soft)]">{{ $acara->lokasi }}</p>
                        </div>
                    </div>
                @endif

                {{-- Acara Info Card --}}
                <div class="neo-card mb-6 p-6">
                    <div class="flex items-center gap-3 mb-4 pb-4 border-b border-[var(--line)]">
                        <div class="w-10 h-10 bg-[var(--gold)]/10 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-[var(--gold)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-[var(--ink)]">{{ $acara->judul }}</h2>
                            <p class="text-xs text-[var(--ink-soft)]">{{ $acara->lokasi }}</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-[var(--ink-soft)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-sm text-[var(--ink)]">{{ \Carbon\Carbon::parse($acara->tanggal)->format('d M Y') }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-[var(--ink-soft)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-sm text-[var(--ink)]">{{ $acara->jam_mulai }} - {{ $acara->jam_selesei }} WIB</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-[var(--ink-soft)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            </svg>
                            <span class="text-sm text-[var(--ink)]">{{ $acara->lokasi }}</span>
                        </div>
                    </div>
                </div>

                {{-- NIP Input Form --}}
                <div class="neo-card p-6">
                    <div class="text-center mb-6">
                        <div class="w-16 h-16 mx-auto mb-4 bg-[var(--gold)]/10 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-[var(--gold)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2v-5M14 6h4m0 0v4m0-4l-7 7m8-10l2 2"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-[var(--ink)] mb-2">Masukkan NIP Anda</h3>
                        <p class="text-sm text-[var(--ink-soft)]">Untuk mengakses halaman presensi acara</p>
                    </div>

                    <form action="{{ route('presensi-acara-nip.submit', $acara->id) }}" method="POST" id="nipForm">
                        @csrf
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-[var(--ink)] mb-2">Nomor Induk Pegawai (NIP)</label>
                            <input type="text" name="nomor_induk" required
                                class="w-full px-4 py-3 bg-[var(--paper-soft)] border border-[var(--line)] rounded-xl text-[var(--ink)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)] focus:border-transparent text-center text-lg font-mono"
                                placeholder="Masukkan NIP Anda"
                                pattern="[0-9]+"
                                title="Hanya angka yang diperbolehkan">
                        </div>

                        <button type="submit" id="submitNip" class="w-full py-3 bg-[var(--gold)] hover:bg-[var(--gold-bright)] text-white font-bold rounded-xl shadow-lg transition-all flex items-center justify-center gap-2">
                            <span id="nipText">Lanjutkan</span>
                            <span id="nipSpinner" class="hidden">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </span>
                        </button>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <script>
        document.getElementById('nipForm').addEventListener('submit', function() {
            document.getElementById('nipText').classList.add('hidden');
            document.getElementById('nipSpinner').classList.remove('hidden');
            document.getElementById('submitNip').disabled = true;
        });
    </script>
</x-layouts.app>
