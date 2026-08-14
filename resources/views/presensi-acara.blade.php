<x-layouts.app title="Presensi Acara - SILATAR">

    <main class="neo-mirai min-h-screen bg-[var(--paper)] pt-20 lg:pt-24">
        <!-- Content -->
        <section class="page-content px-6 py-8 lg:px-8">
            <div class="max-w-2xl mx-auto">
                @if(session('success'))
                    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ session('success') }}
                    </div>
                @endif

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

                {{-- User Info Card --}}
                @if(isset($userName))
                    <div class="mb-6 neo-card p-5">
                        <div class="flex items-center gap-4">
                            @if(isset($userPhoto) && $userPhoto)
                                <div class="w-14 h-14 rounded-full flex-shrink-0 overflow-hidden border-2 border-[var(--gold)]">
                                    <img src="{{ asset('storage/users_berkas/' . $nomorInduk . '/' . $userPhoto) }}" alt="{{ $userName }}" class="w-full h-full object-cover">
                                </div>
                            @else
                                <div class="w-14 h-14 bg-[var(--gold)] rounded-full flex items-center justify-center flex-shrink-0">
                                    <span class="text-xl font-bold text-white">{{ substr($userName, 0, 2) }}</span>
                                </div>
                            @endif
                            <div class="flex-1">
                                <h2 class="text-lg font-bold text-[var(--ink)]">{{ $userName }}</h2>
                                <div class="flex flex-wrap gap-x-4 gap-y-1 text-xs text-[var(--ink-soft)]">
                                    <span>NIP: {{ $nomorInduk }}</span>
                                    @if(isset($jabatan))
                                        <span>Jabatan: {{ $jabatan }}</span>
                                    @endif
                                    @if(isset($unitKerja))
                                        <span>Unit Kerja: {{ $unitKerja }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Acara Info Card --}}
                <div class="neo-card mb-6 p-5">
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

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-sm">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-[var(--ink-soft)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-[var(--ink)]">{{ \Carbon\Carbon::parse($acara->tanggal)->format('d M Y') }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-[var(--ink-soft)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-[var(--ink)]">{{ $acara->jam_mulai }} - {{ $acara->jam_selesei }} WIB</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-[var(--ink-soft)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            </svg>
                            <span class="text-[var(--ink)]">{{ $acara->lokasi }}</span>
                        </div>

                        @if($acara->filename)
                            <div class="mt-3">
                                <button type="button" onclick="showPhotoModal('{{ asset('storage/acara/' . $acara->filename) }}')" class="w-full py-2 bg-[var(--gold)]/10 hover:bg-[var(--gold)]/20 text-[var(--gold)] text-sm font-semibold rounded-lg transition-colors flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    Lihat Foto Acara
                                </button>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Action Buttons --}}
                @if($sudahPresensi)
                    <div class="neo-card p-6 text-center">
                        <div class="w-16 h-16 mx-auto mb-4 {{ $statusKehadiran == 'hadir' ? 'bg-emerald-100' : 'bg-red-100' }} rounded-full flex items-center justify-center">
                            @if($statusKehadiran == 'hadir')
                                <svg class="w-8 h-8 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            @else
                                <svg class="w-8 h-8 text-red-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            @endif
                        </div>
                        <h3 class="text-lg font-bold text-[var(--ink)] mb-2">
                            {{ $statusKehadiran == 'hadir' ? 'Kehadiran Tercatat' : 'Tidak Hadir' }}
                        </h3>
                        @if($keterangan)
                            <p class="text-sm text-[var(--ink-soft)]">{{ $keterangan }}</p>
                        @endif
                    </div>
                @else
                    {{-- Hadir Button --}}
                    <form action="{{ route('presensi-acara.hadir', $acara->id) }}" method="POST" id="hadirForm" onsubmit="return validatePresensi()">
                        @csrf
                        <input type="hidden" name="nomor_induk" value="{{ $nomorInduk ?? '' }}">
                        <input type="hidden" name="latitude" id="latitude" value="0">
                        <input type="hidden" name="longitude" id="longitude" value="0">
                        <input type="hidden" name="foto" id="foto" value="">

                        <div class="neo-card p-6 mb-4">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-[var(--ink)]">Hadir</h3>
                                    <p class="text-xs text-[var(--ink-soft)]">Konfirmasi kehadiran Anda</p>
                                </div>
                            </div>

                            {{-- Location Info --}}
                            <div id="locationInfo" class="mb-4 p-3 bg-[var(--paper-soft)] rounded-lg">
                                <div class="flex items-center gap-2 mb-2">
                                    <svg class="w-4 h-4 text-[var(--ink-soft)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    </svg>
                                    <span class="text-sm font-semibold text-[var(--ink)]">Lokasi Anda</span>
                                </div>
                                <div id="locationText" class="text-xs text-[var(--ink-soft)]">
                                    Mendeteksi lokasi...
                                </div>
                            </div>

                            {{-- Photo Section --}}
                            <div class="mb-4">
                                <label class="block text-sm font-semibold text-[var(--ink)] mb-2">Foto Lokasi</label>
                                <div id="photoPreview" class="hidden mb-3">
                                    <img id="photoImg" src="" alt="Foto" class="w-full h-48 object-cover rounded-lg">
                                </div>
                                <button type="button" onclick="takePhoto()" class="w-full py-4 border-2 border-dashed border-[var(--gold)] rounded-lg bg-[var(--gold)]/5 text-[var(--gold)] hover:bg-[var(--gold)]/10 transition-colors">
                                    <svg class="w-8 h-8 mx-auto mb-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9a2 2 0 012-2z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span class="text-sm font-semibold">Ambil Foto</span>
                                </button>
                            </div>

                            <button type="submit" id="submitHadir" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg transition-all flex items-center justify-center gap-2">
                                <span id="hadirText">Presensi Hadir</span>
                                <span id="hadirSpinner" class="hidden">
                                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </span>
                            </button>
                        </div>
                    </form>

                    {{-- Tidak Hadir Button --}}
                    <div class="neo-card p-6">
                        <button type="button" onclick="showTidakHadirModal()" class="w-full py-3 bg-red-500 hover:bg-red-600 text-white font-bold rounded-xl shadow-lg transition-all flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Tidak Hadir
                        </button>
                    </div>

                    {{-- Tidak Hadir Modal --}}
                    <div id="tidakHadirModal" style="display:none;" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
                        <div class="bg-[var(--paper)] rounded-2xl w-full max-w-md p-6 shadow-xl">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-red-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-[var(--ink)]">Tidak Hadir</h3>
                                    <p class="text-xs text-[var(--ink-soft)]">Saya tidak bisa hadir</p>
                                </div>
                            </div>

                            <form action="{{ route('presensi-acara.tidak-hadir', $acara->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="nomor_induk" value="{{ $nomorInduk ?? '' }}">
                                <div class="mb-4">
                                    <label class="block text-sm font-semibold text-[var(--ink)] mb-2">Keterangan / Alasan Ketidakhadiran</label>
                                    <textarea name="keterangan" class="w-full px-4 py-3 bg-[var(--paper-soft)] border border-[var(--line)] rounded-xl text-[var(--ink)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)] focus:border-transparent" rows="3" placeholder="Masukkan alasan tidak hadir..." required></textarea>
                                </div>
                                <div class="flex gap-3">
                                    <button type="button" onclick="closeTidakHadirModal()" id="cancelTidakHadir" class="flex-1 py-3 bg-[var(--paper-soft)] hover:bg-[var(--line)] text-[var(--ink)] font-semibold rounded-xl transition-all">
                                        Batal
                                    </button>
                                    <button type="submit" id="submitTidakHadir" class="flex-1 py-3 bg-red-500 hover:bg-red-600 text-white font-bold rounded-xl shadow-lg transition-all flex items-center justify-center gap-2">
                                        <span id="tidakHadirText">Kirim</span>
                                        <span id="tidakHadirSpinner" class="hidden">
                                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </section>
    </main>

    <script>
        // Tidak Hadir Modal
        function showTidakHadirModal() {
            document.getElementById('tidakHadirModal').style.display = 'flex';
        }

        function closeTidakHadirModal() {
            document.getElementById('tidakHadirModal').style.display = 'none';
        }

        // Loading states
        document.getElementById('hadirForm').addEventListener('submit', function() {
            document.getElementById('hadirText').classList.add('hidden');
            document.getElementById('hadirSpinner').classList.remove('hidden');
            document.getElementById('submitHadir').disabled = true;
        });

        document.querySelector('#tidakHadirModal form').addEventListener('submit', function() {
            document.getElementById('tidakHadirText').classList.add('hidden');
            document.getElementById('tidakHadirSpinner').classList.remove('hidden');
            document.getElementById('submitTidakHadir').disabled = true;
            document.getElementById('cancelTidakHadir').disabled = true;
        });

        var locationDetected = false;

        // Try to get location silently on page load
        document.addEventListener('DOMContentLoaded', function() {
            tryGetLocation();
        });

        function tryGetLocation() {
            var locationText = document.getElementById('locationText');
            locationText.innerHTML = '<span class="text-[var(--ink-soft)]">Mendeteksi lokasi...</span>';

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        document.getElementById('latitude').value = position.coords.latitude;
                        document.getElementById('longitude').value = position.coords.longitude;
                        locationDetected = true;
                        locationText.innerHTML = '<span class="text-[var(--ink)] font-semibold text-sm">✓ Lokasi terdeteksi</span>';
                    },
                    function(error) {
                        console.log('GPS error:', error.message);
                        locationDetected = false;
                        document.getElementById('latitude').value = '0';
                        document.getElementById('longitude').value = '0';
                        locationText.innerHTML = '<span class="text-[var(--ink-soft)] text-sm">Lokasi tidak tersedia (foto sebagai bukti)</span>';
                    },
                    {
                        enableHighAccuracy: true,
                        timeout: 15000,
                        maximumAge: 60000
                    }
                );
            } else {
                locationDetected = false;
                document.getElementById('latitude').value = '0';
                document.getElementById('longitude').value = '0';
                locationText.innerHTML = '<span class="text-[var(--ink-soft)] text-sm">GPS tidak tersedia (foto sebagai bukti)</span>';
            }
        }

        function validatePresensi() {
            var foto = document.getElementById('foto').value;
            if (!foto) {
                alert('Harap ambil foto terlebih dahulu sebagai bukti kehadiran');
                return false;
            }
            return true;
        }

        function takePhoto() {
            // Check if getUserMedia is supported (works on both mobile and desktop)
            if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                openCamera();
            } else {
                // Fallback to file input
                var input = document.createElement('input');
                input.type = 'file';
                input.accept = 'image/*';
                input.capture = 'environment';

                input.onchange = function(e) {
                    processFile(e.target.files[0]);
                };
                input.click();
            }
        }

        var currentStream = null;
        var currentFacingMode = 'environment'; // Start with back camera

        function openCamera() {
            // Create modal for camera
            var modal = document.createElement('div');
            modal.id = 'cameraModal';
            modal.style.cssText = 'position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.9);z-index:9999;display:flex;flex-direction:column;align-items:center;justify-content:center;';

            var video = document.createElement('video');
            video.id = 'cameraVideo';
            video.style.cssText = 'max-width:100%;max-height:65vh;border-radius:8px;';
            video.setAttribute('autoplay', '');

            var btnContainer = document.createElement('div');
            btnContainer.style.cssText = 'margin-top:20px;display:flex;gap:15px;justify-content:center;flex-wrap:wrap;';

            var switchBtn = document.createElement('button');
            switchBtn.id = 'switchCameraBtn';
            switchBtn.textContent = '🔄 Ganti Kamera';
            switchBtn.style.cssText = 'padding:12px 20px;background:#3b82f6;color:white;border:none;border-radius:8px;font-size:14px;cursor:pointer;font-weight:bold;';

            var captureBtn = document.createElement('button');
            captureBtn.textContent = '📷 Ambil Foto';
            captureBtn.style.cssText = 'padding:12px 20px;background:#22c55e;color:white;border:none;border-radius:8px;font-size:14px;cursor:pointer;font-weight:bold;';

            var cancelBtn = document.createElement('button');
            cancelBtn.textContent = '✕ Batal';
            cancelBtn.style.cssText = 'padding:12px 20px;background:#ef4444;color:white;border:none;border-radius:8px;font-size:14px;cursor:pointer;font-weight:bold;';

            btnContainer.appendChild(switchBtn);
            btnContainer.appendChild(captureBtn);
            btnContainer.appendChild(cancelBtn);
            modal.appendChild(video);
            modal.appendChild(btnContainer);
            document.body.appendChild(modal);

            // Start camera
            startCamera(currentFacingMode);

            // Switch camera button
            switchBtn.onclick = function() {
                currentFacingMode = currentFacingMode === 'user' ? 'environment' : 'user';
                startCamera(currentFacingMode);
            };

            // Capture button
            captureBtn.onclick = function() {
                var canvas = document.createElement('canvas');
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                canvas.getContext('2d').drawImage(video, 0, 0);

                var compressed = canvas.toDataURL('image/jpeg', 0.7);

                document.getElementById('photoPreview').classList.remove('hidden');
                document.getElementById('photoImg').src = compressed;
                document.getElementById('foto').value = compressed;

                // Stop camera and close modal
                if (currentStream) {
                    currentStream.getTracks().forEach(function(track) { track.stop(); });
                }
                modal.remove();
            };

            // Cancel button
            cancelBtn.onclick = function() {
                if (currentStream) {
                    currentStream.getTracks().forEach(function(track) { track.stop(); });
                }
                modal.remove();
            };
        }

        function startCamera(facingMode) {
            if (currentStream) {
                currentStream.getTracks().forEach(function(track) { track.stop(); });
            }

            navigator.mediaDevices.getUserMedia({ video: { facingMode: facingMode } })
                .then(function(stream) {
                    currentStream = stream;
                    var video = document.getElementById('cameraVideo');
                    if (video) {
                        video.srcObject = stream;
                        video.play();
                    }
                })
                .catch(function(err) {
                    alert('Tidak dapat mengakses kamera: ' + err.message);
                    var modal = document.getElementById('cameraModal');
                    if (modal) modal.remove();
                });
        }

        function processFile(file) {
            if (!file || !file.type.startsWith('image/')) {
                alert('Hanya file gambar yang diperbolehkan');
                return;
            }

            var reader = new FileReader();
            reader.onload = function(event) {
                var img = new Image();
                img.onload = function() {
                    var canvas = document.createElement('canvas');
                    var maxSize = 800;
                    var width = img.width;
                    var height = img.height;

                    if (width > height) {
                        if (width > maxSize) { height *= maxSize / width; width = maxSize; }
                    } else {
                        if (height > maxSize) { width *= maxSize / height; height = maxSize; }
                    }

                    canvas.width = width;
                    canvas.height = height;
                    canvas.getContext('2d').drawImage(img, 0, 0, width, height);

                    var compressed = canvas.toDataURL('image/jpeg', 0.7);
                    document.getElementById('photoPreview').classList.remove('hidden');
                    document.getElementById('photoImg').src = compressed;
                    document.getElementById('foto').value = compressed;
                };
                img.src = event.target.result;
            };
            reader.readAsDataURL(file);
        }
    </script>
</x-layouts.app>
