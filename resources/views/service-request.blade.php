<x-layouts.app :title="'Ajukan Layanan - ' . $service['title'] . ' - SILATAR'">
    @php
        $isEditing = (bool) ($editing ?? false);
        $isAppointment = ! empty($appointmentData);
        $isTpgService = ($service['id'] ?? 0) === 1037;
        $isTpgEdit = $isTpgService && !empty($editPemberkasanId);
        $formAction = $formAction ?? (
            $isTpgEdit
                ? route('pelayanan.tpg.update', $editPemberkasanId)
                : ($isTpgService
                    ? route('pelayanan.tpg.submit', $service['id'])
                    : route('pelayanan.request.submit', $service['id']))
        );
        $backUrl = $backUrl ?? ($isTpgEdit ? route('pengajuan-saya') : route('pelayanan'));

        // Separate file and non-file requirements
        $fileRequirements = collect($service['requirements'])->filter(function($r) {
            return $r['type_normalized'] === 'file';
        })->values();

        $otherRequirements = collect($service['requirements'])->filter(function($r) {
            return $r['type_normalized'] !== 'file';
        })->values();
    @endphp

    <main class="neo-mirai" x-data="fileUploadComponent()" x-init="init">
        <!-- Pass PHP data to component -->
        <script>
            function fileUploadComponent() {
                return {
                    uploadedFiles: {},
                    existingFiles: {!! json_encode($existingFiles ?? []) !!},
                    fileErrors: {},
                    isProcessing: {},
                    deletedFileIds: [],
                    previewModal: { open: false, url: '', filename: '', filetype: '' },
                    validationModal: { open: false, message: '', missingCount: 0 },
                    requiredFileIds: {{ json_encode($fileRequirements->where('is_required', true)->pluck('id')->toArray()) }},

                    init() {
                        // Nothing needed here for now
                    },

                    handleFormSubmit(event) {
                        event.preventDefault();

                        var missingCount = 0;
                        var self = this;

                        this.requiredFileIds.forEach(function(fileId) {
                            var wasDeleted = self.deletedFileIds.includes(fileId);
                            var fileInput = document.querySelector('input[name="files[' + fileId + ']"]');
                            var hasNewFile = fileInput && fileInput.files && fileInput.files.length > 0;
                            var hasExisting = self.existingFiles && self.existingFiles[fileId] && !wasDeleted;

                            if (!hasNewFile && !hasExisting) {
                                missingCount++;
                            }
                        });

                        if (missingCount > 0) {
                            this.validationModal.message = 'Harap upload semua dokumen yang wajib sebelum mengajukan.';
                            this.validationModal.missingCount = missingCount;
                            this.validationModal.open = true;
                            document.body.style.overflow = 'hidden';
                            return;
                        }

                        // Submit form
                        event.target.submit();
                    },

                    async handleFileUpload(syaratId, file, inputEl) {
                        if (!file) {
                            delete this.uploadedFiles[syaratId];
                            delete this.fileErrors[syaratId];
                            return;
                        }

                        delete this.fileErrors[syaratId];
                        this.uploadedFiles[syaratId] = { name: file.name, size: 'Memproses...', status: 'processing' };
                        this.isProcessing[syaratId] = true;

                        try {
                            const processedFile = await window.processFile(file);
                            const dataTransfer = new DataTransfer();
                            dataTransfer.items.add(processedFile);
                            inputEl.files = dataTransfer.files;
                            this.uploadedFiles[syaratId] = { name: processedFile.name, size: window.formatFileSize(processedFile.size), status: 'ready' };
                        } catch (error) {
                            this.uploadedFiles[syaratId] = { name: file.name, size: window.formatFileSize(file.size), status: 'error' };
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
                    },

                    deleteExistingFile(syaratId) {
                        if (this.existingFiles && this.existingFiles[syaratId]) {
                            if (!this.deletedFileIds.includes(syaratId)) {
                                this.deletedFileIds.push(syaratId);
                            }
                            delete this.existingFiles[syaratId];
                        }
                    },

                    openPreviewModal(url, filename, filetype) {
                        this.previewModal = { open: true, url, filename, filetype };
                        document.body.style.overflow = 'hidden';
                    },

                    closePreviewModal() {
                        this.previewModal.open = false;
                        this.previewModal.url = '';
                        this.previewModal.filename = '';
                        this.previewModal.filetype = '';
                        document.body.style.overflow = '';
                    },

                    closeValidationModal() {
                        this.validationModal.open = false;
                        document.body.style.overflow = '';
                    }
                };
            }
        </script>

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
                <form action="{{ $formAction }}" method="POST" class="neo-form-body" enctype="multipart/form-data" @submit.prevent="handleFormSubmit">
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

                    <!-- Hidden input untuk track deleted files (use array notation for Laravel) -->
                    <template x-for="id in deletedFileIds" :key="id">
                        <input type="hidden" name="deleted_files[]" :value="id">
                    </template>

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
                                            <div class="neo-upload-preview has-existing">
                                                <button
                                                    type="button"
                                                    @click="openPreviewModal(existingFiles[{{ $requirement['id'] }}]?.url, existingFiles[{{ $requirement['id'] }}]?.filename, existingFiles[{{ $requirement['id'] }}]?.filetype)"
                                                    style="text-decoration: none; color: inherit; display: flex; flex-direction: column; align-items: center; gap: 0.25rem; flex: 1; background: none; border: none; cursor: pointer; padding: 0;"
                                                >
                                                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                                                        <polyline points="14 2 14 8 20 8"/>
                                                        <line x1="16" y1="13" x2="8" y2="13"/>
                                                        <line x1="16" y1="17" x2="8" y2="17"/>
                                                        <polyline points="10 9 9 9 8 9"/>
                                                    </svg>
                                                    <span class="neo-file-name" x-text="existingFiles[{{ $requirement['id'] }}]?.filename || 'File sudah ada'" style="font-size: 0.7rem; text-align: center; word-break: break-all;"></span>
                                                    <span class="neo-file-status uploaded">Lihat</span>
                                                </button>
                                                @php
                                                    $isCompleted = isset($existingSubmission) && in_array($existingSubmission->status ?? '', ['SUKSES', 'SELESAI', 'DITOLAK', 'BATAL']);
                                                @endphp
                                                @unless($isCompleted)
                                                <div style="display: flex; gap: 0.5rem; margin-top: 0.5rem;">
                                                    <button
                                                        type="button"
                                                        class="neo-btn-replace"
                                                        onclick="document.getElementById('req_{{ $requirement['id'] }}').click();"
                                                        style="padding: 0.5rem 0.75rem; font-size: 0.7rem; background: var(--gold); color: var(--night); border: none; border-radius: 0.35rem; cursor: pointer; display: inline-flex; align-items: center; gap: 0.35rem; font-weight: 600;"
                                                    >
                                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/>
                                                            <polyline points="17 8 12 3 7 8"/>
                                                            <line x1="12" y1="3" x2="12" y2="15"/>
                                                        </svg>
                                                        Ganti File
                                                    </button>
                                                    <button
                                                        type="button"
                                                        @click="if(confirm('Yakin ingin hapus file ini?')) { deleteExistingFile({{ $requirement['id'] }}); }"
                                                        style="padding: 0.5rem 0.75rem; font-size: 0.7rem; background: oklch(60% 0.2 25); color: white; border: none; border-radius: 0.35rem; cursor: pointer; display: inline-flex; align-items: center; gap: 0.35rem; font-weight: 600;"
                                                    >
                                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <polyline points="3 6 5 6 21 6"/>
                                                            <path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/>
                                                        </svg>
                                                        Hapus File
                                                    </button>
                                                </div>
                                                @endunless
                                            </div>
                                        </template>
                                        <input
                                            type="file"
                                            name="files[{{ $requirement['id'] }}]"
                                            id="req_{{ $requirement['id'] }}"
                                            class="neo-upload-input"
                                            @change="handleFileUpload({{ $requirement['id'] }}, $event.target.files[0], $event.target)"
                                            accept=".pdf,.jpg,.jpeg,.png"
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

        <!-- Preview Modal -->
        <div
            x-show="previewModal.open"
            x-transition:enter="neo-modal-enter"
            x-transition:enter-start="neo-modal-enter-start"
            x-transition:enter-end="neo-modal-enter-end"
            x-transition:leave="neo-modal-leave"
            x-transition:leave-start="neo-modal-leave-start"
            x-transition:leave-end="neo-modal-leave-end"
            class="neo-modal-overlay"
            @click="closePreviewModal()"
            style="position: fixed; inset: 0; background: rgba(0,0,0,0.7); z-index: 9999; padding: 1rem; display: flex; align-items: center; justify-content: center;"
        >
            <div
                @click.stop
                class="neo-modal-content"
                style="background: var(--paper); border-radius: 1rem; max-width: 900px; width: 100%; max-height: 90vh; display: flex; flex-direction: column; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);"
            >
                <!-- Modal Header -->
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 1rem 1.5rem; border-bottom: 1px solid var(--line); background: var(--paper-soft);">
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--gold);">
                            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                        </svg>
                        <span style="font-weight: 600; color: var(--ink);" x-text="previewModal.filename || 'Preview File'"></span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <a
                            :href="previewModal.url"
                            download
                            class="neo-btn"
                            style="padding: 0.5rem 1rem; font-size: 0.7rem; background: oklch(65% 0.15 145); color: white; display: inline-flex; align-items: center; gap: 0.35rem;"
                        >
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/>
                                <polyline points="7 10 12 15 17 10"/>
                                <line x1="12" y1="15" x2="12" y2="3"/>
                            </svg>
                            Download
                        </a>
                        <button
                            type="button"
                            @click="closePreviewModal()"
                            style="padding: 0.5rem; background: none; border: 1px solid var(--line); border-radius: 0.5rem; cursor: pointer; color: var(--ink-soft); display: flex; align-items: center; justify-content: center;"
                        >
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="18" y1="6" x2="6" y2="18"/>
                                <line x1="6" y1="6" x2="18" y2="18"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <!-- Modal Body -->
                <div style="flex: 1; overflow: auto; padding: 1rem; background: var(--ink); display: flex; align-items: center; justify-content: center;">
                    <template x-if="previewModal.url">
                        <iframe
                            :src="previewModal.url"
                            style="width: 100%; height: 70vh; border: none; border-radius: 0.5rem;"
                            title="Preview File"
                        ></iframe>
                    </template>
                </div>
            </div>
        </div>

        <!-- Validation Error Modal -->
        <div
            x-show="validationModal.open"
            x-transition:enter="neo-modal-enter"
            x-transition:enter-start="neo-modal-enter-start"
            x-transition:enter-end="neo-modal-enter-end"
            x-transition:leave="neo-modal-leave"
            x-transition:leave-start="neo-modal-leave-start"
            x-transition:leave-end="neo-modal-leave-end"
            class="neo-modal-overlay"
            @click="closeValidationModal()"
            style="position: fixed; inset: 0; background: rgba(0,0,0,0.7); z-index: 99999; padding: 1rem; display: flex; align-items: center; justify-content: center;"
        >
            <div
                @click.stop
                style="background: linear-gradient(135deg, oklch(60% 0.2 25) 0%, oklch(55% 0.18 20) 100%); border-radius: 1rem; max-width: 480px; width: 100%; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5), 0 0 0 1px rgba(255,255,255,0.1);"
            >
                <!-- Modal Header -->
                <div style="padding: 1.5rem 1.5rem 1rem; text-align: center;">
                    <div style="width: 64px; height: 64px; margin: 0 auto 1rem; background: rgba(255,255,255,0.15); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                            <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                            <line x1="12" y1="9" x2="12" y2="13"/>
                            <line x1="12" y1="17" x2="12.01" y2="17"/>
                        </svg>
                    </div>
                    <h3 style="margin: 0 0 0.5rem; font-size: 1.25rem; font-weight: 700; color: white;">Dokumen Wajib Belum Lengkap</h3>
                    <p style="margin: 0; color: rgba(255,255,255,0.8); line-height: 1.5;" x-text="validationModal.message"></p>
                </div>
                <!-- Info Box -->
                <div style="margin: 0 1.5rem 1.5rem; background: rgba(255,255,255,0.1); border-radius: 0.75rem; padding: 1rem;">
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <div style="width: 40px; height: 40px; background: rgba(255,255,255,0.2); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center;">
                            <span style="font-size: 1.25rem; font-weight: 700; color: white;" x-text="validationModal.missingCount"></span>
                        </div>
                        <div>
                            <p style="margin: 0; font-size: 0.85rem; color: rgba(255,255,255,0.7);">Dokumen wajib</p>
                            <p style="margin: 0; font-size: 0.85rem; color: rgba(255,255,255,0.7);">belum diupload</p>
                        </div>
                    </div>
                </div>
                <!-- Action Button -->
                <div style="padding: 0 1.5rem 1.5rem;">
                    <button
                        type="button"
                        @click="closeValidationModal()"
                        style="width: 100%; padding: 1rem; background: white; color: oklch(60% 0.2 25); border: none; border-radius: 0.75rem; font-size: 0.9rem; font-weight: 600; cursor: pointer; transition: transform 0.2s, box-shadow 0.2s;"
                    >
                        Saya Mengerti!
                    </button>
                </div>
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
