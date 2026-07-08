<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SatkerFilepemberkasan extends Model
{
    protected $table = 'satker_filepemberkasan';

    protected $fillable = [
        'user_id',
        'noreq',
        'layanan_id',
        'syarat_id',
        'filename',
        'filetype',
        'size',
        'status',
    ];

    protected $casts = [
        'status' => 'integer',
    ];
}
