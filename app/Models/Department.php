<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = 'ktd_department';

    protected $fillable = [
        'nama',
        'kode',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'dept_id');
    }

    public function instansi()
    {
        return $this->hasOne(Instansi::class, 'dept_id');
    }
}
