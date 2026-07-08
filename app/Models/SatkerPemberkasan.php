<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SatkerPemberkasan extends Model
{
    protected $table = 'satker_pemberkasan';

    protected $fillable = [
        'tipe',
        'layanan_id',
        'user_id',
        'dept_id',
        'waktu',
        'item_id',
        'noreq',
        'keterangan',
        'deskripsi',
        'files',
        'metadata',
        'requirements_snapshot',
        'status',
        'is_migrated',
        'migrated_at',
        'verifikator_id',
    ];

    protected $casts = [
        'waktu' => 'date',
        'files' => 'array',
        'metadata' => 'array',
        'requirements_snapshot' => 'array',
        'is_migrated' => 'boolean',
        'migrated_at' => 'datetime',
    ];
}
