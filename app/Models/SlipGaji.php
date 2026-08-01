<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SlipGaji extends Model
{
    protected $table = 'ktd_slipgaji';

    protected $fillable = [
        'user_nip',
        'tanggal',
        'gaji_pokok',
        'tunjangan',
        'potongan',
        'total',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_nip', 'nomor_induk');
    }
}
