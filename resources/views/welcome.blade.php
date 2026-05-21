<x-layouts.app title="SILATAR">
    <main class="relative">
        <section id="home" class="mx-auto max-w-7xl px-6 pb-14 pt-8 lg:px-8 lg:pb-20 lg:pt-10">
            <div class="grid items-center gap-10 lg:grid-cols-[1.05fr_.95fr]">
                <div class="space-y-6">
                    <div class="inline-flex items-center gap-2 rounded-full border border-cyan-200 bg-cyan-50 px-4 py-2 text-sm font-medium text-cyan-700 shadow-sm">
                        <span class="h-2 w-2 rounded-full bg-cyan-500"></span>
                        Landing page resmi layanan
                    </div>

                    <div class="space-y-4">
                        <h1 class="max-w-3xl text-4xl font-semibold tracking-tight text-slate-900 sm:text-5xl lg:text-6xl">
                            Layanan cepat, transparan, dan mudah diakses dalam satu halaman.
                        </h1>
                        <p class="max-w-2xl text-base leading-7 text-slate-600 sm:text-lg">
                            Portal ini dirancang untuk memudahkan masyarakat melihat informasi layanan, memahami standar
                            pelayanan, membaca testimoni, dan melihat dokumentasi kegiatan dalam tampilan yang bersih.
                        </p>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('pelayanan') }}" class="rounded-full bg-cyan-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-cyan-200 transition hover:bg-cyan-700">
                            Lihat layanan
                        </a>
                        <a href="#maklumat" class="rounded-full border border-slate-200 bg-white px-6 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-cyan-200 hover:text-slate-900">
                            Maklumat pelayanan
                        </a>
                    </div>

                    <dl class="grid gap-4 sm:grid-cols-3">
                        <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                            <dt class="text-sm text-slate-500">Akses</dt>
                            <dd class="mt-2 text-lg font-semibold text-slate-900">Cepat dan jelas</dd>
                        </div>
                        <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                            <dt class="text-sm text-slate-500">Informasi</dt>
                            <dd class="mt-2 text-lg font-semibold text-slate-900">Tersusun rapi</dd>
                        </div>
                        <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                            <dt class="text-sm text-slate-500">Tampilan</dt>
                            <dd class="mt-2 text-lg font-semibold text-slate-900">Terang dan bersih</dd>
                        </div>
                    </dl>
                </div>

                <div class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-xl shadow-slate-200/60">
                    <div class="overflow-hidden rounded-[1.5rem] border border-slate-200 bg-[radial-gradient(circle_at_top,_rgba(14,165,233,0.15),_transparent_30%),linear-gradient(180deg,_#f8fbff_0%,_#e0f2fe_100%)] p-7">
                        <p class="text-sm font-semibold uppercase tracking-[0.28em] text-cyan-700">Cover</p>
                        <div class="mt-6 grid gap-4">
                            <div class="rounded-3xl border border-white/70 bg-white/85 p-5 shadow-sm backdrop-blur">
                                <p class="text-sm text-slate-500">Title aplikasi</p>
                                <h2 class="mt-2 text-2xl font-semibold text-slate-900">SILATAR</h2>
                            </div>
                            <div class="rounded-3xl border border-white/70 bg-white/85 p-5 shadow-sm backdrop-blur">
                                <p class="text-sm text-slate-500">Kepanjangan nama</p>
                                <p class="mt-2 text-sm leading-6 text-slate-600">
                                    Sistem Informasi, Layanan, dan Administrasi Kankemenag Tanah Datar.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="satuan-kerja" class="mx-auto max-w-7xl px-6 py-10 lg:px-8">
            <div class="flex items-end justify-between gap-4">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-cyan-700">Satuan Kerja</p>
                    <h2 class="mt-2 text-3xl font-semibold text-slate-900">Unit kerja layanan</h2>
                </div>
                <p class="hidden max-w-xl text-sm leading-6 text-slate-500 md:block">
                    Gambaran singkat satuan kerja yang terlibat dalam penyediaan layanan dan pendampingan informasi.
                </p>
            </div>

            <div class="mt-8 grid gap-5 md:grid-cols-2 xl:grid-cols-4">
                <article class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="grid h-12 w-12 place-items-center rounded-2xl bg-cyan-100 text-cyan-700">
                        <svg class="h-6 w-6" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.7">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16V6h12v10" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 6V4h6v2" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 10h6M7 13h4" />
                        </svg>
                    </div>
                    <h3 class="mt-4 text-lg font-semibold text-slate-900">Administrasi</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-600">Melayani alur surat, berkas, dan kebutuhan administrasi.</p>
                </article>
                <article class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="grid h-12 w-12 place-items-center rounded-2xl bg-teal-100 text-teal-700">
                        <svg class="h-6 w-6" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.7">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 3.5 4.5 6v3c0 3.5 2.3 6.5 5.5 7.5 3.2-1 5.5-4 5.5-7.5V6L10 3.5Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 10l1.5 1.5L12.5 8.5" />
                        </svg>
                    </div>
                    <h3 class="mt-4 text-lg font-semibold text-slate-900">Pengawasan</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-600">Menjaga standar layanan agar tetap tertib dan transparan.</p>
                </article>
                <article class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="grid h-12 w-12 place-items-center rounded-2xl bg-rose-100 text-rose-700">
                        <svg class="h-6 w-6" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.7">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 15.5V11M10 15.5V8M14 15.5V5" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 16h11" />
                        </svg>
                    </div>
                    <h3 class="mt-4 text-lg font-semibold text-slate-900">Data dan Laporan</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-600">Mengelola informasi, rekap, dan tindak lanjut layanan.</p>
                </article>
                <article class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="grid h-12 w-12 place-items-center rounded-2xl bg-indigo-100 text-indigo-700">
                        <svg class="h-6 w-6" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.7">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 15.5c1.5-2.5 3.8-4 6-4s4.5 1.5 6 4" />
                            <circle cx="10" cy="7" r="2.5" />
                        </svg>
                    </div>
                    <h3 class="mt-4 text-lg font-semibold text-slate-900">Layanan Front Office</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-600">Melayani kebutuhan awal masyarakat dan informasi umum.</p>
                </article>
            </div>
        </section>

        <section id="maklumat" class="mx-auto max-w-7xl px-6 py-10 lg:px-8">
            <div class="grid gap-6 lg:grid-cols-[.9fr_1.1fr]">
                <div class="rounded-[2rem] border border-cyan-100 bg-gradient-to-br from-cyan-50 via-white to-sky-50 p-8 shadow-sm">
                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-cyan-700">Section 2</p>
                    <h2 class="mt-2 text-3xl font-semibold text-slate-900">Maklumat pelayanan</h2>
                    <p class="mt-4 text-sm leading-7 text-slate-600">
                        Kami berkomitmen memberikan pelayanan yang cepat, transparan, akurat, dan mudah diakses.
                    </p>
                    <div class="mt-6 space-y-3">
                        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                            <p class="text-sm text-slate-500">1. Pelayanan dilakukan secara profesional dan ramah.</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                            <p class="text-sm text-slate-500">2. Informasi disampaikan jelas dan dapat dipertanggungjawabkan.</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                            <p class="text-sm text-slate-500">3. Setiap masukan ditindaklanjuti sesuai prosedur.</p>
                        </div>
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm">
                        <p class="text-sm text-slate-500">Standar 01</p>
                        <h3 class="mt-2 text-lg font-semibold text-slate-900">Ketepatan waktu</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">Layanan diproses sesuai SLA yang telah ditetapkan.</p>
                    </div>
                    <div class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm">
                        <p class="text-sm text-slate-500">Standar 02</p>
                        <h3 class="mt-2 text-lg font-semibold text-slate-900">Transparansi</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">Alur dan status layanan disampaikan dengan jelas.</p>
                    </div>
                    <div class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm">
                        <p class="text-sm text-slate-500">Standar 03</p>
                        <h3 class="mt-2 text-lg font-semibold text-slate-900">Akuntabilitas</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">Setiap proses terdokumentasi dan mudah dilacak.</p>
                    </div>
                    <div class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm">
                        <p class="text-sm text-slate-500">Standar 04</p>
                        <h3 class="mt-2 text-lg font-semibold text-slate-900">Respon cepat</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">Pertanyaan dan pengaduan ditangani secepat mungkin.</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="testimoni" class="mx-auto max-w-7xl px-6 py-10 lg:px-8">
            <div class="flex items-end justify-between gap-4">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-cyan-700">Section 3</p>
                    <h2 class="mt-2 text-3xl font-semibold text-slate-900">Testimoni</h2>
                </div>
                <p class="hidden max-w-xl text-sm leading-6 text-slate-500 md:block">
                    Umpan balik singkat dari pengguna yang sudah merasakan alur pelayanan yang lebih nyaman.
                </p>
            </div>

            <div class="mt-8 grid gap-5 lg:grid-cols-3">
                <figure class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm">
                    <blockquote class="text-sm leading-7 text-slate-600">
                        "Tampilannya jelas dan informasinya mudah dicari. Prosesnya juga terasa lebih cepat."
                    </blockquote>
                    <figcaption class="mt-5 flex items-center gap-3">
                        <div class="grid h-11 w-11 place-items-center rounded-full bg-cyan-100 font-semibold text-cyan-700">A</div>
                        <div>
                            <p class="font-semibold text-slate-900">Ahmad</p>
                            <p class="text-sm text-slate-500">Pengguna layanan</p>
                        </div>
                    </figcaption>
                </figure>

                <figure class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm">
                    <blockquote class="text-sm leading-7 text-slate-600">
                        "Alurnya rapi, jadi saya tahu harus mulai dari mana dan dokumen apa yang dibutuhkan."
                    </blockquote>
                    <figcaption class="mt-5 flex items-center gap-3">
                        <div class="grid h-11 w-11 place-items-center rounded-full bg-teal-100 font-semibold text-teal-700">S</div>
                        <div>
                            <p class="font-semibold text-slate-900">Siti</p>
                            <p class="text-sm text-slate-500">Masyarakat</p>
                        </div>
                    </figcaption>
                </figure>

                <figure class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm">
                    <blockquote class="text-sm leading-7 text-slate-600">
                        "Informasinya ringkas, tampilan bersih, dan sangat membantu untuk akses layanan awal."
                    </blockquote>
                    <figcaption class="mt-5 flex items-center gap-3">
                        <div class="grid h-11 w-11 place-items-center rounded-full bg-indigo-100 font-semibold text-indigo-700">R</div>
                        <div>
                            <p class="font-semibold text-slate-900">Rizky</p>
                            <p class="text-sm text-slate-500">Responden survei</p>
                        </div>
                    </figcaption>
                </figure>
            </div>
        </section>

        <section id="kontak-kami" class="mx-auto max-w-7xl px-6 py-10 lg:px-8">
            <div class="grid gap-6 lg:grid-cols-[1fr_.95fr]">
                <div class="rounded-[2rem] border border-slate-200 bg-white p-8 shadow-sm">
                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-cyan-700">Kontak Kami</p>
                    <h2 class="mt-2 text-3xl font-semibold text-slate-900">Hubungi tim layanan</h2>
                    <p class="mt-4 text-sm leading-7 text-slate-600">
                        Jika ada pertanyaan, permintaan informasi, atau tindak lanjut, silakan hubungi kanal resmi berikut.
                    </p>

                    <div class="mt-6 space-y-3">
                        <div class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <div class="grid h-11 w-11 place-items-center rounded-2xl bg-cyan-100 text-cyan-700">
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.7">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 5.5h11v9h-11z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m5.5 6.5 4.5 3 4.5-3" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-900">Email</p>
                                <p class="text-sm text-slate-600">kontak@kemenagtd.go.id</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <div class="grid h-11 w-11 place-items-center rounded-2xl bg-teal-100 text-teal-700">
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.7">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 4.5h6A2.5 2.5 0 0 1 15.5 7v6A2.5 2.5 0 0 1 13 15.5H7A2.5 2.5 0 0 1 4.5 13V7A2.5 2.5 0 0 1 7 4.5Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 9.5 10 11l2-1.5" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-900">Telepon</p>
                                <p class="text-sm text-slate-600">(021) 555-0123</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <div class="grid h-11 w-11 place-items-center rounded-2xl bg-rose-100 text-rose-700">
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.7">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 18s5-4.5 5-9a5 5 0 0 0-10 0c0 4.5 5 9 5 9Z" />
                                    <circle cx="10" cy="9" r="1.8" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-900">Alamat</p>
                                <p class="text-sm text-slate-600">Jl. Pelayanan No. 12, Jakarta</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-[2rem] border border-slate-200 bg-[linear-gradient(180deg,_#eff6ff_0%,_#ffffff_100%)] p-8 shadow-sm">
                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-cyan-700">Jam Layanan</p>
                    <div class="mt-4 space-y-3">
                        <div class="rounded-2xl border border-white bg-white p-4 shadow-sm">
                            <p class="text-sm font-semibold text-slate-900">Senin - Kamis</p>
                            <p class="mt-1 text-sm text-slate-600">08.00 - 16.00 WIB</p>
                        </div>
                        <div class="rounded-2xl border border-white bg-white p-4 shadow-sm">
                            <p class="text-sm font-semibold text-slate-900">Jumat</p>
                            <p class="mt-1 text-sm text-slate-600">08.00 - 16.30 WIB</p>
                        </div>
                        <div class="rounded-2xl border border-white bg-white p-4 shadow-sm">
                            <p class="text-sm font-semibold text-slate-900">Sabtu - Minggu</p>
                            <p class="mt-1 text-sm text-slate-600">Tutup</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="gallery" class="mx-auto max-w-7xl px-6 py-10 lg:px-8">
            <div class="flex items-end justify-between gap-4">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-cyan-700">Gallery</p>
                    <h2 class="mt-2 text-3xl font-semibold text-slate-900">Dokumentasi</h2>
                </div>
                <p class="hidden max-w-xl text-sm leading-6 text-slate-500 md:block">
                    Kumpulan tampilan visual untuk memberi kesan aktif, informatif, dan terdokumentasi.
                </p>
            </div>

            <div class="mt-8 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                <div class="group overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white p-4 shadow-sm">
                    <div class="h-56 rounded-[1.25rem] bg-[linear-gradient(135deg,_#e0f2fe_0%,_#ffffff_55%,_#bae6fd_100%)]"></div>
                    <p class="mt-4 text-sm font-medium text-slate-700">Ruang layanan</p>
                </div>
                <div class="group overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white p-4 shadow-sm">
                    <div class="h-56 rounded-[1.25rem] bg-[linear-gradient(135deg,_#ecfeff_0%,_#ffffff_55%,_#99f6e4_100%)]"></div>
                    <p class="mt-4 text-sm font-medium text-slate-700">Kegiatan sosialisasi</p>
                </div>
                <div class="group overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white p-4 shadow-sm">
                    <div class="h-56 rounded-[1.25rem] bg-[linear-gradient(135deg,_#eef2ff_0%,_#ffffff_55%,_#c7d2fe_100%)]"></div>
                    <p class="mt-4 text-sm font-medium text-slate-700">Dukungan digital</p>
                </div>
                <div class="group overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white p-4 shadow-sm">
                    <div class="h-56 rounded-[1.25rem] bg-[linear-gradient(135deg,_#fef3c7_0%,_#ffffff_55%,_#fde68a_100%)]"></div>
                    <p class="mt-4 text-sm font-medium text-slate-700">Pelayanan publik</p>
                </div>
                <div class="group overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white p-4 shadow-sm">
                    <div class="h-56 rounded-[1.25rem] bg-[linear-gradient(135deg,_#ffe4e6_0%,_#ffffff_55%,_#fecdd3_100%)]"></div>
                    <p class="mt-4 text-sm font-medium text-slate-700">Koordinasi internal</p>
                </div>
                <div class="group overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white p-4 shadow-sm">
                    <div class="h-56 rounded-[1.25rem] bg-[linear-gradient(135deg,_#ede9fe_0%,_#ffffff_55%,_#ddd6fe_100%)]"></div>
                    <p class="mt-4 text-sm font-medium text-slate-700">Kabar layanan</p>
                </div>
            </div>
        </section>

        <footer class="mt-6 border-t border-slate-200 bg-white/70">
            <div class="mx-auto flex max-w-7xl flex-col gap-4 px-6 py-8 lg:flex-row lg:items-center lg:justify-between lg:px-8">
                <div>
                    <p class="text-sm font-semibold tracking-[0.18em] text-slate-900 uppercase">SILATAR</p>
                    <p class="mt-2 text-sm text-slate-500">Sistem Informasi, Layanan, dan Administrasi Kankemenag Tanah Datar.</p>
                </div>
                <div class="flex flex-wrap gap-3 text-sm text-slate-500">
                    <a href="#home" class="transition hover:text-slate-900">Home</a>
                    <a href="{{ route('satuan-kerja') }}" class="transition hover:text-slate-900">Satuan Kerja</a>
                    <a href="{{ route('pelayanan') }}" class="transition hover:text-slate-900">Pelayanan</a>
                    <a href="#kontak-kami" class="transition hover:text-slate-900">Kontak Kami</a>
                </div>
            </div>
        </footer>
    </main>
</x-layouts.app>
