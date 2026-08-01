<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    protected $table = 'ktd_layanan';

    protected $fillable = [
        'nama',
        'deskripsi',
        'dept_id',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'dept_id');
    }
}
