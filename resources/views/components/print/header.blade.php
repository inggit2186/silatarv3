{{--
    Kop surat (letterhead) untuk dokumen cetak resmi.

    Susunan: logo Kementerian Agama di kiri, identitas kantor di tengah,
    logo SILATAR di kanan. Dipakai sebagai <x-print.header />.

    Semua properti dapat dioverride dari pemanggil.
--}}
@props([
    'fontBase' => null,
    'logoKemenag' => null,
    'logoSilatar' => null,
    'sizeKemenag' => '18mm',
    'sizeSilatar' => '19mm',
    'judulUtama' => 'KEMENTERIAN AGAMA REPUBLIK INDONESIA',
    'judulKantor' => 'KANTOR KEMENTERIAN AGAMA KABUPATEN TANAH DATAR',
    'alamat' => 'Jln. M.Yamin Bukit Gombak Batusangkar Telp. (0752) 71033 Fax 72932',
    'kontak' => 'Website: https://kemenagtanahdatar.id Email: kemenagtanahdatar2@gmail.com',
])

@php
    $fontBase = $fontBase ?: asset('fonts/pdf');
    $logoKemenag = $logoKemenag ?: asset('favicon.webp');
    $logoSilatar = $logoSilatar ?: asset('assets/img/ikon/silatar_logo.png');
@endphp

<style>
    @font-face { font-family: 'Space Mono'; font-weight: 400; font-style: normal; src: url('{{ $fontBase }}/SpaceMono-Regular.ttf') format('truetype'); }
    @font-face { font-family: 'Space Mono'; font-weight: 700; font-style: normal; src: url('{{ $fontBase }}/SpaceMono-Bold.ttf') format('truetype'); }

    .kepala-surat { width: 100%; border-collapse: collapse; }
    .kepala-surat td { vertical-align: middle; }
    .kepala-surat .kolom-logo { width: 20mm; }
    .kepala-surat .kolom-logo-kanan { text-align: right; }
    .kepala-surat .kolom-teks { text-align: center; }

    .kepala-surat .logo-kemenag { width: {{ $sizeKemenag }}; height: auto; }
    .kepala-surat .logo-silatar { width: {{ $sizeSilatar }}; height: auto; }

    .kepala-surat .teks-judul {
        font-family: 'Space Mono', monospace; font-weight: 700; color: #2d2824;
        letter-spacing: 0.3pt; line-height: 1.25;
    }
    .kepala-surat .teks-judul-utama { font-size: 10.6pt; }
    .kepala-surat .teks-judul-kantor { font-size: 9pt; margin-top: 0.7mm; }
    .kepala-surat .teks-info {
        font-family: 'Space Mono', monospace; font-weight: 400; color: #514c46;
        font-size: 7.6pt; line-height: 1.4; margin-top: 1mm;
    }
    .kepala-surat .teks-info + .teks-info { margin-top: 0.4mm; }
</style>

<table class="kepala-surat">
    <tr>
        <td class="kolom-logo">
            <img class="logo-kemenag" src="{{ $logoKemenag }}" alt="Logo Kementerian Agama">
        </td>
        <td class="kolom-teks">
            <div class="teks-judul teks-judul-utama">{{ $judulUtama }}</div>
            <div class="teks-judul teks-judul-kantor">{{ $judulKantor }}</div>
            <div class="teks-info">{{ $alamat }}</div>
            <div class="teks-info">{{ $kontak }}</div>
        </td>
        <td class="kolom-logo kolom-logo-kanan">
            <img class="logo-silatar" src="{{ $logoSilatar }}" alt="Logo SILATAR">
        </td>
    </tr>
</table>
