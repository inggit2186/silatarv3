<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pendidikan extends Model
{
    protected $table = 'up_pendidikan';

    protected $fillable = [
        'user_id',
        'jenjang',
        'nama_sekolah',
        'jurusan',
        'tahun_lulus',
        'file',
        'status',
    ];

    const STATUS_PENDING = 0;
    const STATUS_APPROVED = 1;
    const STATUS_REJECTED = 2;
    const STATUS_EXPIRED = 99;
}
