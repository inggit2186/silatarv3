<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PpidController extends Controller
{
    /**
     * Helper method to get page data with sections.
     */
    protected function getPageData(string $slug): ?array
    {
        $page = DB::table('ppid_pages')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->first();

        if (!$page) {
            return null;
        }

        $sections = DB::table('ppid_sections')
            ->where('page_id', $page->id)
            ->where('is_visible', true)
            ->orderBy('sort_order')
            ->get();

        // Group sections by section_key
        $sectionsByType = $sections->groupBy('section_key');

        return [
            'page' => $page,
            'sections' => $sections,
            'sectionsByType' => $sectionsByType,
        ];
    }

    /**
     * Main PPID page (Beranda)
     */
    public function index()
    {
        $data = $this->getPageData('index');

        if (!$data) {
            abort(404);
        }

        return view('ppid.index', [
            'title' => $data['page']->title,
            'page' => $data['page'],
            'sectionsByType' => $data['sectionsByType'],
        ]);
    }

    /**
     * Profil Singkat
     */
    public function profilSingkat()
    {
        $data = $this->getPageData('profil-singkat');

        if (!$data) {
            abort(404);
        }

        return view('ppid.profil-singkat', [
            'title' => $data['page']->title,
            'page' => $data['page'],
            'sectionsByType' => $data['sectionsByType'],
        ]);
    }

    /**
     * Visi Misi
     */
    public function visiMisi()
    {
        $data = $this->getPageData('visi-misi');

        if (!$data) {
            abort(404);
        }

        return view('ppid.visi-misi', [
            'title' => $data['page']->title,
            'page' => $data['page'],
            'sectionsByType' => $data['sectionsByType'],
        ]);
    }

    /**
     * Tugas, Fungsi, dan Wewenang
     */
    public function tugasFungsi()
    {
        $data = $this->getPageData('tugas-fungsi');

        if (!$data) {
            abort(404);
        }

        return view('ppid.tugas-fungsi', [
            'title' => $data['page']->title,
            'page' => $data['page'],
            'sectionsByType' => $data['sectionsByType'],
        ]);
    }

    /**
     * Struktur Kelembagaan
     */
    public function struktur()
    {
        $data = $this->getPageData('struktur');

        if (!$data) {
            abort(404);
        }

        return view('ppid.struktur', [
            'title' => $data['page']->title,
            'page' => $data['page'],
            'sectionsByType' => $data['sectionsByType'],
        ]);
    }

    /**
     * Peraturan Perundang-undangan
     */
    public function regulasi()
    {
        $data = $this->getPageData('regulasi');

        if (!$data) {
            abort(404);
        }

        return view('ppid.regulasi', [
            'title' => $data['page']->title,
            'page' => $data['page'],
            'sectionsByType' => $data['sectionsByType'],
        ]);
    }

    /**
     * Maklumat Pelayanan
     */
    public function maklumat()
    {
        $data = $this->getPageData('maklumat');

        if (!$data) {
            abort(404);
        }

        return view('ppid.maklumat', [
            'title' => $data['page']->title,
            'page' => $data['page'],
            'sectionsByType' => $data['sectionsByType'],
        ]);
    }

    /**
     * Jadwal Layanan
     */
    public function jadwal()
    {
        $data = $this->getPageData('jadwal');

        if (!$data) {
            abort(404);
        }

        return view('ppid.jadwal', [
            'title' => $data['page']->title,
            'page' => $data['page'],
            'sectionsByType' => $data['sectionsByType'],
        ]);
    }

    /**
     * Biaya Layanan
     */
    public function biaya()
    {
        $data = $this->getPageData('biaya');

        if (!$data) {
            abort(404);
        }

        return view('ppid.biaya', [
            'title' => $data['page']->title,
            'page' => $data['page'],
            'sectionsByType' => $data['sectionsByType'],
        ]);
    }

    /**
     * Laporan Layanan
     */
    public function laporanLayanan()
    {
        $data = $this->getPageData('laporan-layanan');

        if (!$data) {
            abort(404);
        }

        return view('ppid.laporan-layanan', [
            'title' => $data['page']->title,
            'page' => $data['page'],
            'sectionsByType' => $data['sectionsByType'],
        ]);
    }

    /**
     * Prosedur Layanan - Tata Cara Permohonan Informasi Publik
     */
    public function prosedurPermohonan()
    {
        $data = $this->getPageData('prosedur-permohonan');

        if (!$data) {
            abort(404);
        }

        return view('ppid.prosedur-permohonan', [
            'title' => $data['page']->title,
            'page' => $data['page'],
            'sectionsByType' => $data['sectionsByType'],
        ]);
    }

    /**
     * Prosedur Layanan - Tata Cara Pengajuan Keberatan
     */
    public function prosedurKeberatan()
    {
        $data = $this->getPageData('prosedur-keberatan');

        if (!$data) {
            abort(404);
        }

        return view('ppid.prosedur-keberatan', [
            'title' => $data['page']->title,
            'page' => $data['page'],
            'sectionsByType' => $data['sectionsByType'],
        ]);
    }

    /**
     * Prosedur Layanan - Tata Cara Pengajuan Permohonan Penyelesaian Sengketa
     */
    public function prosedurSengketa()
    {
        $data = $this->getPageData('prosedur-sengketa');

        if (!$data) {
            abort(404);
        }

        return view('ppid.prosedur-sengketa', [
            'title' => $data['page']->title,
            'page' => $data['page'],
            'sectionsByType' => $data['sectionsByType'],
        ]);
    }

    /**
     * Formulir Permohonan Informasi Publik
     */
    public function formulirPermohonan()
    {
        $data = $this->getPageData('formulir-permohonan');

        if (!$data) {
            abort(404);
        }

        return view('ppid.formulir-permohonan', [
            'title' => $data['page']->title,
            'page' => $data['page'],
            'sectionsByType' => $data['sectionsByType'],
        ]);
    }

    /**
     * Formulir Pengajuan Keberatan
     */
    public function formulirKeberatan()
    {
        $data = $this->getPageData('formulir-keberatan');

        if (!$data) {
            abort(404);
        }

        return view('ppid.formulir-keberatan', [
            'title' => $data['page']->title,
            'page' => $data['page'],
            'sectionsByType' => $data['sectionsByType'],
        ]);
    }

    /**
     * Daftar Informasi Publik - Informasi Diumumkan Berkala
     */
    public function informasiBerkala()
    {
        $data = $this->getPageData('informasi-berkala');

        if (!$data) {
            abort(404);
        }

        return view('ppid.informasi-berkala', [
            'title' => $data['page']->title,
            'page' => $data['page'],
            'sectionsByType' => $data['sectionsByType'],
        ]);
    }

    /**
     * Daftar Informasi Publik - Informasi Serta Merta
     */
    public function informasiSertaMerta()
    {
        $data = $this->getPageData('informasi-serta-merta');

        if (!$data) {
            abort(404);
        }

        return view('ppid.informasi-serta-merta', [
            'title' => $data['page']->title,
            'page' => $data['page'],
            'sectionsByType' => $data['sectionsByType'],
        ]);
    }

    /**
     * Daftar Informasi Publik - Informasi Tersedia Setiap Saat
     */
    public function informasiSetiapSaat()
    {
        $data = $this->getPageData('informasi-setiap-saat');

        if (!$data) {
            abort(404);
        }

        return view('ppid.informasi-setiap-saat', [
            'title' => $data['page']->title,
            'page' => $data['page'],
            'sectionsByType' => $data['sectionsByType'],
        ]);
    }

    /**
     * Pengaduan
     */
    public function pengaduan()
    {
        $data = $this->getPageData('pengaduan');

        if (!$data) {
            abort(404);
        }

        return view('ppid.pengaduan', [
            'title' => $data['page']->title,
            'page' => $data['page'],
            'sectionsByType' => $data['sectionsByType'],
        ]);
    }

    /**
     * Gallery - Fasilitas Publik
     */
    public function galleryFasilitas()
    {
        $data = $this->getPageData('gallery-fasilitas');

        if (!$data) {
            abort(404);
        }

        // Get gallery items
        $galleryItems = DB::table('ppid_gallery')
            ->where('page_slug', 'gallery-fasilitas')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('ppid.gallery-fasilitas', [
            'title' => $data['page']->title,
            'page' => $data['page'],
            'sectionsByType' => $data['sectionsByType'],
            'galleryItems' => $galleryItems,
        ]);
    }

    /**
     * Gallery - Kegiatan
     */
    public function galleryKegiatan()
    {
        $data = $this->getPageData('gallery-kegiatan');

        if (!$data) {
            abort(404);
        }

        // Get gallery items
        $galleryItems = DB::table('ppid_gallery')
            ->where('page_slug', 'gallery-kegiatan')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('ppid.gallery-kegiatan', [
            'title' => $data['page']->title,
            'page' => $data['page'],
            'sectionsByType' => $data['sectionsByType'],
            'galleryItems' => $galleryItems,
        ]);
    }

    /**
     * Tentang Kami
     */
    public function tentangKami()
    {
        $data = $this->getPageData('tentang-kami');

        if (!$data) {
            abort(404);
        }

        return view('ppid.tentang-kami', [
            'title' => $data['page']->title,
            'page' => $data['page'],
            'sectionsByType' => $data['sectionsByType'],
        ]);
    }

    /**
     * Clear cache for a specific page.
     */
    protected function clearPageCache(string $slug): void
    {
        Cache::forget("ppid_page_{$slug}");
    }
}
