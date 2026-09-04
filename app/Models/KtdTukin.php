<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KtdTukin extends Model
{
    protected $table = 'ktd_tukin';

    protected $fillable = [
        'periode',
        'user_nip',
        'tukin',
        'tk_jumlah',
        'tk_persen',
        'tl',
        'tl_persen',
        'psw',
        'psw_persen',
        'hukdis',
        'hukdis_persen',
        'cpns',
        'cpns_persen',
        'skp',
        'skp_persen',
        'tb',
        'tb_persen',
        'potongan_lain',
        'potongan_lain_persen',
        'total_potongan',
        'import_batch_id',
        'imported_by',
        'imported_at',
        'import_source',
    ];

    protected $casts = [
        'tukin' => 'decimal:2',
        'tk_jumlah' => 'decimal:2',
        'tk_persen' => 'decimal:2',
        'tl' => 'decimal:2',
        'tl_persen' => 'decimal:2',
        'psw' => 'decimal:2',
        'psw_persen' => 'decimal:2',
        'hukdis' => 'decimal:2',
        'hukdis_persen' => 'decimal:2',
        'cpns' => 'decimal:2',
        'cpns_persen' => 'decimal:2',
        'skp' => 'decimal:2',
        'skp_persen' => 'decimal:2',
        'tb' => 'decimal:2',
        'tb_persen' => 'decimal:2',
        'potongan_lain' => 'decimal:2',
        'potongan_lain_persen' => 'decimal:2',
        'total_potongan' => 'decimal:2',
        'imported_at' => 'datetime',
    ];

    /**
     * Get the user that owns this tukin record
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_nip', 'nomor_induk');
    }
}
