@php
    $kepala = $pimpinan['kepala'];
    $pltPlh = $pimpinan['plt_plh'];
    $kepalaCount = $kepala->count();
    $pltCount = $pltPlh->count();
@endphp

<div class="card mb-6">
    <div class="card-header">
        <div class="flex items-center gap-3">
            <div class="stat-icon rose" style="width: 36px; height: 36px;">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6 0a4 4 0 016 0m-6 0a4 4 0 01-6 0m6 0v-2a4 4 0 00-4-4h-2a4 4 0 00-4 4v2m8 0a4 4 0 014 4v2"/>
                </svg>
            </div>
            <div>
                <h3 class="card-title">Pimpinan Unit Kerja</h3>
                <p class="text-sm text-muted">Tetapkan kepala unit kerja dan PLH/PLT kepala</p>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="grid gap-6 lg:grid-cols-2">
            {{-- Kepala --}}
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <label class="form-label" style="margin: 0;">Kepala</label>
                    @if ($kepalaCount > 1)
                        <span class="badge badge-warning" title="{{ $kepalaCount }} kepala terdaftar di unit ini">Multi ({{ $kepalaCount }})</span>
                    @elseif ($kepalaCount === 0)
                        <span class="badge badge-danger">Belum ada</span>
                    @endif
                </div>

                <p class="text-sm text-muted mb-3">
                    Kepala unit kerja adalah pegawai unit ini dengan <strong>kat_jabatan = kepala</strong> dan <strong>status = 1</strong>.
                    Menandai pegawai akan mengubah <strong>kat_jabatan</strong> menjadi <strong>kepala</strong> dan <strong>status</strong> menjadi <strong>1</strong>;
                    melepas penandaan hanya mengubah <strong>kat_jabatan</strong> menjadi <strong>ex-kepala</strong> tanpa mengubah status.
                </p>

                <form method="POST" action="{{ route('admin.units.kepala.update', $department->id) }}">
                    @csrf
                    @method('PUT')

                    <p class="text-sm font-medium mb-2">Kepala saat ini</p>
                    <div id="kepala-list" class="space-y-2 mb-4">
                        @forelse ($kepala as $k)
                            <label class="flex items-center justify-between gap-3 p-3 rounded-lg cursor-pointer" style="background: var(--primary-50);">
                                <span class="flex items-center gap-3">
                                    <input type="checkbox" name="kepala_ids[]" value="{{ $k->id }}" checked>
                                    <span>
                                        <span class="text-sm font-medium block">{{ $k->name }}</span>
                                        <span class="text-sm text-muted">
                                            {{ $k->role ?? '-' }}
                                            @if (! empty($k->nomor_induk))
                                                &middot; NIP/NUPTK {{ $k->nomor_induk }}
                                            @endif
                                        </span>
                                    </span>
                                </span>
                                <span class="badge badge-success">Kepala</span>
                            </label>
                        @empty
                            <p class="text-sm text-muted" data-kepala-kosong>Belum ada kepala yang ditetapkan untuk unit ini.</p>
                        @endforelse
                    </div>

                    <div class="combobox" data-combobox>
                        <div class="combobox-field">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" class="form-input pl-10" data-combobox-input autocomplete="off"
                                   placeholder="Ketik nama pegawai unit ini untuk ditambahkan...">
                            <input type="hidden" data-combobox-value>
                            <ul class="combobox-menu" data-combobox-menu hidden></ul>
                        </div>
                        <script type="application/json" data-combobox-pool>@json($kandidatKepala)</script>
                        <p class="text-sm mt-1" style="color: var(--danger);" data-combobox-error hidden></p>
                        <button type="button" class="btn btn-secondary mt-2" data-combobox-add disabled>
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                            </svg>
                            Tambahkan sebagai Kepala
                        </button>
                    </div>

                    <button type="submit" class="btn btn-primary mt-4">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Kepala
                    </button>
                </form>
            </div>

            {{-- PLH/PLT Kepala --}}
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <label class="form-label" style="margin: 0;">PLH/PLT Kepala</label>
                    @if ($pltCount > 1)
                        <span class="badge badge-warning" title="{{ $pltCount }} PLH/PLT terdaftar di unit ini">Multi ({{ $pltCount }})</span>
                    @endif
                </div>

                <p class="text-sm text-muted mb-3">
                    Pegawai yang ditunjuk sebagai PLH/PLT kepala unit ini (tabel <strong>plt_plh</strong>).
                    Daftar pilihan hanya berisi pegawai dengan <strong>status = 1</strong>.
                </p>

                @if ($pltCount > 0)
                    <div class="space-y-2 mb-4">
                        @foreach ($pltPlh as $p)
                            <div class="flex items-center justify-between gap-3 p-3 rounded-lg" style="background: var(--primary-50);">
                                <div>
                                    <p class="text-sm font-medium">{{ $p->name }}</p>
                                    <p class="text-sm text-muted">
                                        {{ $p->role ?? '-' }}
                                        @if (! empty($p->nomor_induk))
                                            &middot; NIP/NUPTK {{ $p->nomor_induk }}
                                        @endif
                                    </p>
                                </div>
                                <form method="POST" action="{{ route('admin.units.plt-plh.destroy', [$department->id, $p->plt_plh_id]) }}" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn delete" title="Hapus PLH/PLT ini">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a2 2 0 00-1-1h-4a2 2 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-muted mb-4">Belum ada PLH/PLT kepala yang ditugaskan ke unit ini.</p>
                @endif

                <form method="POST" action="{{ route('admin.units.plt-plh.store', $department->id) }}">
                    @csrf

                    <div class="combobox" data-combobox>
                        <div class="combobox-field">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" class="form-input pl-10" data-combobox-input autocomplete="off"
                                   placeholder="Ketik nama pegawai status 1 untuk ditunjuk...">
                            <input type="hidden" name="user_id" data-combobox-value>
                            <ul class="combobox-menu" data-combobox-menu hidden></ul>
                        </div>
                        <script type="application/json" data-combobox-pool>@json($kandidatPlt)</script>
                        <p class="text-sm mt-1" style="color: var(--danger);" data-combobox-error hidden></p>
                        <button type="submit" class="btn btn-secondary mt-2" data-combobox-submit disabled>
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                            </svg>
                            Tetapkan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function () {
    function buildKepalaRow(item) {
        var label = document.createElement('label');
        label.className = 'flex items-center justify-between gap-3 p-3 rounded-lg cursor-pointer';
        label.style.background = 'var(--primary-50)';

        var left = document.createElement('span');
        left.className = 'flex items-center gap-3';

        var checkbox = document.createElement('input');
        checkbox.type = 'checkbox';
        checkbox.name = 'kepala_ids[]';
        checkbox.value = item.id;
        checkbox.checked = true;

        var text = document.createElement('span');
        var name = document.createElement('span');
        name.className = 'text-sm font-medium block';
        name.textContent = item.name;
        var meta = document.createElement('span');
        meta.className = 'text-sm text-muted';
        meta.textContent = item.meta || '';
        text.appendChild(name);
        text.appendChild(meta);

        left.appendChild(checkbox);
        left.appendChild(text);

        var right = document.createElement('span');
        right.className = 'flex items-center gap-2';

        var badge = document.createElement('span');
        badge.className = 'badge badge-success';
        badge.textContent = 'Kepala';
        right.appendChild(badge);

        var remove = document.createElement('button');
        remove.type = 'button';
        remove.className = 'action-btn delete';
        remove.title = 'Batalkan penambahan';
        remove.innerHTML = '<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>';
        remove.addEventListener('click', function (event) {
            event.preventDefault();
            label.remove();
        });
        right.appendChild(remove);

        label.appendChild(left);
        label.appendChild(right);

        return label;
    }

    document.querySelectorAll('[data-combobox]').forEach(function (box) {
        var input = box.querySelector('[data-combobox-input]');
        var menu = box.querySelector('[data-combobox-menu]');
        var value = box.querySelector('[data-combobox-value]');
        var error = box.querySelector('[data-combobox-error]');
        var poolScript = box.querySelector('[data-combobox-pool]');
        var addButton = box.querySelector('[data-combobox-add]');
        var submitButton = box.querySelector('[data-combobox-submit]');
        var pool = poolScript ? JSON.parse(poolScript.textContent) : [];
        var selected = null;

        function buttons() {
            return [addButton, submitButton].filter(function (button) {
                return button !== null;
            });
        }

        function refreshButtons() {
            buttons().forEach(function (button) {
                button.disabled = !selected;
            });
        }

        function clearSelection() {
            selected = null;
            value.value = '';
            refreshButtons();
        }

        function pickedIds() {
            var form = box.closest('form');
            var picked = {};
            if (!form) {
                return picked;
            }
            form.querySelectorAll('input[name="kepala_ids[]"]:checked').forEach(function (element) {
                picked[element.value] = true;
            });
            return picked;
        }

        function render(matches) {
            menu.innerHTML = '';

            if (!matches.length) {
                var empty = document.createElement('li');
                empty.className = 'combobox-empty';
                empty.textContent = 'Nama tidak ditemukan';
                menu.appendChild(empty);
            }

            matches.forEach(function (item) {
                var itemWrapper = document.createElement('li');
                var option = document.createElement('button');
                option.type = 'button';
                option.className = 'combobox-option';

                var optionName = document.createElement('span');
                optionName.className = 'combobox-option-name';
                optionName.textContent = item.name;
                option.appendChild(optionName);

                if (item.meta) {
                    var optionMeta = document.createElement('span');
                    optionMeta.className = 'combobox-option-meta';
                    optionMeta.textContent = item.meta;
                    option.appendChild(optionMeta);
                }

                option.addEventListener('click', function () {
                    selected = item;
                    input.value = item.name;
                    value.value = item.id;
                    menu.innerHTML = '';
                    menu.hidden = true;
                    error.hidden = true;
                    error.textContent = '';
                    refreshButtons();
                });

                itemWrapper.appendChild(option);
                menu.appendChild(itemWrapper);
            });

            menu.hidden = false;
        }

        input.addEventListener('input', function () {
            clearSelection();

            var term = input.value.trim().toLowerCase();
            if (!term) {
                menu.innerHTML = '';
                menu.hidden = true;
                return;
            }

            var picked = pickedIds();
            var matches = pool.filter(function (item) {
                if (picked[item.id]) {
                    return false;
                }
                return item.name.toLowerCase().indexOf(term) !== -1
                    || (item.meta || '').toLowerCase().indexOf(term) !== -1;
            }).slice(0, 20);

            render(matches);
        });

        input.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                menu.hidden = true;
            }
            if (event.key === 'Enter') {
                event.preventDefault();
                if (selected) {
                    buttons().forEach(function (button) {
                        button.click();
                    });
                } else {
                    error.textContent = 'Pilih nama dari daftar yang muncul.';
                    error.hidden = false;
                }
            }
        });

        document.addEventListener('click', function (event) {
            if (!box.contains(event.target)) {
                menu.hidden = true;
            }
        });

        if (addButton) {
            addButton.addEventListener('click', function () {
                if (!selected) {
                    error.textContent = 'Pilih nama dari daftar yang muncul.';
                    error.hidden = false;
                    return;
                }

                var list = document.getElementById('kepala-list');
                var kosong = list.querySelector('[data-kepala-kosong]');
                if (kosong) {
                    kosong.remove();
                }
                list.appendChild(buildKepalaRow(selected));

                input.value = '';
                clearSelection();
                error.hidden = true;
                error.textContent = '';
            });
        }

        refreshButtons();
    });
})();
</script>
@endpush
