<x-layouts.app title="Pengajuan Saya - SILATAR">
    <main class="mx-auto max-w-7xl px-6 py-8 lg:px-8 lg:py-10">
        <x-ui.page-hero badge="Riwayat Pengajuan" title="Pengajuan Saya">
            <p class="max-w-3xl text-sm leading-7 text-slate-600 sm:text-base">
                Halaman ini menampilkan semua layanan yang pernah Anda ajukan, termasuk draft dan pengajuan final.
            </p>
            <div class="mt-6 flex flex-wrap justify-center gap-3">
                <a href="{{ route('pelayanan') }}" class="rounded-full bg-cyan-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-cyan-700">
                    Ajukan layanan baru
                </a>
                <a href="{{ url('/') }}" class="rounded-full border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-cyan-200 hover:text-slate-900">
                    Kembali ke beranda
                </a>
            </div>
        </x-ui.page-hero>

        @if (session('success'))
            <div class="mb-6 rounded-[1.5rem] border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm leading-6 text-emerald-800 shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-[1.75rem] border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">Total Pengajuan</p>
                <p class="mt-2 text-3xl font-semibold text-slate-900">{{ $summary['total'] }}</p>
            </div>
            <div class="rounded-[1.75rem] border border-amber-200 bg-amber-50 p-5 shadow-sm">
                <p class="text-sm text-amber-700">Draft</p>
                <p class="mt-2 text-3xl font-semibold text-amber-900">{{ $summary['draft'] }}</p>
            </div>
            <div class="rounded-[1.75rem] border border-cyan-200 bg-cyan-50 p-5 shadow-sm">
                <p class="text-sm text-cyan-700">Menunggu / Diproses</p>
                <p class="mt-2 text-3xl font-semibold text-cyan-900">{{ $summary['pending'] + $summary['processed'] }}</p>
            </div>
            <div class="rounded-[1.75rem] border border-emerald-200 bg-emerald-50 p-5 shadow-sm">
                <p class="text-sm text-emerald-700">Selesai / Ditutup</p>
                <p class="mt-2 text-3xl font-semibold text-emerald-900">{{ $summary['done'] }}</p>
            </div>
        </section>

        <section class="mt-8">
            <div class="mb-4 flex items-end justify-between gap-4">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-cyan-700">Daftar layanan</p>
                    <h2 class="mt-2 text-2xl font-semibold text-slate-900">Semua pengajuan Anda</h2>
                </div>
                <p class="hidden max-w-lg text-sm leading-6 text-slate-500 md:block">
                    Setiap kartu memperlihatkan nomor request, status, ringkasan layanan, dan jumlah lampiran yang sudah tersimpan.
                </p>
            </div>

            @if ($requests->count() === 0)
                <div class="rounded-[2rem] border border-dashed border-slate-200 bg-white p-10 text-center shadow-sm">
                    <div class="mx-auto grid h-16 w-16 place-items-center rounded-2xl bg-cyan-50 text-cyan-700">
                        <svg class="h-8 w-8" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 4.5h11v11h-11z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 8.5h6M7 11.5h4" />
                        </svg>
                    </div>
                    <h3 class="mt-5 text-lg font-semibold text-slate-900">Belum ada pengajuan</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        Saat Anda menyimpan draft atau mengirim layanan, datanya akan tampil di sini.
                    </p>
                    <a href="{{ route('pelayanan') }}" class="mt-6 inline-flex items-center gap-2 rounded-full bg-cyan-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-cyan-700">
                        Mulai pengajuan
                    </a>
                </div>
            @else
                <div class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-sm">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50">
                                <tr class="text-left text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">
                                    <th class="px-5 py-4">No Req</th>
                                    <th class="px-5 py-4">Layanan</th>
                                    <th class="px-5 py-4">Status</th>
                                    <th class="px-5 py-4">Lampiran</th>
                                    <th class="px-5 py-4">Dibuat</th>
                                    <th class="px-5 py-4">Update</th>
                                    <th class="px-5 py-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @foreach ($requests as $request)
                                    @php
                                        $statusMeta = match ($request->status) {
                                            'DRAFT' => ['label' => 'Draft', 'class' => 'border-amber-200 bg-amber-50 text-amber-700'],
                                            'UNCHECK' => ['label' => 'Menunggu', 'class' => 'border-cyan-200 bg-cyan-50 text-cyan-700'],
                                            'PENDING' => ['label' => 'Pending', 'class' => 'border-slate-200 bg-slate-50 text-slate-700'],
                                            'DITERIMA' => ['label' => 'Diterima', 'class' => 'border-emerald-200 bg-emerald-50 text-emerald-700'],
                                            'DIPROSES' => ['label' => 'Diproses', 'class' => 'border-blue-200 bg-blue-50 text-blue-700'],
                                            'SUKSES' => ['label' => 'Sukses', 'class' => 'border-emerald-200 bg-emerald-50 text-emerald-700'],
                                            'DITOLAK' => ['label' => 'Ditolak', 'class' => 'border-rose-200 bg-rose-50 text-rose-700'],
                                            'BATAL' => ['label' => 'Batal', 'class' => 'border-slate-200 bg-slate-100 text-slate-600'],
                                            default => ['label' => $request->status, 'class' => 'border-slate-200 bg-slate-50 text-slate-700'],
                                        };
                                        $createdAt = \Illuminate\Support\Carbon::parse($request->created_at)->format('d M Y, H:i');
                                        $updatedAt = \Illuminate\Support\Carbon::parse($request->updated_at)->format('d M Y, H:i');
                                        $description = $request->deskripsi ?: $request->layanan_description;
                                        $editMeta = $request->status === 'DRAFT'
                                            ? ['class' => 'border-amber-200 bg-amber-50 text-amber-800 hover:border-amber-300 hover:bg-amber-100 hover:text-amber-900', 'icon' => 'amber']
                                            : ['class' => 'border-cyan-200 bg-cyan-50 text-cyan-800 hover:border-cyan-300 hover:bg-cyan-100 hover:text-cyan-900', 'icon' => 'cyan'];
                                    @endphp

                                    <tr class="align-top transition hover:bg-slate-50/70">
                                        <td class="px-5 py-4">
                                            <div class="text-sm font-semibold text-cyan-700">{{ $request->no_req }}</div>
                                            <div class="mt-1 text-xs text-slate-500">{{ $request->kategori }}</div>
                                        </td>
                                        <td class="px-5 py-4">
                                            <div class="font-semibold text-slate-950">{{ $request->layanan_name }}</div>
                                            <p class="mt-1 max-w-xl text-sm leading-6 text-slate-600" style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                                                {{ $description }}
                                            </p>
                                        </td>
                                        <td class="px-5 py-4">
                                            <span class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold uppercase tracking-[0.14em] {{ $statusMeta['class'] }}">
                                                {{ $statusMeta['label'] }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-4 text-sm font-medium text-slate-900">
                                            {{ (int) $request->file_count }} file
                                        </td>
                                        <td class="px-5 py-4 text-sm text-slate-700">
                                            {{ $createdAt }}
                                        </td>
                                        <td class="px-5 py-4 text-sm text-slate-700">
                                            {{ $updatedAt }}
                                        </td>
                                        <td class="px-5 py-4">
                                            <a href="{{ route('pengajuan-saya.edit', $request->id) }}" class="inline-flex items-center gap-2 rounded-full border px-4 py-2 text-sm font-semibold transition hover:-translate-y-0.5 {{ $editMeta['class'] }}">
                                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 13.5 13 5l2.5 2.5-8.5 8.5H4.5v-2.5z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6l2 2" />
                                                </svg>
                                                Edit
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <x-ui.pagination-shell :paginator="$requests" class="mt-8" />
            @endif
        </section>
    </main>
</x-layouts.app>
