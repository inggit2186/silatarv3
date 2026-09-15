<x-layouts.ppid-layout title="{{ $page->title }}">
    <x-ppid.nav />

    <main class="ppid-content">
        <div class="ppid-page">
            <div class="ppid-page-breadcrumb">
                <a href="{{ route('ppid') }}">PPID</a>
                <span>/</span>
                <span>Standar Layanan</span>
                <span>/</span>
                <span>Biaya</span>
            </div>
            <div class="ppid-page-header" data-reveal>
                <h1 class="ppid-page-title">{{ $page->title }}</h1>
                <p class="ppid-page-subtitle">{{ $page->subtitle ?? 'Biaya Pelayanan Informasi Publik' }}</p>
            </div>

            {{-- Informasi Biaya Section --}}
            @if(isset($sectionsByType['informasi_biaya']))
                @php $biaya = $sectionsByType['informasi_biaya']->first(); @endphp
                <section class="ppid-section" data-reveal>
                    <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 1.5rem; padding: 2.5rem; color: white; text-align: center; margin-bottom: 2rem;">
                        <h2 style="font-family: var(--font-display); font-size: 1.5rem; font-weight: 600; margin: 0 0 0.5rem;">
                            {{ $biaya->title ?? 'Gratis!' }}
                        </h2>
                        <p style="font-size: 1rem; opacity: 0.95; margin: 0;">
                            {!! $biaya->content ?? 'Layanan Informasi Publik Tidak Dipungut Biaya' !!}
                        </p>
                    </div>
                </section>
            @endif

            <section class="ppid-section" data-reveal>
                <h2 class="ppid-section-title">Ketentuan</h2>
                <div class="ppid-section-content">
                    <p>Berdasarkan UU No. 14 Tahun 2008, Badan Publik wajib memberikan informasi secara gratis. Pemohon tidak dipungut biaya untuk:</p>
                </div>
                <ul class="ppid-list">
                    <li>Melihat dan membaca informasi di ruang layanan</li>
                    <li>Mengunduh informasi dari portal online</li>
                    <li>Memohon informasi melalui formulir</li>
                    <li>Menerima salinan informasi dalam bentuk softcopy</li>
                </ul>
            </section>

            {{-- Pengecualian Section --}}
            @if(isset($sectionsByType['pengecualian']))
                @php $pengecualian = $sectionsByType['pengecualian']->first(); @endphp
                @php $tableData = json_decode($pengecualian->metadata); @endphp
                <section class="ppid-section" data-reveal>
                    <h2 class="ppid-section-title">{{ $pengecualian->title ?? 'Pengecualian' }}</h2>
                    <div class="ppid-info-box">
                        <h3 class="ppid-info-box-title">Biaya Penggandaan</h3>
                        <p class="ppid-info-box-text">Hanya informasi dalam bentuk hardcopy yang dapat dikenakan biaya penggandaan sesuai biaya riil.</p>
                    </div>
                    <div style="background: white; border: 1px solid rgba(140, 135, 130, 0.15); border-radius: 1.5rem; overflow: hidden; margin-top: 1.5rem;">
                        <table class="ppid-table">
                            <thead>
                                <tr>
                                    @foreach($tableData->headers as $header)
                                        <th>{{ $header }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tableData->rows as $row)
                                    <tr>
                                        @foreach($row as $cell)
                                            <td>{{ $cell }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>
            @endif
        </div>
        @include('ppid.partials.footer')
    </main>
</x-layouts.ppid-layout>
