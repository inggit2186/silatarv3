<x-layouts.app title="Ajukan Layanan - SILATAR">
    @php
        $isEditing = (bool) ($editing ?? false);
        $formAction = $formAction ?? route('pelayanan.request.submit', $service['id']);
        $backUrl = $backUrl ?? route('pelayanan');
        $pageKicker = $isEditing ? 'Edit Request' : 'Service Request';
        $bannerTitle = $isEditing ? 'Edit ' . $service['title'] : 'Ajukan ' . $service['title'];
        $bannerSubtitle = $isEditing
            ? 'Perbarui data dan unggah syarat yang diperlukan.'
            : 'Lengkapi data dan unggah syarat untuk melanjutkan pengajuan.';
    @endphp
    <main
        class="mx-auto max-w-7xl px-6 py-8 lg:px-8 lg:py-10"
        x-data="requestForm(@js(collect($service['requirements'])->where('is_required', true)->pluck('id')->values()))"
        @requirement-changed.window="markRequirement($event.detail.id, $event.detail.hasValue)"
        @request-preview-open.window="openRequirementPreview($event.detail)"
    >
        <div class="silatar-request-banner">
            <div class="silatar-request-banner-inner">
                <div class="silatar-request-banner-brand">
                    <div class="silatar-request-banner-mark">
                        <img src="{{ $service['cover_path'] }}" alt="{{ $service['title'] }}">
                    </div>
                    <div>
                        <p class="silatar-request-banner-kicker">{{ $pageKicker }}</p>
                        <h1 class="silatar-request-banner-title">
                            {{ $bannerTitle }}
                        </h1>
                        <p class="silatar-request-banner-subtitle">
                            {{ $bannerSubtitle }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="silatar-request-banner-status">
                        {{ $service['status_label'] }}
                    </div>
                    @if ($service['is_spesial'])
                        <div class="silatar-request-banner-special">
                            Layanan Spesial
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-6 rounded-[1.5rem] border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm leading-6 text-emerald-800 shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 rounded-[1.5rem] border border-rose-200 bg-rose-50 px-5 py-4 text-sm leading-6 text-rose-800 shadow-sm">
                <p class="font-semibold">Ada beberapa isian yang perlu diperbaiki:</p>
                <ul class="mt-3 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>&bull; {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid gap-6">
            <section class="space-y-6">
                <div class="silatar-request-surface">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div class="min-w-0">
                            <p class="silatar-request-section-label">
                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 6.5A2 2 0 0 1 6.5 4.5h7A2 2 0 0 1 15.5 6.5v7A2 2 0 0 1 13.5 15.5h-7a2 2 0 0 1-2-2z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 8.5h6M7 11.5h4" />
                                </svg>
                                Detail Layanan
                            </p>
                            <h2 class="mt-1 truncate text-2xl font-semibold text-slate-950">{{ $service['title'] }}</h2>
                            <p class="mt-2 text-sm leading-6 text-slate-600">
                                {{ $service['unit_name'] }} &bull; {{ $service['status_label'] }} &bull; {{ $service['waktu'] }} &bull; {{ $service['biaya'] }}
                            </p>
                        </div>
                        <a href="{{ $backUrl }}" class="inline-flex items-center gap-2 rounded-full border border-cyan-200 bg-cyan-50 px-4 py-2.5 text-sm font-semibold text-cyan-800 shadow-sm transition hover:-translate-y-0.5 hover:border-cyan-300 hover:bg-cyan-100 hover:text-cyan-900">
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12.5 5 8 9.5l4.5 4.5" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.5 9.5H16" />
                            </svg>
                            Kembali
                        </a>
                    </div>
                    <p class="mt-4 text-sm leading-7 text-slate-600">
                        {{ $service['description'] }}
                    </p>
                </div>

                <form
                    action="{{ $formAction }}"
                    method="POST"
                    enctype="multipart/form-data"
                    class="silatar-request-surface"
                >
                    @csrf
                    @if ($isEditing && ! empty($requestRecord?->id))
                        <input type="hidden" name="request_id" value="{{ $requestRecord->id }}">
                    @endif

                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="silatar-request-section-label">
                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 4.5h10A1.5 1.5 0 0 1 16.5 6v8A1.5 1.5 0 0 1 15 15.5H5A1.5 1.5 0 0 1 3.5 14V6A1.5 1.5 0 0 1 5 4.5Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 8h6M7 11h5" />
                                </svg>
                                Form Input
                            </p>
                            <h3 class="mt-1 text-xl font-semibold text-slate-950">Data Pemohon dan Berkas</h3>
                        </div>
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-slate-600">
                            {{ count($service['requirements']) }} syarat
                        </span>
                    </div>

                    <div class="mt-5 grid gap-4 sm:grid-cols-2">
                        <label class="block">
                            <span class="mb-2 block text-sm font-medium text-slate-700">Nama User</span>
                            <input
                                type="text"
                                name="nama_user"
                                value="{{ old('nama_user', $requester['name'] ?? '') }}"
                                class="w-full rounded-2xl border border-slate-200 bg-slate-100 px-4 py-3 text-slate-700 shadow-sm outline-none"
                                placeholder="Nama pengguna"
                                readonly
                            >
                        </label>

                        <label class="block">
                            <span class="mb-2 block text-sm font-medium text-slate-700">Nomor Identitas</span>
                            <input
                                type="text"
                                name="nomor_identitas"
                                value="{{ old('nomor_identitas', $requester['identity'] ?? '') }}"
                                class="w-full rounded-2xl border border-slate-200 bg-slate-100 px-4 py-3 text-slate-700 shadow-sm outline-none"
                                placeholder="NIK / NIP / identitas lain"
                                readonly
                            >
                        </label>
                    </div>

                    <div class="mt-6">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <h3 class="inline-flex items-center gap-2 text-xl font-semibold text-slate-950">
                                    <svg class="h-5 w-5 text-cyan-700" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 13V4" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.5 7.5 10 4l3.5 3.5" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.5v2A1.5 1.5 0 0 0 6 16h8a1.5 1.5 0 0 0 1.5-1.5v-2" />
                                    </svg>
                                    Upload File Syarat
                                </h3>
                                <p class="mt-1 text-sm leading-6 text-slate-500">Unggah berkas sesuai daftar syarat.</p>
                            </div>
                            <span class="rounded-full bg-cyan-50 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-cyan-700">
                                Dinamis
                            </span>
                        </div>

                        @php
                            $requirementsByType = collect($service['requirements'])->groupBy('type_normalized');
                            $typeSequence = ['file', 'image', 'date', 'datetime', 'input', 'textarea', 'option'];
                            $typeMeta = [
                                'file' => ['label' => 'Unggah PDF', 'icon' => 'file'],
                                'image' => ['label' => 'Unggah Gambar', 'icon' => 'image'],
                                'date' => ['label' => 'Tanggal', 'icon' => 'calendar'],
                                'datetime' => ['label' => 'Tanggal & Waktu', 'icon' => 'clock'],
                                'input' => ['label' => 'Input Singkat', 'icon' => 'keyboard'],
                                'textarea' => ['label' => 'Textarea', 'icon' => 'notes'],
                                'option' => ['label' => 'Pilihan', 'icon' => 'select'],
                            ];
                        @endphp

                        @foreach ($typeSequence as $type)
                            @php
                                $typeRequirements = $requirementsByType->get($type, collect());
                                $isUploadType = in_array($type, ['file', 'image'], true);
                                $groupLabel = $typeMeta[$type]['label'] ?? ucfirst($type);
                                $groupIcon = $typeMeta[$type]['icon'] ?? 'file';
                            @endphp

                            @if ($typeRequirements->isNotEmpty())
                                <section class="silatar-request-type-group">
                                    <div class="silatar-request-type-header">
                                        <div class="min-w-0">
                                            <p class="silatar-request-section-label">
                                                @if ($groupIcon === 'file')
                                                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 4.5h6l3.5 3.5V15.5A1.5 1.5 0 0 1 14 17H6a1.5 1.5 0 0 1-1.5-1.5v-11A1 1 0 0 1 6 4.5Z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5V8h3.5" />
                                                    </svg>
                                                @elseif ($groupIcon === 'image')
                                                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 6.5A2 2 0 0 1 6.5 4.5h7A2 2 0 0 1 15.5 6.5v7A2 2 0 0 1 13.5 15.5h-7a2 2 0 0 1-2-2z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m6 12 2.2-2.2a1 1 0 0 1 1.4 0L13 13.4" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.5 8.5h.01" />
                                                    </svg>
                                                @elseif ($groupIcon === 'calendar')
                                                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 4.5h10A1.5 1.5 0 0 1 16.5 6v8A1.5 1.5 0 0 1 15 15.5H5A1.5 1.5 0 0 1 3.5 14V6A1.5 1.5 0 0 1 5 4.5Z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.5 3.5v2M13.5 3.5v2M3.5 8h13" />
                                                    </svg>
                                                @elseif ($groupIcon === 'clock')
                                                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 4.5a5.5 5.5 0 1 1 0 11 5.5 5.5 0 0 1 0-11Z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 7v3l2 1.5" />
                                                    </svg>
                                                @elseif ($groupIcon === 'keyboard')
                                                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 6.5h11A1.5 1.5 0 0 1 17 8v4A1.5 1.5 0 0 1 15.5 13.5h-11A1.5 1.5 0 0 1 3 12V8A1.5 1.5 0 0 1 4.5 6.5Z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 9h.01M8.5 9h.01M11 9h.01M13.5 9h.01M6 11h8" />
                                                    </svg>
                                                @elseif ($groupIcon === 'notes')
                                                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 4.5h10A1.5 1.5 0 0 1 16.5 6v8A1.5 1.5 0 0 1 15 15.5H5A1.5 1.5 0 0 1 3.5 14V6A1.5 1.5 0 0 1 5 4.5Z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 8h6M7 11h5" />
                                                    </svg>
                                                @else
                                                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5.5 6.5h9M5.5 10h9M5.5 13.5h5" />
                                                    </svg>
                                                @endif
                                                {{ $groupLabel }}
                                            </p>
                                        </div>
                                        <span class="silatar-request-type-badge">
                                            {{ $typeRequirements->count() }} item
                                        </span>
                                    </div>

                                    @if ($isUploadType)
                                        <div class="silatar-request-upload-grid">
                                            @foreach ($typeRequirements as $requirement)
                                                @php
                                                    $type = $requirement['type_normalized'] ?? strtolower((string) ($requirement['type'] ?? 'file'));
                                                    $controlLabel = $requirement['type_label'] ?? ucfirst($type);
                                                    $existingFile = $existingFiles[$requirement['id']] ?? null;
                                                    $hasExistingUpload = ! empty($existingFile['filename'] ?? null);
                                                    $initialValue = old('values.' . $requirement['id']);
                                                    $uploadEmpty = asset('assets/img/ikon/filenotfound.png');
                                                    $uploadFilled = asset('assets/img/ikon/FileUploaded.png');
                                                @endphp
                                                <div
                                                    x-data="requestRequirementCard({
                                                        kind: @js($type),
                                                        emptySrc: @js($uploadEmpty),
                                                        filledSrc: @js($uploadFilled),
                                                        initialValue: @js($initialValue),
                                                        initialFileName: @js($existingFile['filename'] ?? ''),
                                                        initialFileType: @js($existingFile['filetype'] ?? ''),
                                                        initialPreviewSrc: @js($existingFile['url'] ?? ''),
                                                    })"
                                                    x-init="initRequirement({{ $requirement['id'] }}, {{ $requirement['is_required'] ? 'true' : 'false' }})"
                                                    :class="{ 'is-filled': hasValue, 'is-just-uploaded': justUpdated }"
                                                    class="silatar-request-upload-card"
                                                >
                                                    <div class="flex items-start justify-between gap-3">
                                                        <div class="min-w-0">
                                                            <p class="silatar-request-upload-title">
                                                                <svg class="h-4 w-4 shrink-0 text-slate-500" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 4.5h6l3.5 3.5V15.5A1.5 1.5 0 0 1 14 17H6a1.5 1.5 0 0 1-1.5-1.5v-11A1 1 0 0 1 6 4.5Z" />
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5V8h3.5" />
                                                                </svg>
                                                                {{ $requirement['title'] }}
                                                                @if ($requirement['is_required'])
                                                                    <span class="ml-1 align-middle text-rose-500">*</span>
                                                                @endif
                                                            </p>
                                                            @if (! empty($requirement['note']))
                                                                <p class="silatar-request-upload-note">{{ $requirement['note'] }}</p>
                                                            @endif
                                                        </div>
                                                        <div class="flex shrink-0 flex-col items-end gap-1">
                                                            <span class="silatar-request-field-label">
                                                                {{ $controlLabel }}
                                                            </span>
                                                            @if ($requirement['is_required'])
                                                                <span class="inline-flex items-center gap-1 rounded-full bg-rose-50 px-2.5 py-1 text-[0.62rem] font-semibold uppercase tracking-[0.18em] text-rose-700">
                                                                    <svg class="h-3 w-3" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 4.5 4.5 7v4.2C4.5 14.9 7.2 17.2 10 18c2.8-.8 5.5-3.1 5.5-6.8V7L10 4.5Z" />
                                                                    </svg>
                                                                    Wajib
                                                                </span>
                                                            @endif
                                                            @if ($requirement['is_primary'])
                                                                <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 px-2.5 py-1 text-[0.62rem] font-semibold uppercase tracking-[0.18em] text-amber-700">
                                                                    <svg class="h-3 w-3" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m10 3 1.9 4 4.4.6-3.2 3.1.8 4.3L10 13l-4 2.1.8-4.3-3.2-3.1 4.4-.6L10 3Z" />
                                                                    </svg>
                                                                    Utama
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <button
                                                        type="button"
                                                        class="silatar-request-upload-figure w-full text-left"
                                                        :class="{ 'cursor-pointer': hasValue }"
                                                        :disabled="!hasValue"
                                                        @click.prevent.stop="$dispatch('request-preview-open', {
                                                            title: @js($requirement['title']),
                                                            kind: @js($type),
                                                            src: previewUrl || imageSrc
                                                        })"
                                                    >
                                                        <img
                                                            :src="imageSrc"
                                                            alt="File belum diupload"
                                                            :class="{ 'is-filled': hasValue }"
                                                        >
                                                        <p
                                                            class="silatar-request-field-value"
                                                            x-show="hasValue"
                                                            x-cloak
                                                            x-text="fileName"
                                                        ></p>
                                                        @if (! empty($existingFile['filename'] ?? null))
                                                            <p class="silatar-request-upload-note text-cyan-700">
                                                                File lama sudah terisi di form ini
                                                            </p>
                                                        @endif
                                                        <div
                                                            x-show="hasValue"
                                                            x-cloak
                                                            x-transition:enter="transition ease-out duration-300"
                                                            x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                                                            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                                            x-transition:leave="transition ease-in duration-200"
                                                            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                                            x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                                                            class="silatar-request-upload-terisi"
                                                        >
                                                            <svg class="h-3 w-3" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 13V4" />
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.5 7.5 10 4l3.5 3.5" />
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.5v2A1.5 1.5 0 0 0 6 16h8a1.5 1.5 0 0 0 1.5-1.5v-2" />
                                                            </svg>
                                                            Terisi
                                                        </div>
                                                    </button>

                                                    <div class="mt-3 border-t border-slate-200 pt-3">
                                                        <input
                                                            id="file-upload-{{ $requirement['id'] }}"
                                                            type="file"
                                                            name="files[{{ $requirement['id'] }}]"
                                                            accept="{{ $type === 'file' ? '.pdf,application/pdf' : 'image/*' }}"
                                                            class="sr-only"
                                                            @change="selectFile($event, {{ $requirement['id'] }}, {{ $requirement['is_required'] ? 'true' : 'false' }})"
                                                            @if ($requirement['is_required'] && ! $hasExistingUpload) required @endif
                                                        >
                                                        <label
                                                            for="file-upload-{{ $requirement['id'] }}"
                                                            class="silatar-request-upload-button"
                                                            :class="{ 'is-filled': hasValue }"
                                                        >
                                                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 13V4" />
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.5 7.5 10 4l3.5 3.5" />
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.5v2A1.5 1.5 0 0 0 6 16h8a1.5 1.5 0 0 0 1.5-1.5v-2" />
                                                            </svg>
                                                            Unggah {{ $type === 'file' ? 'PDF' : 'Gambar' }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="silatar-request-type-stack">
                                            @foreach ($typeRequirements as $requirement)
                                                @php
                                                    $type = $requirement['type_normalized'] ?? strtolower((string) ($requirement['type'] ?? 'input'));
                                                    $controlLabel = $requirement['type_label'] ?? ucfirst($type);
                                                    $initialValue = old('values.' . $requirement['id'], $existingAnswers[$requirement['id']] ?? '');
                                                    $options = $requirement['options'] ?? [];
                                                @endphp
                                                <div
                                                    x-data="requestRequirementCard({
                                                        kind: @js($type),
                                                        initialValue: @js($initialValue),
                                                    })"
                                                    x-init="initRequirement({{ $requirement['id'] }}, {{ $requirement['is_required'] ? 'true' : 'false' }})"
                                                    :class="{ 'is-filled': hasValue, 'is-just-uploaded': justUpdated }"
                                                    class="silatar-request-input-card"
                                                >
                                                    <div class="flex items-start justify-between gap-3">
                                                        <div class="min-w-0">
                                                            <p class="silatar-request-upload-title">
                                                                <svg class="h-4 w-4 shrink-0 text-slate-500" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 4.5h10A1.5 1.5 0 0 1 16.5 6v8A1.5 1.5 0 0 1 15 15.5H5A1.5 1.5 0 0 1 3.5 14V6A1.5 1.5 0 0 1 5 4.5Z" />
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 8h6M7 11h5" />
                                                                </svg>
                                                                {{ $requirement['title'] }}
                                                                @if ($requirement['is_required'])
                                                                    <span class="ml-1 align-middle text-rose-500">*</span>
                                                                @endif
                                                            </p>
                                                            @if (! empty($requirement['note']))
                                                                <p class="silatar-request-upload-note">{{ $requirement['note'] }}</p>
                                                            @endif
                                                        </div>
                                                        <div class="flex shrink-0 flex-col items-end gap-1">
                                                            <span class="silatar-request-field-label">
                                                                {{ $controlLabel }}
                                                            </span>
                                                            @if ($requirement['is_required'])
                                                                <span class="inline-flex items-center gap-1 rounded-full bg-rose-50 px-2.5 py-1 text-[0.62rem] font-semibold uppercase tracking-[0.18em] text-rose-700">
                                                                    <svg class="h-3 w-3" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 4.5 4.5 7v4.2C4.5 14.9 7.2 17.2 10 18c2.8-.8 5.5-3.1 5.5-6.8V7L10 4.5Z" />
                                                                    </svg>
                                                                    Wajib
                                                                </span>
                                                            @endif
                                                            @if ($requirement['is_primary'])
                                                                <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 px-2.5 py-1 text-[0.62rem] font-semibold uppercase tracking-[0.18em] text-amber-700">
                                                                    <svg class="h-3 w-3" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m10 3 1.9 4 4.4.6-3.2 3.1.8 4.3L10 13l-4 2.1.8-4.3-3.2-3.1 4.4-.6L10 3Z" />
                                                                    </svg>
                                                                    Utama
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="silatar-request-field-frame silatar-request-field-frame-compact">
                                                        @if ($type === 'date' || $type === 'datetime')
                                                            <input
                                                                type="{{ $requirement['input_type'] }}"
                                                                name="values[{{ $requirement['id'] }}]"
                                                                class="silatar-request-field-input"
                                                                x-model="value"
                                                                @input="syncText($event, {{ $requirement['id'] }}, {{ $requirement['is_required'] ? 'true' : 'false' }})"
                                                                @change="syncText($event, {{ $requirement['id'] }}, {{ $requirement['is_required'] ? 'true' : 'false' }})"
                                                                @if ($requirement['is_required']) required @endif
                                                            >
                                                        @elseif ($type === 'textarea')
                                                            <textarea
                                                                name="values[{{ $requirement['id'] }}]"
                                                                rows="5"
                                                                class="silatar-request-field-input silatar-request-field-textarea"
                                                                placeholder="{{ $requirement['note'] ?: 'Isi keterangan sesuai syarat' }}"
                                                                x-model="value"
                                                                @input="syncText($event, {{ $requirement['id'] }}, {{ $requirement['is_required'] ? 'true' : 'false' }})"
                                                                @if ($requirement['is_required']) required @endif
                                                            >{{ $initialValue }}</textarea>
                                                        @elseif ($type === 'option')
                                                            <select
                                                                name="values[{{ $requirement['id'] }}]"
                                                                class="silatar-request-field-input silatar-request-field-select"
                                                                x-model="value"
                                                                @change="syncText($event, {{ $requirement['id'] }}, {{ $requirement['is_required'] ? 'true' : 'false' }})"
                                                                @if ($requirement['is_required']) required @endif
                                                            >
                                                                <option value="" disabled hidden @selected(empty($initialValue))>
                                                                    Pilih salah satu
                                                                </option>
                                                                @foreach ($options as $option)
                                                                    <option value="{{ $option }}" @selected($initialValue === $option)>{{ $option }}</option>
                                                                @endforeach
                                                            </select>
                                                        @else
                                                            <input
                                                                type="{{ $requirement['input_type'] }}"
                                                                name="values[{{ $requirement['id'] }}]"
                                                                class="silatar-request-field-input"
                                                                placeholder="{{ $requirement['note'] ?: 'Isi data sesuai syarat' }}"
                                                                x-model="value"
                                                                @input="syncText($event, {{ $requirement['id'] }}, {{ $requirement['is_required'] ? 'true' : 'false' }})"
                                                                @change="syncText($event, {{ $requirement['id'] }}, {{ $requirement['is_required'] ? 'true' : 'false' }})"
                                                                @if ($requirement['is_required']) required @endif
                                                            >
                                                        @endif

                                                        <div
                                                            x-show="hasValue"
                                                            x-cloak
                                                            x-transition:enter="transition ease-out duration-300"
                                                            x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                                                            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                                            x-transition:leave="transition ease-in duration-200"
                                                            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                                            x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                                                            class="silatar-request-field-filled"
                                                        >
                                                            <svg class="h-3 w-3" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 13V4" />
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.5 7.5 10 4l3.5 3.5" />
                                                            </svg>
                                                            Terisi
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </section>
                            @endif
                        @endforeach
                    </div>

                    <label class="mt-6 block">
                        <span class="mb-2 block text-sm font-medium text-slate-700">Deskripsi / Keterangan</span>
                        <textarea
                            name="deskripsi"
                            rows="5"
                            class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-cyan-300 focus:ring-2 focus:ring-cyan-100"
                            placeholder="Ceritakan kebutuhan atau keterangan singkat dari request ini"
                        >{{ old('deskripsi', $requestDescription ?? '') }}</textarea>
                    </label>

                    <div class="silatar-request-action-row">
                        <button
                            type="submit"
                            name="submit_action"
                            value="draft"
                            formnovalidate
                            class="silatar-request-action-button silatar-request-action-draft"
                        >
                            <svg class="mr-2 h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 6.5A2 2 0 0 1 6.5 4.5h7A2 2 0 0 1 15.5 6.5v7A2 2 0 0 1 13.5 15.5h-7a2 2 0 0 1-2-2z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 8h6" />
                            </svg>
                            Simpan ke Draft
                        </button>
                        <button
                            type="submit"
                            name="submit_action"
                            value="final"
                            class="silatar-request-action-button silatar-request-action-primary"
                            :disabled="!canSubmit"
                            :title="canSubmit ? 'Siap dikirim' : 'Lengkapi semua syarat wajib terlebih dahulu'"
                        >
                            <svg class="mr-2 h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 13V4" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.5 7.5 10 4l3.5 3.5" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.5v2A1.5 1.5 0 0 0 6 16h8a1.5 1.5 0 0 0 1.5-1.5v-2" />
                            </svg>
                            Kirim Pengajuan
                        </button>
                        <a href="{{ route('pelayanan') }}" class="silatar-request-action-button silatar-request-action-secondary">
                            <svg class="mr-2 h-4 w-4 text-cyan-600" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12.5 5 8 9.5l4.5 4.5" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.5 9.5H16" />
                            </svg>
                            Batal
                        </a>
                    </div>
                </form>

                <div
                    x-cloak
                    x-show="previewOpen"
                    x-transition.opacity
                    class="fixed inset-0 z-[80] flex items-center justify-center bg-slate-950/65 p-4 backdrop-blur-sm"
                    @click.self="closeRequirementPreview()"
                    @keydown.escape.window="previewOpen && closeRequirementPreview()"
                >
                    <div class="w-full max-w-3xl overflow-hidden rounded-[2rem] bg-white shadow-[0_40px_120px_rgba(15,23,42,0.35)] ring-1 ring-black/10">
                        <div class="flex items-start justify-between gap-4 border-b border-slate-200 bg-gradient-to-r from-cyan-50 via-white to-cyan-50 px-5 py-4">
                            <div class="min-w-0">
                                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-cyan-700">Preview File</p>
                                <h4 class="mt-1 truncate text-lg font-semibold text-slate-950" x-text="previewTitle"></h4>
                                <p class="mt-1 text-sm text-slate-500">
                                    Klik area luar modal atau tombol tutup untuk kembali ke form.
                                </p>
                            </div>
                            <button
                                type="button"
                                class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-500 shadow-sm transition hover:border-cyan-300 hover:text-cyan-700"
                                @click="closeRequirementPreview()"
                            >
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6l8 8M14 6l-8 8" />
                                </svg>
                            </button>
                        </div>

                        <div class="bg-slate-100 p-4 sm:p-6">
                            <template x-if="previewKind === 'image'">
                                <div class="overflow-hidden rounded-[1.5rem] bg-white p-3 shadow-sm ring-1 ring-slate-200">
                                    <img :src="previewSrc" alt="Preview file" class="max-h-[70vh] w-full rounded-[1.1rem] object-contain">
                                </div>
                            </template>
                            <template x-if="previewKind !== 'image'">
                                <div class="overflow-hidden rounded-[1.5rem] bg-white shadow-sm ring-1 ring-slate-200">
                                    <iframe
                                        :src="previewSrc"
                                        class="h-[70vh] w-full"
                                        title="Preview file"
                                    ></iframe>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
</x-layouts.app>
