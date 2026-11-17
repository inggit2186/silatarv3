<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UjianPelanggaran extends Model
{
    protected $table = 'ujian_pelanggaran';

    /**
     * Jenis pelanggaran beserta label yang ditampilkan di halaman admin.
     */
    public const LABEL = [
        'perpindahan_fokus' => 'Berpindah tab / jendela',
        'keluar_layar_penh' => 'Keluar dari mode layar penuh',
        'tanpa_layar_penh' => 'Menjalankan ujian tanpa layar penuh',
        'sesi_ganda' => 'Ujian dibuka dari perangkat lain',
        'tab_baru' => 'Upaya membuka tab / jendela baru',
        'salin' => 'Upaya menyalin isi soal',
        'tempel' => 'Upaya menempel teks',
        'cetak' => 'Upaya mencetak halaman ujian',
    ];

    protected $fillable = [
        'ujian_id',
        'user_id',
        'jenis',
        'keterangan',
        'ip',
    ];

    public function label(): string
    {
        return self::LABEL[$this->jenis] ?? $this->jenis;
    }

    public function ujian(): BelongsTo
    {
        return $this->belongsTo(Ujian::class, 'ujian_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
