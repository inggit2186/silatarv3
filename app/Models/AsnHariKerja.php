<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AsnHariKerja extends Model
{
    protected $table = 'asn_harikerja';

    protected $fillable = [
        'user_id',
        'harikerja',
    ];

    public $timestamps = true;

    /**
     * Get the user that owns this working schedule
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the working schedule details
     */
    public function hariKerja()
    {
        return $this->belongsTo(HariKerja::class, 'harikerja');
    }
}
