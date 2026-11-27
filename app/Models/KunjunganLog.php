<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KunjunganLog extends Model
{
    protected $table = 'kunjungan_log';

    public $timestamps = false;

    protected $fillable = [
        'ip_address',
        'user_agent',
        'halaman',
        'visited_at',
    ];

    protected function casts(): array
    {
        return [
            'visited_at' => 'datetime',
        ];
    }
}
