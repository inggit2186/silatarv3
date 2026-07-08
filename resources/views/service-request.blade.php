<x-layouts.app :title="'Ajukan Layanan - ' . $service['title'] . ' - SILATAR'">
    @php
        $isEditing = (bool) ($editing ?? false);
        $isAppointment = ! empty($appointmentData);
        $isTpgService = ($service['id'] ?? 0) === 1037;
        $formAction = $formAction ?? (
            $isTpgService
                ? route('pelayanan.tpg.submit', $service['id'])
                : route('pelayanan.request.submit', $service['id'])
        );
        $backUrl = $backUrl ?? route('pelayanan');

        // Separate file and non-file requirements
        $fileRequirements = collect($service['requirements'])->filter(function($r) {
            return $r['type_normalized'] === 'file';
        })->values();

        $otherRequirements = collect($service['requirements'])->filter(function($r) {
            return $r['type_normalized'] !== 'file';
        })->values();
    @endphp

    <main class="neo-mirai" x-data="{
        uploadedFiles: {},
        existingFiles: {{ json_encode($existingFiles ?? []) }},
        fileErrors: {},
        isProcessing: {},
        async handleFileUpload(syaratId, file, inputEl) {
            if (!file) {
                delete this.uploadedFiles[syaratId];
                delete this.fileErrors[syaratId];
                return;
            }

            // Reset error
            delete this.fileErrors[syaratId];
            this.uploadedFiles[syaratId] = {
                name: file.name,
                size: 'Memproses...',
                status: 'processing'
            };
            this.isProcessing[syaratId] = true;

            try {
                // Process file (validate size + compress)
                const processedFile = await window.processFile(file);

                // Update file in input for form submission
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(processedFile);
                inputEl.files = dataTransfer.files;

                this.uploadedFiles[syaratId] = {
                    name: processedFile.name,
                    size: window.formatFileSize(processedFile.size),
                    status: 'ready'
                };
            } catch (error) {
                this.uploadedFiles[syaratId] = {
                    name: file.name,
                    size: window.formatFileSize(file.size),
                    status: 'error'
                };
                this.fileErrors[syaratId] = error.message || 'Gagal memproses file';
            } finally {
                delete this.isProcessing[syaratId];
            }
        },
        hasExistingFile(syaratId) {
            return this.existingFiles && this.existingFiles[syaratId];
        },
        getFileError(syaratId) {
            return this.fileErrors[syaratId];
        }
    }">
        <x-layouts.site-header />

        <!-- Hero Section -->
        <section class="hero-page" style="background-image: url('/assets/img/template/layanan-bg.webp'); background-size: cover; background-position: center top; padding: 120px 2rem 4rem; min-height: 300px;">
            <div class="hero-page-content" style="max-width: 36rem; text-align: center;">
                <p class="section-label" style="color: var(--gold); font-family: var(--font-mono); font-size: 0.65rem; text-transform: uppercase; margin: 0 0 0.5rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Formulir Pengajuan
                </p>
                <h1 class="hero-page-title" style="font-family: var(--font-display); font-size: clamp(1.5rem, 3vw, 2.5rem); font-weight: 600; color: var(--ink); margin: 0 0 1rem;">
                    {{ $service['title'] }}
                </h1>
                <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 0.75rem; margin-top: 1rem;">
                    <a href="{{ $backUrl }}" class="neo-btn-back">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                        Kembali
                    </a>
                </div>
            </div>
        </section>

        <div class="section-divider wave-rounded"></div>

        <!-- Main Content - Full Width -->
        <section class="page-content" style="padding: 2rem;">
            <div class="neo-request-form">
                <!-- Header Info -->
                <div class="neo-form-header">
                    <div class="neo-form-header-info">
                        <div class="neo-info-item">
                            <span class="neo-info-label">Unit</span>
                            <span class="neo-info-value">{{ $service['unit_name'] }}</span>
                        </div>
                        <div class="neo-info-item">
                            <span class="neo-info-label">Waktu</span>
                            <span class="neo-info-value">{{ $service['waktu'] }}</span>
                        </div>
                        <div class="neo-info-item">
                            <span class="neo-info-label">Biaya</span>
                            <span class="neo-info-value">{{ $service['biaya'] }}</span>
                        </div>
                    </div>

                    <!-- Periode Info for TPG -->
                    @if ($isTpgService && !empty($tahunPelajaran))
                    <div class="neo-periode-banner">
                        <div class="neo-periode-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                <line x1="16" y1="2" x2="16" y2="6"/>
                                <line x1="8" y1="2" x2="8" y2="6"/>
                                <line x1="3" y1="10" x2="21" y2="10"/>
                            </svg>
                        </div>
                        <div class="neo-periode-text">
                            <span class="neo-periode-title">Periode Pencairan</span>
                            <span class="neo-periode-detail">{{ $tahunPelajaran }} - Semester {{ $semester }}</span>
                        </div>
                    </div>
                    @endif

                    <!-- Appointment Info -->
                    @if ($isAppointment && $appointmentData)
                    <div class="neo-appointment-banner">
                        <div class="neo-appointment-avatar">
                            @if (! empty($appointmentData['employee_photo']) && str_starts_with($appointmentData['employee_photo'], 'http'))
                                <img src="{{ $appointmentData['employee_photo'] }}" alt="{{ $appointmentData['employee_name'] }}">
                            @else
                                <span>{{ $appointmentData['type'] === 'direct' ? 'S' : substr($appointmentData['employee_name'], 0, 2) }}</span>
                            @endif
                        </div>
                        <div class="neo-appointment-text">
                            <span class="neo-appointment-title">Tujuan</span>
                            <span class="neo-appointment-name">{{ $appointmentData['employee_name'] }}</span>
                            <span class="neo-appointment-role">{{ $appointmentData['employee_role'] }}</span>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Form -->
                <form action="{{ $formAction }}" method="POST" class="neo-form-body" enctype="multipart/form-data">
                    @csrf

                    <!-- Existing Submission Info (TPG Service) -->
                    @if ($isTpgService && $existingSubmission)
                        <div class="neo-alert neo-alert-info" style="margin-bottom: 1rem;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="16" x2="12" y2="12"></line>
                                <line x1="12" y1="8" x2="12.01" y2="8"></line>
                            </svg>
                            <div>
                                <strong>Pengajuan sebelumnya ditemukan</strong>
                                <p style="margin: 0.25rem 0 0;">
                                    Status: <span class="neo-badge neo-badge-{{ $existingSubmission->status === 'SUBMITTED' ? 'success' : 'warning' }}">{{ $existingSubmission->status }}</span>
                                    <span style="margin-left: 1rem;">No.Req: {{ $existingSubmission->noreq }}</span>
                                </p>
                                @if ($existingSubmission->status === 'SUBMITTED')
                                    <p style="margin: 0.25rem 0 0; color: var(--text-muted);">Data file yang sudah diupload akan tetap tersimpan. Upload ulang file untuk memperbarui.</p>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Hidden fields -->
                    @if ($isTpgService && !empty($tahunPelajaran))
                        <input type="hidden" name="tahun_pelajaran" value="{{ $tahunPelajaran }}">
                        <input type="hidden" name="semester" value="{{ $semester }}">
                        @if ($existingSubmission)
                            <input type="hidden" name="noreq" value="{{ $existingSubmission->noreq }}">
                        @endif
                    @endif

                    @if ($isEditing && !empty($requestRecord))
                        <input type="hidden" name="request_id" value="{{ $requestRecord->id }}">
                    @endif

                    @if ($isAppointment && $appointmentData)
                        @if ($appointmentData['type'] === 'direct')
                            <input type="hidden" name="appointment_type" value="direct">
                            <input type="hidden" name="dept_id" value="{{ $service['dept_id'] ?? '' }}">
                        @else
                            <input type="hidden" name="appointment_type" value="employee">
                            <input type="hidden" name="employee_id" value="{{ $appointmentData['employee_id'] }}">
                        @endif
                    @endif

                    <!-- File Uploads Section - 3 Columns Grid -->
                    @if ($fileRequirements->count() > 0)
                    <div class="neo-upload-section">
                        <div class="neo-section-header">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                                <polyline points="14 2 14 8 20 8"/>
                                <line x1="12" y1="18" x2="12" y2="12"/>
                                <line x1="9" y1="15" x2="15" y2="15"/>
                            </svg>
                            <span>Unggah Dokumen</span>
                            <span class="neo-section-count">{{ $fileRequirements->count() }} dokumen</span>
                        </div>

                        <div class="neo-upload-grid">
                            @foreach($fileRequirements as $index => $requirement)
                            <div class="neo-upload-card" :class="{ 'has-file': uploadedFiles[{{ $requirement['id'] }}] || hasExistingFile({{ $requirement['id'] }}), 'has-existing': hasExistingFile({{ $requirement['id'] }}) }">
                                <div class="neo-upload-card-header">
                                    <span class="neo-upload-number">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                    @if ($requirement['is_required'])
                                        <span class="neo-upload-badge wajib">Wajib</span>
                                    @else
                                        <span class="neo-upload-badge opsional">Opsional</span>
                                    @endif
                                </div>

                                <div class="neo-upload-card-body">
                                    <label class="neo-upload-dropzone" :class="{
                                        'has-file': uploadedFiles[{{ $requirement['id'] }}],
                                        'is-processing': isProcessing[{{ $requirement['id'] }}],
                                        'has-error': fileErrors[{{ $requirement['id'] }}]
                                    }">
                                        <template x-if="isProcessing[{{ $requirement['id'] }}]">
                                            <div class="neo-upload-processing">
                                                <div class="neo-upload-spinner"></div>
                                                <span>Memproses file...</span>
                                            </div>
                                        </template>
                                        <template x-if="!uploadedFiles[{{ $requirement['id'] }}] && !hasExistingFile({{ $requirement['id'] }}) && !isProcessing[{{ $requirement['id'] }}]">
                                            <div class="neo-upload-placeholder">
                                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                    <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/>
                                                    <polyline points="17 8 12 3 7 8"/>
                                                    <line x1="12" y1="3" x2="12" y2="15"/>
                                                </svg>
                                                <span>Klik untuk pilih file</span>
                                                <span class="neo-upload-hint">PDF, JPG, PNG (max 2MB)</span>
                                            </div>
                                        </template>
                                        <template x-if="uploadedFiles[{{ $requirement['id'] }}] && !isProcessing[{{ $requirement['id'] }}]">
                                            <div class="neo-upload-preview" :class="{ 'is-error': uploadedFiles[{{ $requirement['id'] }}]?.status === 'error' }">
                                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                                                    <polyline points="14 2 14 8 20 8"/>
                                                </svg>
                                                <span class="neo-file-name" x-text="uploadedFiles[{{ $requirement['id'] }}]?.name"></span>
                                                <span class="neo-file-size" x-text="uploadedFiles[{{ $requirement['id'] }}]?.size"></span>
                                                <template x-if="uploadedFiles[{{ $requirement['id'] }}]?.status === 'ready'">
                                                    <span class="neo-file-status ready">Siap</span>
                                                </template>
                                                <template x-if="uploadedFiles[{{ $requirement['id'] }}]?.status === 'error'">
                                                    <span class="neo-file-status error">Error</span>
                                                </template>
                                            </div>
                                        </template>
                                        <template x-if="!uploadedFiles[{{ $requirement['id'] }}] && hasExistingFile({{ $requirement['id'] }}) && !isProcessing[{{ $requirement['id'] }}]">
                                            <a
                                                :href="existingFiles[{{ $requirement['id'] }}]?.url"
                                                target="_blank"
                                                class="neo-upload-preview has-existing"
                                                title="Klik untuk melihat file"
                                            >
                                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                                                    <polyline points="14 2 14 8 20 8"/>
                                                    <line x1="16" y1="13" x2="8" y2="13"/>
                                                    <line x1="16" y1="17" x2="8" y2="17"/>
                                                    <polyline points="10 9 9 9 8 9"/>
                                                </svg>
                                                <span class="neo-file-name" x-text="existingFiles[{{ $requirement['id'] }}]?.filename || 'File sudah ada'"></span>
                                                <span class="neo-file-status uploaded">Terverifikasi</span>
                                            </a>
                                        </template>
                                        <input
                                            type="file"
                                            name="files[{{ $requirement['id'] }}]"
                                            id="req_{{ $requirement['id'] }}"
                                            class="neo-upload-input"
                                            @change="handleFileUpload({{ $requirement['id'] }}, $event.target.files[0], $event.target)"
                                            accept=".pdf,.jpg,.jpeg,.png"
                                            {{ $requirement['is_required'] && !($isEditing) ? 'required' : '' }}
                                        >
                                    </label>
                                    <template x-if="fileErrors[{{ $requirement['id'] }}]">
                                        <div class="neo-upload-error">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <circle cx="12" cy="12" r="10"/>
                                                <line x1="12" y1="8" x2="12" y2="12"/>
                                                <line x1="12" y1="16" x2="12.01" y2="16"/>
                                            </svg>
                                            <span x-text="fileErrors[{{ $requirement['id'] }}]"></span>
                                        </div>
                                    </template>
                                </div>

                                <div class="neo-upload-card-footer">
                                    <span class="neo-upload-title">{{ $requirement['title'] }}</span>
                                    @if ($requirement['note'])
                                        <span class="neo-upload-note">{{ $requirement['note'] }}</span>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Other Form Fields -->
                    @if ($otherRequirements->count() > 0)
                    <div class="neo-fields-section">
                        <div class="neo-section-header">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                            </svg>
                            <span>Data Pendukung</span>
                            <span class="neo-section-count">{{ $otherRequirements->count() }} field</span>
                        </div>

                        <div class="neo-fields-grid">
                            @foreach($otherRequirements as $requirement)
                            <div class="neo-field-item">
                                <label for="req_{{ $requirement['id'] }}" class="neo-field-label">
                                    {{ $requirement['title'] }}
                                    @if ($requirement['is_required'])
                                        <span class="neo-field-required">Wajib</span>
                                    @endif
                                </label>

                                @if ($requirement['type_normalized'] === 'textarea')
                                    <textarea
                                        name="req_{{ $requirement['id'] }}"
                                        id="req_{{ $requirement['id'] }}"
                                        class="neo-field-input neo-field-textarea"
                                        rows="4"
                                        placeholder="{{ $requirement['note'] ?? '' }}"
                                        {{ $requirement['is_required'] ? 'required' : '' }}
                                    >{{ old('req_' . $requirement['id']) }}</textarea>
                                @elseif ($requirement['type_normalized'] === 'select' && !empty($requirement['options']))
                                    <select name="req_{{ $requirement['id'] }}" id="req_{{ $requirement['id'] }}" class="neo-field-input neo-field-select" {{ $requirement['is_required'] ? 'required' : '' }}>
                                        <option value="">-- Pilih --</option>
                                        @foreach($requirement['options'] as $option)
                                            <option value="{{ $option }}" {{ old('req_' . $requirement['id']) === $option ? 'selected' : '' }}>{{ $option }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <input
                                        type="{{ $requirement['input_type'] ?? 'text' }}"
                                        name="req_{{ $requirement['id'] }}"
                                        id="req_{{ $requirement['id'] }}"
                                        class="neo-field-input"
                                        placeholder="{{ $requirement['note'] ?? '' }}"
                                        {{ $requirement['is_required'] ? 'required' : '' }}
                                        value="{{ old('req_' . $requirement['id']) }}"
                                    >
                                @endif

                                @error('req_' . $requirement['id'])
                                    <span class="neo-field-error">{{ $message }}</span>
                                @enderror
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Keterangan Tambahan -->
                    <div class="neo-keterangan-section">
                        <div class="neo-section-header">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="17" y1="10" x2="3" y2="10"/>
                                <line x1="21" y1="6" x2="3" y2="6"/>
                                <line x1="21" y1="14" x2="3" y2="14"/>
                                <line x1="17" y1="18" x2="3" y2="18"/>
                            </svg>
                            <span>Keterangan Tambahan</span>
                        </div>
                        <textarea
                            name="deskripsi"
                            id="deskripsi"
                            class="neo-field-input neo-field-textarea"
                            rows="3"
                            placeholder="Tambahkan keterangan lain jika diperlukan..."
                        >{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <span class="neo-field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Submit Actions -->
                    <div class="neo-form-actions">
                        <a href="{{ $backUrl }}" class="neo-btn-cancel-action">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M19 12H5M12 19l-7-7 7-7"/>
                            </svg>
                            Batal
                        </a>
                        <button type="submit" name="submit_action" value="draft" class="neo-btn-draft">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/>
                                <polyline points="17 21 17 13 7 13 7 21"/>
                            </svg>
                            Simpan Draft
                        </button>
                        <button type="submit" name="submit_action" value="submit" class="neo-btn-submit">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"/>
                            </svg>
                            Ajukan Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </section>

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
