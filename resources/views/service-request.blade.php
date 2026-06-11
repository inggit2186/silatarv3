<x-layouts.app :title="'Ajukan Janji Temu - ' . ($appointmentData['employee_name'] ?? $service['title']) . ' - SILATAR'">
    @php
        $isEditing = (bool) ($editing ?? false);
        $isAppointment = ! empty($appointmentData);
        $formAction = $formAction ?? route('pelayanan.request.submit', $service['id']);
        $backUrl = $backUrl ?? route('pelayanan');
    @endphp

    <main class="relative cyber-bg">
        <div class="mx-auto max-w-3xl px-6 py-8 lg:px-8">
            {{-- Banner --}}
            <div class="cyber-card mb-6">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center gap-4">
                        <div class="cyber-sidebar-cover flex-shrink-0">
                            <img src="{{ $service['cover_path'] }}" alt="{{ $service['title'] }}" class="cyber-sidebar-cover-img">
                        </div>
                        <div>
                            <p class="cyber-section-step">Janji Temu</p>
                            <h1 class="cyber-modal-title">{{ $service['title'] }}</h1>
                            <p class="cyber-text-subtle text-sm mt-1">
                                {{ $service['unit_name'] }} &bull; {{ $service['waktu'] }} &bull; {{ $service['biaya'] }}
                            </p>
                        </div>
                    </div>
                    <a href="{{ $backUrl }}" class="cyber-btn-secondary-sm flex-shrink-0">
                        Kembali
                    </a>
                </div>
            </div>

            {{-- Appointment Form --}}
            <div class="cyber-card">
                <div class="cyber-card-header">
                    <div class="flex items-center gap-3">
                        <div class="cyber-icon">
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8l10-5 4 10H3m18 0l-5-5M3 12l5 5m-5-5l5 5" />
                            </svg>
                        </div>
                        <div>
                            <p class="cyber-step-label">Form Janji Temu</p>
                            <p class="cyber-text-subtle text-sm">Lengkapi data untuk membuat janji temu</p>
                        </div>
                    </div>
                </div>

                <form action="{{ $formAction }}" method="POST" class="space-y-6 p-6">
                    @csrf

                    @if ($isAppointment && $appointmentData)
                        {{-- Tujuan (Read Only) --}}
                        <div class="cyber-appointment-target">
                            <label class="cyber-form-label">Tujuan</label>
                            <div class="cyber-appointment-target-card">
                                <div class="cyber-appointment-target-photo">
                                    @if (! empty($appointmentData['employee_photo']) && str_starts_with($appointmentData['employee_photo'], 'http'))
                                        <img src="{{ $appointmentData['employee_photo'] }}" alt="{{ $appointmentData['employee_name'] }}" class="cyber-appointment-target-img">
                                    @else
                                        <span class="cyber-appointment-target-initials">
                                            {{ $appointmentData['type'] === 'direct' ? 'S' : substr($appointmentData['employee_name'], 0, 2) }}
                                        </span>
                                    @endif
                                </div>
                                <div class="cyber-appointment-target-info">
                                    <p class="cyber-appointment-target-name">{{ $appointmentData['employee_name'] }}</p>
                                    <p class="cyber-appointment-target-role">{{ $appointmentData['employee_role'] }}</p>
                                </div>
                                @if ($appointmentData['type'] === 'direct')
                                    <input type="hidden" name="appointment_type" value="direct">
                                    <input type="hidden" name="dept_id" value="{{ $service['dept_id'] ?? '' }}">
                                @else
                                    <input type="hidden" name="appointment_type" value="employee">
                                    <input type="hidden" name="employee_id" value="{{ $appointmentData['employee_id'] }}">
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- Tanggal Janji Temu --}}
                    <div class="cyber-form-group">
                        <label for="appointment_date" class="cyber-form-label">Tanggal Janji Temu</label>
                        <input
                            type="date"
                            name="appointment_date"
                            id="appointment_date"
                            class="cyber-form-input"
                            required
                            min="{{ now()->toDateString() }}"
                            value="{{ old('appointment_date') }}"
                        >
                        @error('appointment_date')
                            <p class="cyber-form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Jam Janji Temu --}}
                    <div class="cyber-form-group">
                        <label for="appointment_time" class="cyber-form-label">Jam Janji Temu</label>
                        <select name="appointment_time" id="appointment_time" class="cyber-form-select" required>
                            <option value="">-- Pilih Jam --</option>
                            @foreach (['08:00', '08:30', '09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '13:00', '13:30', '14:00', '14:30', '15:00', '15:30', '16:00'] as $time)
                                <option value="{{ $time }}" {{ old('appointment_time') === $time ? 'selected' : '' }}>
                                    {{ $time }} WIB
                                </option>
                            @endforeach
                        </select>
                        @error('appointment_time')
                            <p class="cyber-form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Keterangan --}}
                    <div class="cyber-form-group">
                        <label for="appointment_note" class="cyber-form-label">
                            Keterangan / Alasan Bertemu
                        </label>
                        <textarea
                            name="appointment_note"
                            id="appointment_note"
                            class="cyber-form-textarea"
                            rows="4"
                            placeholder="Jelaskan alasan/keperluan Anda ingin bertemu..."
                            required
                        >{{ old('appointment_note') }}</textarea>
                        @error('appointment_note')
                            <p class="cyber-form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Submit Button --}}
                    <div class="flex justify-end gap-4">
                        <a href="{{ $backUrl }}" class="cyber-btn-secondary">
                            Batal
                        </a>
                        <button type="submit" class="cyber-btn">
                            Ajukan Janji Temu
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</x-layouts.app>