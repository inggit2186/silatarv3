<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Konsul extends Model
{
    protected $table = 'ktd_konsul';

    protected $fillable = [
        'user_id',
        'subject',
        'pertanyaan',
        'jawaban',
        'status',
    ];

    const STATUS_PENDING = 'PENDING';
    const STATUS_DONE = 'DONE';
}
