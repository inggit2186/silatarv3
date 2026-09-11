<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ketidakhadiran extends Model
{
    protected $table = 'ktd_ketidakhadiran';

    protected $fillable = [
        'jenis',
        'potongan',
    ];

    protected $casts = [
        'potongan' => 'float',
    ];

    public $timestamps = true;
}
