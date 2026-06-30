<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PpidController extends Controller
{
    /**
     * Main PPID page (Beranda)
     */
    public function index()
    {
        return view('ppid.index', [
            'title' => 'PPID - Pejabat Pengelola Informasi dan Dokumentasi',
        ]);
    }

    /**
     * Profil Singkat
     */
    public function profilSingkat()
    {
        return view('ppid.profil-singkat', [
            'title' => 'Profil Singkat - PPID',
        ]);
    }

    /**
     * Visi Misi
     */
    public function visiMisi()
    {
        return view('ppid.visi-misi', [
            'title' => 'Visi Misi - PPID',
        ]);
    }

    /**
     * Tugas, Fungsi, dan Wewenang
     */
    public function tugasFungsi()
    {
        return view('ppid.tugas-fungsi', [
            'title' => 'Tugas, Fungsi, dan Wewenang - PPID',
        ]);
    }

    /**
     * Struktur Kelembagaan
     */
    public function struktur()
    {
        return view('ppid.struktur', [
            'title' => 'Struktur Kelembagaan - PPID',
        ]);
    }

    /**
     * Peraturan Perundang-undangan
     */
    public function regulasi()
    {
        return view('ppid.regulasi', [
            'title' => 'Regulasi - PPID',
        ]);
    }

    /**
     * Maklumat Pelayanan
     */
    public function maklumat()
    {
        return view('ppid.maklumat', [
            'title' => 'Maklumat Pelayanan - PPID',
        ]);
    }

    /**
     * Jadwal Layanan
     */
    public function jadwal()
    {
        return view('ppid.jadwal', [
            'title' => 'Jadwal Layanan - PPID',
        ]);
    }

    /**
     * Biaya Layanan
     */
    public function biaya()
    {
        return view('ppid.biaya', [
            'title' => 'Biaya Layanan - PPID',
        ]);
    }

    /**
     * Laporan Layanan
     */
    public function laporanLayanan()
    {
        return view('ppid.laporan-layanan', [
            'title' => 'Laporan Layanan - PPID',
        ]);
    }

    /**
     * Prosedur Layanan - Tata Cara Permohonan Informasi Publik
     */
    public function prosedurPermohonan()
    {
        return view('ppid.prosedur-permohonan', [
            'title' => 'Tata Cara Permohonan Informasi Publik - PPID',
        ]);
    }

    /**
     * Prosedur Layanan - Tata Cara Pengajuan Keberatan
     */
    public function prosedurKeberatan()
    {
        return view('ppid.prosedur-keberatan', [
            'title' => 'Tata Cara Pengajuan Keberatan - PPID',
        ]);
    }

    /**
     * Prosedur Layanan - Tata Cara Pengajuan Permohonan Penyelesaian Sengketa
     */
    public function prosedurSengketa()
    {
        return view('ppid.prosedur-sengketa', [
            'title' => 'Tata Cara Pengajuan Permohonan Penyelesaian Sengketa - PPID',
        ]);
    }

    /**
     * Formulir Permohonan Informasi Publik
     */
    public function formulirPermohonan()
    {
        return view('ppid.formulir-permohonan', [
            'title' => 'Formulir Permohonan Informasi Publik - PPID',
        ]);
    }

    /**
     * Formulir Pengajuan Keberatan
     */
    public function formulirKeberatan()
    {
        return view('ppid.formulir-keberatan', [
            'title' => 'Formulir Pengajuan Keberatan - PPID',
        ]);
    }

    /**
     * Daftar Informasi Publik - Informasi Diumumkan Berkala
     */
    public function informasiBerkala()
    {
        return view('ppid.informasi-berkala', [
            'title' => 'Informasi Diumumkan Berkala - PPID',
        ]);
    }

    /**
     * Daftar Informasi Publik - Informasi Serta Merta
     */
    public function informasiSertaMerta()
    {
        return view('ppid.informasi-serta-merta', [
            'title' => 'Informasi Serta Merta - PPID',
        ]);
    }

    /**
     * Daftar Informasi Publik - Informasi Tersedia Setiap Saat
     */
    public function informasiSetiapSaat()
    {
        return view('ppid.informasi-setiap-saat', [
            'title' => 'Informasi Tersedia Setiap Saat - PPID',
        ]);
    }

    /**
     * Pengaduan
     */
    public function pengaduan()
    {
        return view('ppid.pengaduan', [
            'title' => 'Pengaduan - PPID',
        ]);
    }

    /**
     * Gallery - Fasilitas Publik
     */
    public function galleryFasilitas()
    {
        return view('ppid.gallery-fasilitas', [
            'title' => 'Fasilitas Publik - Gallery PPID',
        ]);
    }

    /**
     * Gallery - Kegiatan
     */
    public function galleryKegiatan()
    {
        return view('ppid.gallery-kegiatan', [
            'title' => 'Kegiatan - Gallery PPID',
        ]);
    }

    /**
     * Tentang Kami
     */
    public function tentangKami()
    {
        return view('ppid.tentang-kami', [
            'title' => 'Tentang Kami - PPID',
        ]);
    }
}
