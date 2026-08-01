<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersRequest extends Model
{
    protected $table = 'users_request';

    protected $fillable = [
        'no_req',
        'no_surat',
        'tgl_surat',
        'kategori',
        'user_id',
        'judul',
        'deskripsi',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function bp()
    {
        return $this->belongsTo(BadanPeradilan::class, 'user_id');
    }
}
